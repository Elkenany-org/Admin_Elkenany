<?php

namespace Modules\Guide\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\SearchReg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Guide\Entities\Guide_Section;
use Modules\Guide\Entities\Company;
use Modules\Guide\Entities\Company_Alboum_Images;
use Modules\Guide\Entities\Company_Social_media;
use Modules\Guide\Entities\Company_product;
use Modules\Guide\Entities\Guide_Sub_Section;
use Modules\Guide\Entities\Companies_Sec;
use Modules\Guide\Entities\Company_transport;
use Modules\Guide\Entities\Company_gallary;
use Modules\Guide\Entities\Company_address;
use Modules\Countries\Entities\Country;
use Modules\Guide\Entities\Company_Rate;
use Modules\Store\Entities\Customer;
use Modules\Cities\Entities\City;
use Illuminate\Support\Str;
use Modules\FodderStock\Entities\Fodder_Stock;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Illuminate\Support\Facades\Input;
use App\Social;
use Modules\SystemAds\Transformers\LogoBannerResource;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;

class ApicompanyController extends Controller
{
    use SearchReg,ApiResponse;
    # show companies
    public function showCompanies(Request $request)
    {

        $sub_id     = $request->input("sub_id");
        $country_id = $request->input("country_id");
        $city_id    = $request->input("city_id");
        $section_id = $request->input("section_id");
        $sort       = $request->input("sort");
        $search     = $request->input("search");

        $section = Guide_Sub_Section::where('id',$sub_id)->first();

        # check section exist
        if(!$section) { return $this->ErrorMsgWithStatus('section not found'); }
        $section_id = $section->section_id;

        $section->view_count = $section->view_count + 1;
        $section->save();

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub_id)->where('type','guide')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        if(!$search){
            $compsort = Company::with(['Country','City','SubSections','sections']);

            $compsort = $compsort->whereHas('SubSections',function($q) use ($sub_id) {
                $q->where('sub_section_id',$sub_id);
            });

            $compsort = $compsort->whereIn('id',$ads)->inRandomOrder()->get();

            $cooo =  $compsort->pluck('id')->toArray();
        }


        $companies = Company::with(['Country','City','SubSections']);

        if($country_id)
        {
            $companies = $companies->where('country_id',$country_id);
        }

        if($city_id)
        {
            if($city_id == 0){
                $cities = City::where('country_id',$country_id)->get()->pluck('id');
                $companies = $companies->whereIn('city_id',$cities);
            }else{
                $companies = $companies->where('city_id',$city_id);
            }
        }



        if($sub_id)
        {
            $companies = $companies->whereHas('SubSections',function($q) use ($sub_id) {
                $q->where('sub_section_id',$sub_id);
            });
        }
        if($section_id)
        {
            $companies = $companies->whereHas('sections',function($q) use ($section_id) {
                $q->where('section_id',$section_id);
            });
        }

        if($search)
        {
            $keyword = $this->searchQuery($search);

            $compsort = Company::with(['Country','City','SubSections','sections']);

            $compsort = $compsort->whereHas('SubSections',function($q) use ($sub_id) {
                $q->where('sub_section_id',$sub_id);
            });
            $compsort = $compsort->whereIn('id',$ads)->where('name' , 'REGEXP' , $keyword)->get();
            $cooo =  $compsort->pluck('id')->toArray();
            $companies = $companies->where('name' , 'REGEXP' , $keyword);
        }

        if($sort)
        {
            if($sort == 0)
            {
                if($request->hasHeader('android')){
                    $companies = $companies->whereNotIn('id',$cooo)->orderby('name')->get();

                }else{
                    $companies = $companies->whereNotIn('id',$cooo)->orderby('name')->paginate(12);

                }
            }else{

                if($request->hasHeader('android')) {
                    $companies = $companies->whereNotIn('id',$cooo)->orderBy('rate' , 'desc')->get();

                }else{
                    $companies = $companies->whereNotIn('id',$cooo)->orderBy('rate' , 'desc')->paginate(12);

                }
            }
        }else{
            if($request->hasHeader('android')) {
                $companies = $companies->whereNotIn('id',$cooo)->get();

            }else{
                $companies = $companies->whereNotIn('id',$cooo)->paginate(12);

            }
        }



        $list = [];

        $sections = Guide_Section::select('id','name','type')->get();
        $sections->map(function($section) use($section_id){
            $section['selected'] = $section_id && $section_id == $section->id ? 1 : 0;
            return $section;
        });
        $list['sectors'] = $sections;


        $list['banners'] = count($adss) == 0 ? [] : LogoBannerResource::collection($adss);
        $list['logos'] = count($logos) == 0 ? [] : LogoBannerResource::collection($logos);

        if(count($compsort) == 0){
            $list['compsort'] = [];
        }else{
            foreach ($compsort as $key => $company)
            {
                $list['compsort'][$key]['id']                = $company->id;
                $list['compsort'][$key]['name']              = $company->name;
                $list['compsort'][$key]['rate']              = $company->rate;
                $list['compsort'][$key]['image']             = URL::to('uploads/company/images/'.$company->image);
                $list['compsort'][$key]['desc']              = Str::limit($company->short_desc, 60, '...');
                $list['compsort'][$key]['address']           = $company->address;
                if($request->hasHeader('android')|| $request->hasHeader('ios')){
                    $list['compsort'][$key]['sponser']           = 1;
                }

            }
        }

        if(count($companies) < 1)
        {
            $list['data'] = [];

            if(! $request->hasHeader('android')) {
                $list['current_page']                     = $companies->toArray()['current_page'];
                $list['last_page']                        = $companies->toArray()['last_page'];
            }
        }

        foreach ($companies as $key => $company)
        {
            $list['data'][$key]['id']                = $company->id;
            $list['data'][$key]['name']              = $company->name;
            $list['data'][$key]['rate']              = $company->rate;
            $list['data'][$key]['image']             = URL::to('uploads/company/images/'.$company->image);
            $list['data'][$key]['desc']              = Str::limit($company->short_desc, 60, '...');
            $list['data'][$key]['address']           = $company->address;
            if($request->hasHeader('android') || $request->hasHeader('ios')){

                $list['data'][$key]['sponser']           = 0;}

            if(! $request->hasHeader('android')) {
                $list['current_page']                    = $companies->toArray()['current_page'];
                $list['last_page']                       = $companies->toArray()['last_page'];
                $list['first_page_url']                  = $companies->toArray()['first_page_url'];
                $list['next_page_url']                   = $companies->toArray()['next_page_url'];
                $list['last_page_url']                   = $companies->toArray()['last_page_url'];
            }
        }


        if($search){

            $list['data']=array_merge($list['compsort'],$list['data']);
            $list['compsort']=[];
            return response()->json([
                'message'  => null,
                'error'    => null,
                'data'     => $list
            ],200);
        }

        if($request->hasHeader('ios')){
            $list['data']=array_merge($list['compsort'],$list['data']);
            return response()->json([
                'message'  => null,
                'error'    => null,
                'data'     => $list
            ],200);
        }
        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show filter sections
    public function filterSections(Request $request)
    {
        $id = $request->input("section_id");
        $country_id = $request->input("country_id");
        $city_id = $request->input("city_id");

            $section = Guide_Sub_Section::where('id',$id)->first();

        # check section exist
        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $sections = Guide_Section::get();

        $cities   =   City::get();
        $countries=   Country::get();
        if($country_id){
            $cities   =   City::where('country_id',$country_id)->get();
        }
        
        $sect = Guide_Section::with('SubSections')->where('id',$id)->first();

        $list = [];

        foreach ($sections as $key => $sec)
        {
            $list['sectors'][$key]['id']          = $sec->id;
            $list['sectors'][$key]['name']        = $sec->name;
            $list['sectors'][$key]['type']        = $sec->type;
            if($sec->id == $id){
                $list['sectors'][$key]['selected']        = 1;
            }else{
                $list['sectors'][$key]['selected']        = 0;
            }

        }

        foreach ($sect->SubSections as $key => $sen)
        {
            $list['sub_sections'][$key]['id']          = $sen->id;
            $list['sub_sections'][$key]['name']        = $sen->name;
//            if($sen->id == $section->id){
//                $list['sub_sections'][$key]['selected']        = 1;
//            }else{
//                $list['sub_sections'][$key]['selected']        = 0;
//            }

        }

        $list['countries'][0]['id'] = 0;
        $list['countries'][0]['name'] = 'الكل';
        $list['cities'][0]['id'] = 0;
        $list['cities'][0]['name'] = 'الكل';
        $list['countries'][0]['selected'] = $country_id ? 0 :1;

        if($request->hasHeader('device')){
            $list['cities'][0]['selected'] = $city_id ? 0 :1;
        }
        foreach ($countries as $key => $country)
        {
            $list['countries'][$key+1]['id']          = $country->id;
            $list['countries'][$key+1]['name']        = $country->name;
            if($country_id == $country->id){
                $list['countries'][$key+1]['selected']        = 1;
            }else{
                $list['countries'][$key+1]['selected']        = 0;
            }
        }



        foreach ($cities as $key => $city)
        {
            $list['cities'][$key+1]['id']          = $city->id;
            $list['cities'][$key+1]['name']        = $city->name;
            if($request->hasHeader('device')){
                if($city_id == $city->id){
                    $list['cities'][$key+1]['selected']        = 1;
                }else{
                    $list['cities'][$key+1]['selected']        = 0;
                }
            }

        }

        $list['sort'] = [
            [
                'id'=>1,
                'name'=>'الابجدي',
                'value'=>0
            ],[
                'id'=>2,
                'name'=>'الاكثر تقيما',
                'value'=>1
            ],
        ];

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # filter companies
    public function FilterSubs(Request $request)
    {

        $id = $request->input("id");

        if(!$id)
        {
            $section = Guide_Section::where('selected','1')->first();
        }else{
            $section = Guide_Section::where('id',$id)->first();
        }
        # check section exist
        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $subs = Guide_Sub_Section::with('Section','Company')->where('section_id',$id)->orderby('name')->get();

        $list = [];

        foreach ($subs as $key => $sub)
        {
            $list[$key]['id']          = $sub->id;
            $list[$key]['name']        = $sub->name;


        }
    

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # filter companies
    public function Filtercount(Request $request)
    {

        $id = $request->input("id");

        $cont = Country::where('id',$id)->first();
            
        # check section exist
        if(!$cont)
        {
            $msg = 'Country not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $subs = City::where('country_id',$id)->orderby('name')->get();

        $list = [];

        foreach ($subs as $key => $sub)
        {
            $list[$key]['id']          = $sub->id;
            $list[$key]['name']        = $sub->name;


        }
    

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show company
    public function ShowCompany(Request $request)
    {

        $id = $request->input("id");
        $company = Company::find($id);
        # check section exist
        if(!$company)
        {
            return $this->ErrorMsgWithStatus('company not found');
        }


        /////////all info for premium user with for free or paied company or /////////////////////all info for free user or guest and paied company free user or guest
        if((Auth::guard('customer-api')->user() && Auth::guard('customer-api')->user()->memb == 1)|| $company->paied === 1)
        {
            $company = Company::with('Companyaddress','Companygallary.CompanyAlboumImages','CompanyAlboumImages','CompanySocialmedia.Social','Companyproduct','CompanyRates')->find($id);

            $phone     = $company->phones;
            $phones    = json_decode($phone);
            $email     = $company->emails;
            $emails    = json_decode($email);
            $mobile    = $company->mobiles;
            $mobiles   = json_decode($mobile);
            $fax    = $company->faxs;
            $faxs   = json_decode($fax);
            $cities = City::get();
            $stocks    = Fodder_Stock::where('company_id',$company->id)->with('subSection')->latest()->get()->unique('company_id');
            $transports = Company_transport::where('company_id',$company->id)->with('City')->where('city_id','1')->latest()->get();
            $list = [];
            $phon = [];
            $eme = [];
            $mob = [];
            $soc = [];
            $img = [];
            $prod = [];
            $local = [];
            $fod = [];
            $faxx = [];
            $trn = [];
            $city = [];
            $adr = [];


            $list['id']          = $company->id;
            $list['name']        = $company->name;
            $list['short_desc']  = $company->short_desc;
            $list['about']       = $company->about;
            $list['address']     = $company->address;
            $list['latitude']    = $company->latitude;
            $list['longitude']   = $company->longitude;
            $list['rate']        = $company->rate;
            $list['count_rate']  = count($company->CompanyRates);
            $list['image']       = URL::to('uploads/company/images/'.$company->image);
            $list['created_at']  = Date::parse($company->created_at)->diffForHumans();


            if(count($phones) > 0 && $phones[0] !== null )
            {
                foreach ($phones as $key => $pho)
                {
                    $phon[$key]['phone']        = $pho;
                }
            }else{
                $phon = [];
            }

            if(count($emails) > 0 && $emails[0] !== null)
            {
                foreach ($emails as $k => $em)
                {
                    $eme[$k]['email']        = $em;
                }
            }else{
                $eme ;
            }

            if(count($mobiles) > 0 && $mobiles[0] !== null)
            {
                foreach ($mobiles as $ke => $mo)
                {
                    $mob[$ke]['mobile']        = $mo;
                }
            }else{
                $mob ;
            }

            if(count($faxs) > 0 && $faxs[0] !== null)
            {
                foreach ($faxs as $kx => $fa)
                {
                    $faxx[$kx]['fax']        = $fa;
                }
            }else{
                $faxx ;
            }


            $list['phones']         = $phon;
            $list['emails']         = $eme;
            $list['mobiles']        = $mob;
            $list['faxs']        = $faxx;


            // social
            foreach ($company->CompanySocialmedia as $kk => $social)
            {
                $soc[$kk]['social_id']        = $social->id;
                $soc[$kk]['social_link']      = $social->social_link;
                $soc[$kk]['social_name']      = $social->Social->social_name;
                $soc[$kk]['social_icon']      = URL::to($social->Social->social_icon);
            }

            $list['social']        = $soc;


            // addresses
            foreach ($company->Companyaddress as $kr => $value)
            {
                $adr[$kr]['address']   = $value->address;
                $adr[$kr]['latitude']  = $value->latitude;
                $adr[$kr]['longitude'] = $value->longitude;
            }

            $list['addresses']         = $adr;


            foreach ($company->CompanyAlboumImages as $K => $value)
            {
                $img[$K]['image'] = URL::to('uploads/company/alboum/'.$value->image);
                $img[$K]['name']      = $value->name;
                $img[$K]['id']        = $value->id;
            }

            $list['gallary']         = $img;

            // products
            foreach ($company->Companyproduct as $Kpd => $value)
            {
                if($request->hasHeader('android')){
                    $prod[$Kpd]['id']        = $value->id;
                }
                $prod[$Kpd]['image'] = URL::to('uploads/company/product/'.$value->image);
                $prod[$Kpd]['name']        = $value->name;
            }

            $list['products']         = $prod;

            // localstock

            $ids = array();
            foreach ($company->LocalStockMember as $Kll => $value)
            {
                if (! in_array($value->Section->id, $ids)) {
                    $local[$Kll]['image'] = URL::to('uploads/sections/sub/'.$value->Section->image);
                    $local[$Kll]['name']  = $value->Section->name;
                    $local[$Kll]['id']  = $value->Section->id;
                }
                $ids[] = $value->Section->id;
            }

            $list['localstock']         = $local;

            // fodderstock
            foreach ($stocks as $Kf => $value)
            {
                $fod[$Kf]['image'] = URL::to('uploads/sections/avatar/'.$value->subSection->image);
                $fod[$Kf]['name']  = $value->subSection->name;
                $fod[$Kf]['id']  = $value->subSection->id;

            }

            $list['fodderstock']         = $fod;

            // transports
            foreach ($transports as $kt => $value)
            {
                if($request->hasHeader('android')){
                    $trn[$kt]['id'] = $value->id;
                }
                $trn[$kt]['price'] = $value->price;
                $trn[$kt]['name']  = $value->product_name;
                if($value->product_type == "0"){
                    $trn[$kt]['type']  = 'تكلفة نقل الكتكوت';
                }else{
                    $trn[$kt]['type']  = 'تكلفة نقل العلف';
                }

                $trn[$kt]['city']  = $value->City->name;
            }

            $list['transports']         = $trn;


            // cities
//            foreach ($cities as $kc => $value)
//            {
//                $city[$kc]['id']    = $value->id;
//                $city[$kc]['name']  = $value->name;
//            }

            $list['cities']         = [];

        }
        /////////////////////some info for free user and free company
        else{

            $list = [];
            $list['id']          = $company->id;
            $list['name']        = $company->name;
            $list['rate']        = $company->rate;
            $list['count_rate']  = count($company->CompanyRates);
            $list['image']       = URL::to('uploads/company/images/'.$company->image);

        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }
    # show company
//    public function ShowCompany(Request $request)
//    {
//
//        $id = $request->input("id");
//
//        $company = Company::with('Companyaddress','Companygallary.CompanyAlboumImages','FodderStocks.Section','CompanyAlboumImages','CompanySocialmedia.Social','Companyproduct','LocalStockMember.Section','CompanyRates')->where('id',$id)->first();
//
//        # check section exist
//        if(!$company)
//        {
//            $msg = 'company not found';
//            return response()->json([
//                'status'   => '0',
//                'message'  => null,
//                'error'    => $msg,
//            ],400);
//        }
//
//
//
//        $phone     = $company->phones;
//        $phones    = json_decode($phone);
//        $email     = $company->emails;
//        $emails    = json_decode($email);
//        $mobile    = $company->mobiles;
//        $mobiles   = json_decode($mobile);
//        $fax    = $company->faxs;
//        $faxs   = json_decode($fax);
//        $cities = City::get();
//        $stocks    = Fodder_Stock::where('company_id',$company->id)->with('subSection')->latest()->get()->unique('company_id');
//        $transports = Company_transport::where('company_id',$company->id)->with('City')->where('city_id','1')->latest()->get();
//
//        $list = [];
//        $phon = [];
//        $eme = [];
//        $mob = [];
//        $soc = [];
//        $img = [];
//        $prod = [];
//        $local = [];
//        $fod = [];
//        $faxx = [];
//        $trn = [];
//        $city = [];
//        $adr = [];
//
//
//        $list['id']          = $company->id;
//        $list['name']        = $company->name;
//        $list['short_desc']  = $company->short_desc;
//        $list['about']       = $company->about;
//        $list['address']     = $company->address;
//        $list['latitude']    = $company->latitude;
//        $list['longitude']   = $company->longitude;
//        $list['rate']        = $company->rate;
//        $list['count_rate']  = count($company->CompanyRates);
//        $list['image']       = URL::to('uploads/company/images/'.$company->image);
//        $list['created_at']  = Date::parse($company->created_at)->diffForHumans();
//
//
//        if(count($phones) > 0 && $phones[0] !== null )
//        {
//            foreach ($phones as $key => $pho)
//            {
//                $phon[$key]['phone']        = $pho;
//            }
//        }else{
//            $phon = [];
//        }
//
//        if(count($emails) > 0 && $emails[0] !== null)
//        {
//            foreach ($emails as $k => $em)
//            {
//                $eme[$k]['email']        = $em;
//            }
//        }else{
//            $eme ;
//        }
//
//        if(count($mobiles) > 0 && $mobiles[0] !== null)
//        {
//            foreach ($mobiles as $ke => $mo)
//            {
//                $mob[$ke]['mobile']        = $mo;
//            }
//        }else{
//            $mob ;
//        }
//
//        if(count($faxs) > 0 && $faxs[0] !== null)
//        {
//            foreach ($faxs as $kx => $fa)
//            {
//                $faxx[$kx]['fax']        = $fa;
//            }
//        }else{
//            $faxx ;
//        }
//
//
//        $list['phones']         = $phon;
//        $list['emails']         = $eme;
//        $list['mobiles']        = $mob;
//        $list['faxs']        = $faxx;
//
//
//        // social
//        foreach ($company->CompanySocialmedia as $kk => $social)
//        {
//            $soc[$kk]['social_id']        = $social->id;
//            $soc[$kk]['social_link']      = $social->social_link;
//            $soc[$kk]['social_name']      = $social->Social->social_name;
//            $soc[$kk]['social_icon']      = URL::to($social->Social->social_icon);
//        }
//
//        $list['social']        = $soc;
//
//
//        // addresses
//        foreach ($company->Companyaddress as $kr => $value)
//        {
//            $adr[$kr]['address']   = $value->address;
//            $adr[$kr]['latitude']  = $value->latitude;
//            $adr[$kr]['longitude'] = $value->longitude;
//        }
//
//        $list['addresses']         = $adr;
//
//
//
//        if(!is_null($request->header('Authorization')))
//        {
//            $token = $request->header('Authorization');
//            $token = explode(' ',$token);
//            if(count( $token) == 2)
//            {
//
//                $customer = Customer::where('api_token',$token[1])->first();
//
//                    if($customer->memb == '1')
//                    {
//
//
//
//
//                        // gallary
//                        foreach ($company->Companygallary as $K => $value)
//                        {
//                            $img[$K]['image'] = URL::to('uploads/gallary/avatar/'.$value->image);
//                            $img[$k]['name']      = $value->name;
//                            $img[$k]['id']        = $value->id;
//                        }
//
//                        $list['gallary']         = $img;
//
//                        // products
//                        foreach ($company->Companyproduct as $Kpd => $value)
//                        {
//                            $prod[$Kpd]['image'] = URL::to('uploads/company/product/'.$value->image);
//                            $prod[$Kpd]['name']        = $value->name;
//                        }
//
//                        $list['products']         = $prod;
//
//                        // localstock
//                        foreach ($company->LocalStockMember as $Kll => $value)
//                        {
//                            $local[$Kll]['image'] = URL::to('uploads/sections/sub/'.$value->Section->image);
//                            $local[$Kll]['name']  = $value->Section->name;
//                            $local[$Kll]['id']  = $value->Section->id;
//                        }
//
//                        $list['localstock']         = $local;
//
//                        // fodderstock
//                        foreach ($stocks as $Kf => $value)
//                        {
//                            $fod[$Kf]['image'] = URL::to('uploads/sections/avatar/'.$value->subSection->image);
//                            $fod[$Kf]['name']  = $value->subSection->name;
//                            $fod[$Kf]['id']  = $value->subSection->id;
//
//                        }
//
//                        $list['fodderstock']         = $fod;
//
//                        // transports
//                        foreach ($transports as $kt => $value)
//                        {
//                            $trn[$kt]['price'] = $value->price;
//                            $trn[$kt]['name']  = $value->product_name;
//                            if($value->product_type == "0"){
//                                $trn[$kt]['type']  = 'تكلفة نقل الكتكوت';
//                            }else{
//                                $trn[$kt]['type']  = 'تكلفة نقل العلف';
//                            }
//
//                            $trn[$kt]['city']  = $value->City->name;
//                        }
//
//                        $list['transports']         = $trn;
//
//
//                        // cities
//                        foreach ($cities as $kc => $value)
//                        {
//                            $city[$kc]['id']    = $value->id;
//                            $city[$kc]['name']  = $value->name;
//                        }
//
//                        $list['cities']         = $city;
//
//                    }else{
//                        if($company->paied === 1){
//
//
//
//                            // gallary
//                            foreach ($company->Companygallary as $K => $value)
//                            {
//                                $img[$K]['image'] = URL::to('uploads/gallary/avatar/'.$value->image);
//                                $img[$k]['name']      = $value->name;
//                                $img[$k]['id']        = $value->id;
//                            }
//
//                            $list['gallary']         = $img;
//
//                            // products
//                            foreach ($company->Companyproduct as $Kpd => $value)
//                            {
//                                $prod[$Kpd]['image'] = URL::to('uploads/company/product/'.$value->image);
//                                $prod[$Kpd]['name']        = $value->name;
//                            }
//
//                            $list['products']         = $prod;
//
//                            // localstock
//                            foreach ($company->LocalStockMember as $Kll => $value)
//                            {
//                                $local[$Kll]['image'] = URL::to('uploads/sections/sub/'.$value->Section->image);
//                                $local[$Kll]['name']  = $value->Section->name;
//                                $local[$Kll]['id']  = $value->Section->id;
//                            }
//
//                            $list['localstock']         = $local;
//
//                            // fodderstock
//                            foreach ($stocks as $Kf => $value)
//                            {
//                                $fod[$Kf]['image'] = URL::to('uploads/sections/avatar/'.$value->subSection->image);
//                                $fod[$Kf]['name']  = $value->subSection->name;
//                                $fod[$Kf]['id']  = $value->subSection->id;
//
//                            }
//
//                            $list['fodderstock']         = $fod;
//
//                            // transports
//                            foreach ($transports as $kt => $value)
//                            {
//                                $trn[$kt]['price'] = $value->price;
//                                $trn[$kt]['name']  = $value->product_name;
//                                if($value->product_type == "0"){
//                                    $trn[$kt]['type']  = 'تكلفة نقل الكتكوت';
//                                }else{
//                                    $trn[$kt]['type']  = 'تكلفة نقل العلف';
//                                }
//
//                                $trn[$kt]['city']  = $value->City->name;
//                            }
//
//                            $list['transports']         = $trn;
//
//
//                            // cities
//                            foreach ($cities as $kc => $value)
//                            {
//                                $city[$kc]['id']    = $value->id;
//                                $city[$kc]['name']  = $value->name;
//                            }
//
//                            $list['cities']         = $city;
//
//
//
//                        }
//                    }
//            }else{
//
//                if($company->paied === 1){
//
//
//
//                    // gallary
//                    foreach ($company->Companygallary as $K => $value)
//                    {
//                        $img[$K]['image'] = URL::to('uploads/gallary/avatar/'.$value->image);
//                        $img[$k]['name']      = $value->name;
//                        $img[$k]['id']        = $value->id;
//                    }
//
//                    $list['gallary']         = $img;
//
//                    // products
//                    foreach ($company->Companyproduct as $Kpd => $value)
//                    {
//                        $prod[$Kpd]['image'] = URL::to('uploads/company/product/'.$value->image);
//                        $prod[$Kpd]['name']        = $value->name;
//                    }
//
//                    $list['products']         = $prod;
//
//                    // localstock
//                    foreach ($company->LocalStockMember as $Kll => $value)
//                    {
//                        $local[$Kll]['image'] = URL::to('uploads/sections/sub/'.$value->Section->image);
//                        $local[$Kll]['name']  = $value->Section->name;
//                        $local[$Kll]['id']  = $value->Section->id;
//                    }
//
//                    $list['localstock']         = $local;
//
//                    // fodderstock
//                    foreach ($stocks as $Kf => $value)
//                    {
//                        $fod[$Kf]['image'] = URL::to('uploads/sections/avatar/'.$value->subSection->image);
//                        $fod[$Kf]['name']  = $value->subSection->name;
//                        $fod[$Kf]['id']  = $value->subSection->id;
//
//                    }
//
//                    $list['fodderstock']         = $fod;
//
//                    // transports
//                    foreach ($transports as $kt => $value)
//                    {
//                        $trn[$kt]['price'] = $value->price;
//                        $trn[$kt]['name']  = $value->product_name;
//                        if($value->product_type == "0"){
//                            $trn[$kt]['type']  = 'تكلفة نقل الكتكوت';
//                        }else{
//                            $trn[$kt]['type']  = 'تكلفة نقل العلف';
//                        }
//
//                        $trn[$kt]['city']  = $value->City->name;
//                    }
//
//                    $list['transports']         = $trn;
//
//
//                    // cities
//                    foreach ($cities as $kc => $value)
//                    {
//                        $city[$kc]['id']    = $value->id;
//                        $city[$kc]['name']  = $value->name;
//                    }
//
//                    $list['cities']         = $city;
//
//
//
//                }
//            }
//        }else{
//
//            if($company->paied === 1){
//
//
//
//                // gallary
//                foreach ($company->Companygallary as $K => $value)
//                {
//                    $img[$K]['image'] = URL::to('uploads/gallary/avatar/'.$value->image);
//                    $img[$k]['name']      = $value->name;
//                    $img[$k]['id']        = $value->id;
//                }
//
//                $list['gallary']         = $img;
//
//                // products
//                foreach ($company->Companyproduct as $Kpd => $value)
//                {
//                    $prod[$Kpd]['image'] = URL::to('uploads/company/product/'.$value->image);
//                    $prod[$Kpd]['name']        = $value->name;
//                }
//
//                $list['products']         = $prod;
//
//                // localstock
//                foreach ($company->LocalStockMember as $Kll => $value)
//                {
//                    $local[$Kll]['image'] = URL::to('uploads/sections/sub/'.$value->Section->image);
//                    $local[$Kll]['name']  = $value->Section->name;
//                    $local[$Kll]['id']  = $value->Section->id;
//                }
//
//                $list['localstock']         = $local;
//
//                // fodderstock
//                foreach ($stocks as $Kf => $value)
//                {
//                    $fod[$Kf]['image'] = URL::to('uploads/sections/avatar/'.$value->subSection->image);
//                    $fod[$Kf]['name']  = $value->subSection->name;
//                    $fod[$Kf]['id']  = $value->subSection->id;
//
//                }
//
//                $list['fodderstock']         = $fod;
//
//                // transports
//                foreach ($transports as $kt => $value)
//                {
//                    $trn[$kt]['price'] = $value->price;
//                    $trn[$kt]['name']  = $value->product_name;
//                    if($value->product_type == "0"){
//                        $trn[$kt]['type']  = 'تكلفة نقل الكتكوت';
//                    }else{
//                        $trn[$kt]['type']  = 'تكلفة نقل العلف';
//                    }
//
//                    $trn[$kt]['city']  = $value->City->name;
//                }
//
//                $list['transports']         = $trn;
//
//
//                // cities
//                foreach ($cities as $kc => $value)
//                {
//                    $city[$kc]['id']    = $value->id;
//                    $city[$kc]['name']  = $value->name;
//                }
//
//                $list['cities']         = $city;
//
//
//
//            }
//        }
//
//
//
//
//
//        return response()->json([
//            'message'  => null,
//            'error'    => null,
//            'data'     => $list
//        ],200);
//
//    }


    # transports companies
    public function transportsCompany(Request $request)
    {

        $id = $request->input("id");
        $city_id = $request->input("city_id");
        $type = $request->input("type");
        $transports = Company_transport::with('City')->where('company_id',$id)->where('city_id',$city_id)->where('product_type',$type)->latest()->get();
        $list = [];

        foreach ($transports as $key => $value)
        {
            $list[$key]['price'] = $value->price;
            $list[$key]['name']  = $value->product_name;
            if($value->product_type == "0"){
                $list[$key]['type']  = 'تكلفة نقل الكتكوت';
            }else{
                $list[$key]['type']  = 'تكلفة نقل العلف';
            }
            
            $list[$key]['city']  = $value->City->name;

        }
    

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    # gallary companies
    public function gallaryCompany(Request $request)
    {

        $id = $request->input("id");
        $gallary = Company_gallary::with('CompanyAlboumImages')->where('id',$id)->latest()->first();
        $list = [];

        # check gallary exist
        if(!$gallary)
        {
            $msg = 'gallary not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        foreach ($gallary->CompanyAlboumImages as $key => $value)
        {
   
            $list[$key]['image']     = URL::to('uploads/company/alboum/'.$value->image);
            $list[$key]['id']        = $value->id;

        }
    

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # add rating
    public function rating(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'company_id'      => 'required',
            'reat'            => 'required',
        ]);
        
        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['company_id']))
            {
                $msg = 'company_id is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['reat']))
            {
                $msg = 'reat is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        $company = Company::where('id',$request->company_id)->first();

        # check company exist
        if(!$company)
        {
            $msg = 'company not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $rating = new Company_Rate;
        $rating->rate       = $request->reat;
        $rating->company_id       = $request->company_id;
        $rating->user_id       = session('customer')->id;
        $rating->save();

      
        $company->rate =  Company_Rate::where('company_id' , $request->company_id)->avg('rate');
        $company->save();

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $rating
        ],200);
    }

    # add rating
    public function updaterating(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'company_id'      => 'required',
            'reat'            => 'required',
        ]);
        
        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['company_id']))
            {
                $msg = 'company_id is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['reat']))
            {
                $msg = 'reat is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        $company = Company::where('id',$request->company_id)->first();

        # check company exist
        if(!$company)
        {
            $msg = 'company not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $rating = Company_Rate::where('user_id',session('customer')->id)->where('company_id',$request->company_id)->first();
        $rating->rate       = $request->reat;
        $rating->save();

        $company->rate =  Company_Rate::where('company_id' , $request->company_id)->avg('rate');
        $company->save();

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $rating
        ],200);
    }

   
}
