<?php

namespace Modules\Magazines\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Traits\SearchReg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Magazines\Entities\Mag_Section;
use Modules\Magazines\Entities\Magazin_Alboum_Images;
use Modules\Magazines\Entities\Magazin_gallary;
use Modules\Magazines\Entities\Magazin_magazines;
use Modules\Magazines\Entities\Magazin_Social_media;
use Modules\Magazines\Entities\Magazine_address;
use Modules\Magazines\Entities\Magazine_Rate;
use Modules\Magazines\Entities\Magazine_Sec;
use Modules\Magazines\Entities\Magazine;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Modules\Countries\Entities\Country;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\Data_Analysis;
use Modules\Store\Entities\Customer;
use Modules\Cities\Entities\City;
use Illuminate\Support\Str;
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

class ApimagazineController extends Controller
{
    use SearchReg;
    # show magazines
    public function showMagazines(Request $request)
    {

        $type       = $request->input("type");
        $sort       = $request->input("sort");
        $search     = $request->input("search");
        $city_id    = $request->input("city_id");
        $country_id    = $request->input("country_id");
        $section_id      = $request->input("section_id");

        if(!$section_id)
        {
            $section = Mag_Section::with('Magazine')->where('selected','1')->first();
        }else{
            $section = Mag_Section::with('Magazine')->where('id',$section_id)->first();
        }
   
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

        # check recomndation system
        if(!is_null($request->header('Authorization')))
        {
            $token = $request->header('Authorization'); 
            $token = explode(' ',$token);
            if(count( $token) == 2)
            {

                $customer = Customer::where('api_token',$token[1])->first();
                if($customer)
                {

                    $main_keyword = Data_Analysis_Keywords::where('keyword','magazine')->where('type',$section->id)->first();
                    $main_keyword->use_count = $main_keyword->use_count + 1;
                    $main_keyword->update();

                    $user_data = User_Data_Analysis::where([['keyword_id',$main_keyword->id],['user_id',$customer->id]])->first();


                    if($user_data)
                    {
                        # increment user analysis count
                        $user_data->use_count = $user_data->use_count + 1; 
                        $user_data->update();
                    }else{
                        # create new record for user with keyword
                        $user_data = new User_Data_Analysis;
                        $user_data->user_id    = $customer->id;
                        $user_data->keyword_id = $main_keyword->id;
                        $user_data->use_count  = 1;
                        $user_data->save();
                    }

                    
                    # crate new record for data analysis
                    $data_analysis = new Data_Analysis;
                    $data_analysis->user_id    = $customer->id;
                    $data_analysis->keyword_id = $main_keyword->id;
                    $data_analysis->save();



                }
            }
        }

        $page = System_Ads_Pages::with('SystemAds')->where('type','magazines')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        
        $magazines = Magazine::with(['City','sections']);

        if(!is_null($city_id))
        {
            if($city_id == 0){
                $cities = City::where('country_id',$country_id)->get()->pluck('id');
                $magazines = $magazines->whereIn('city_id',$cities);
            }else{
                $magazines = $magazines->where('city_id',$city_id);
            }

        }

        if(!is_null($section))
        {
            $magazines = $magazines->whereHas('sections',function($q) use ($section) {
                $q->where('section_id',$section->id);
            });
        }
//
//
        if(!is_null($search))
        {
            $keyword = $this->searchQuery($search);
            $magazines = $magazines->where('name' , 'REGEXP' , $keyword); //where('name' , 'like' , "%". $search ."%");
        }

        if(!is_null($sort))
        {
            if($sort == 0)
            {
                if($request->hasHeader('android')){
                    $magazines = $magazines->orderby('name')->get();

                }else{
                    $magazines = $magazines->orderby('name')->paginate(10);
                }
            }else{
                if($request->hasHeader('android')){
                    $magazines = $magazines->orderBy('rate' , 'desc')->get();

                }else{
                    $magazines = $magazines->orderBy('rate' , 'desc')->paginate(10);
                }
            }
        }else{
            if($request->hasHeader('android')){
                $magazines = $magazines->get();

            }else{
                $magazines = $magazines->paginate(10);
            }
        }


    

        $list = [];

        $sections = Mag_Section::get();

        foreach ($sections as $key => $sec)
        {
            $list['sectors'][$key]['id']          = $sec->id;
            $list['sectors'][$key]['name']        = $sec->name;
            $list['sectors'][$key]['type']        = $sec->type;
            if($sec->id == $section->id){
                $list['sectors'][$key]['selected']        = 1;
            }else{
                $list['sectors'][$key]['selected']        = 0;
            }
    


        }

        $list['banners'] = count($adss) == 0 ? [] : LogoBannerResource::collection($adss);
        $list['logos'] = count($logos) == 0 ? [] : LogoBannerResource::collection($logos);


        if(count($magazines) < 1)
        {
            $list['data'] = [];

            if(! $request->hasHeader('android')){
                $list['current_page']                     = $magazines->toArray()['current_page'];
                $list['last_page']                        = $magazines->toArray()['last_page'];
            }

        }

        foreach ($magazines as $key => $company)
        {
            $list['data'][$key]['id']                = $company->id;
            $list['data'][$key]['name']              = $company->name;
            $list['data'][$key]['rate']              = $company->rate;
            $list['data'][$key]['image']             = URL::to('uploads/magazine/images/'.$company->image);
            $list['data'][$key]['desc']              = Str::limit($company->short_desc, 60, '...');
            $list['data'][$key]['address']           = $company->address;
            if(! $request->hasHeader('android')) {
                $list['current_page'] = $magazines->toArray()['current_page'];
                $list['last_page'] = $magazines->toArray()['last_page'];
                $list['first_page_url'] = $magazines->toArray()['first_page_url'];
                $list['next_page_url'] = $magazines->toArray()['next_page_url'];
                $list['last_page_url'] = $magazines->toArray()['last_page_url'];
            }
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

        
        $type = $request->input("type");
        $country_id = $request->input("country_id");
        $section_id = $request->input("section_id");

        if(!$section_id)
        {
            $section = Mag_Section::with('Magazine')->where('selected','1')->first();
        }else{
            $section = Mag_Section::with('Magazine')->where('id',$section_id)->first();
        }
            
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

        $sections = Mag_Section::get();

        $countries = Country::get();

        $cities   =   City::get();
        if($country_id){
            $cities   =   City::where('country_id',$country_id)->get();
        }


        $list = [];

        foreach ($sections as $key => $sec)
        {
            $list['sectors'][$key]['id']          = $sec->id;
            $list['sectors'][$key]['name']        = $sec->name;
            $list['sectors'][$key]['type']        = $sec->type;
            if($sec->id == $section->id){
                $list['sectors'][$key]['selected']        = 1;
            }else{
                $list['sectors'][$key]['selected']        = 0;
            }

        }
        foreach ($countries as $key => $country)
        {
            $list['countries'][$key]['id']          = $country->id;
            $list['countries'][$key]['name']        = $country->name;
            if($country->id == $country_id){
                $list['countries'][$key]['selected']        = 1;
            }else{
                $list['countries'][$key]['selected']        = 0;
            }
        }

        $list['cities'][0]['id'] = 0;
        $list['cities'][0]['name'] = 'الكل';
        foreach ($cities as $key => $city)
        {
            $list['cities'][$key+1]['id']          = $city->id;
            $list['cities'][$key+1]['name']        = $city->name;

        }


        $list['sort'] = [
            [
                'id'=>1,
                'name'=>'الابجدي',
                'value'=>0,
            ],[
                'id'=>2,
                'name'=>'الاكثر تقيما',
                'value'=>1,
            ],
        ];

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    # show magazines
    public function ShowMagazine(Request $request)
    {

        $id = $request->input("id");

        $magazines = Magazine::with('MagazineAlboumImages','Magazingallary.MagazineAlboumImages','Magazineaddress','MagazinSocialmedia.Social','Magazinguide','MagazineRate')->where('id',$id)->first();
      
        # check section exist
        if(!$magazines)
        {
            $msg = 'magazines not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $phone     = $magazines->phones;
        $phones    = json_decode($phone);
        $email     = $magazines->emails;
        $emails    = json_decode($email);
        $mobile    = $magazines->mobiles;
        $mobiles   = json_decode($mobile);
        $fax    = $magazines->faxs;
        $faxs   = json_decode($fax);

        $list = [];
        $phon = [];
        $eme = [];
        $mob = [];
        $soc = [];
        $img = [];
        $guid = [];
        $faxx = [];
        $adr = [];
        
        
        $list['id']          = $magazines->id;
        $list['name']        = $magazines->name;
        $list['short_desc']  = $magazines->short_desc;
        $list['about']       = $magazines->about;
        $list['address']     = $magazines->address;
        $list['latitude']    = $magazines->latitude;
        $list['longitude']   = $magazines->longitude;
        $list['rate']        = $magazines->rate;
        $list['count_rate']  = count($magazines->MagazineRate);
        $list['image']       = URL::to('uploads/magazine/images/'.$magazines->image);
        $list['created_at']  = Date::parse($magazines->created_at)->diffForHumans();

     
        if(count($phones) > 0 && $phones[0] != null)
        {
            foreach ($phones as $key => $pho)
            {
                $phon[$key]['phone']        = $pho;
            }
        }else{
            $phon;
        }

        if(count($emails) > 0 && $emails[0] != null)
        {
            foreach ($emails as $k => $em)
            {
                $eme[$k]['email']        = $em;
            }
        }else{
            $eme;
        }

        if(count($mobiles) > 0 && $mobiles[0] != null)
        {
            foreach ($mobiles as $ke => $mo)
            {
                $mob[$ke]['mobile']        = $mo;
            }
        }else{
            $mob;
        }

        if(count($faxx) > 0 && $faxx[0] != null)
        {
            foreach ($faxs as $kx => $fa)
            {
                $faxx[$kx]['fax']        = $fa;
            }
        }else{
            $faxx;
        }


        $list['phones']         = $phon;
        $list['emails']         = $eme;
        $list['mobiles']        = $mob;
        $list['faxs']        = $faxx;
        
        // social
        foreach ($magazines->MagazinSocialmedia as $kk => $social)
        {
            $soc[$kk]['social_id']        = $social->id;
            $soc[$kk]['social_link']      = $social->social_link;
            $soc[$kk]['social_name']      = $social->Social->social_name;
            $soc[$kk]['social_icon']      = URL::to($social->Social->social_icon);
        }

        $list['social']        = $soc;


        //addresses
        if($request->header('device')=="web"){
            foreach ($magazines->Magazineaddress as $kr => $value)
            {
                $adr[$kr]['address']   = $value->address;
                $adr[$kr]['latitude']  = $value->latitude;
                $adr[$kr]['longitude'] = $value->longitude;
            }

            $list['addresses']         = $adr;
        }
  

        if(!is_null($request->header('Authorization')))
        {
            $token = $request->header('Authorization'); 
            $token = explode(' ',$token);
            if(count( $token) == 2)
            {

                $customer = Customer::where('api_token',$token[1])->first();

                    if($customer->memb == '1')
                    {
                      
                         // gallary
                        foreach ($magazines->Magazingallary as $K => $value)
                        {
                            $img[$K]['image'] = URL::to('uploads/gallary/avatar/'.$value->image);
                            $img[$k]['name']      = $value->name;
                            $img[$k]['id']        = $value->id;
                        }

                        $list['gallary']         = $img;

                        // guide
                        foreach ($magazines->Magazinguide as $Kpd => $value)
                        {
                            $guid[$Kpd]['image'] = URL::to('uploads/magazine/guides/'.$value->image);
                            $guid[$Kpd]['name']        = $value->name;
                            $guid[$Kpd]['link']        = $value->link;
                        }

                        $list['guides']         = $guid;
     

                    }else{
                        if($magazines->paied === 1){
 
                             // gallary
                            foreach ($magazines->Magazingallary as $K => $value)
                            {
                                $img[$K]['image'] = URL::to('uploads/gallary/avatar/'.$value->image);
                                $img[$k]['name']      = $value->name;
                                $img[$k]['id']        = $value->id;
                            }

                            $list['gallary']         = $img;

                            // guide
                            foreach ($magazines->Magazinguide as $Kpd => $value)
                            {
                                $guid[$Kpd]['image'] = URL::to('uploads/magazine/guides/'.$value->image);
                                $guid[$Kpd]['name']        = $value->name;
                                $guid[$Kpd]['link']        = $value->link;
                            }

                            $list['guides']         = $guid;
                        }
                    }
            }else{

                if($magazines->paied === 1){
 
                // gallary
                foreach ($magazines->Magazingallary as $K => $value)
                {
                    $img[$K]['image'] = URL::to('uploads/gallary/avatar/'.$value->image);
                    $img[$k]['name']      = $value->name;
                    $img[$k]['id']        = $value->id;
                }

                $list['gallary']         = $img;

                // guide
                foreach ($magazines->Magazinguide as $Kpd => $value)
                {
                    $guid[$Kpd]['image'] = URL::to('uploads/magazine/guides/'.$value->image);
                    $guid[$Kpd]['name']        = $value->name;
                    $guid[$Kpd]['link']        = $value->link;
                }

                $list['guides']         = $guid;
                
                    
                }
            }
        }else{

            if($magazines->paied === 1){
 
                // gallary
                foreach ($magazines->Magazingallary as $K => $value)
                {
                    $img[$K]['image'] = URL::to('uploads/gallary/avatar/'.$value->image);
                    $img[$k]['name']      = $value->name;
                    $img[$k]['id']        = $value->id;
                }

                $list['gallary']         = $img;

                // guide
                foreach ($magazines->Magazinguide as $Kpd => $value)
                {
                    $guid[$Kpd]['image'] = URL::to('uploads/magazine/guides/'.$value->image);
                    $guid[$Kpd]['name']        = $value->name;
                    $guid[$Kpd]['link']        = $value->link;
                }

                $list['guides']         = $guid;
                
            }
        }


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
 
    }

    # gallary magazines
    public function gallarymagazines(Request $request)
    {

        $id = $request->input("id");
        $gallary = Magazin_gallary::with('MagazineAlboumImages')->where('id',$id)->latest()->first();
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

        foreach ($gallary->MagazineAlboumImages as $key => $value)
        {
   
            $list[$key]['image']     = URL::to('uploads/magazine/alboum/'.$value->image);
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

            'maga_id'      => 'required',
            'reat'            => 'required',
        ]);
        
        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['maga_id']))
            {
                $msg = 'maga_id is required';
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

        $magazine = Magazine::where('id',$request->maga_id)->first();

        # check magazine exist
        if(!$magazine)
        {
            $msg = 'magazine not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $rating = new Magazine_Rate;
        $rating->rate       = $request->reat;
        $rating->maga_id       = $request->maga_id;
        $rating->user_id       = session('customer')->id;
        $rating->save();

    
        $magazine->rate =  Magazine_Rate::where('maga_id' , $request->maga_id)->avg('rate');
        $magazine->save();

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

            'maga_id'      => 'required',
            'reat'            => 'required',
        ]);
        
        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['maga_id']))
            {
                $msg = 'maga_id is required';
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

        $magazine = Magazine::where('id',$request->maga_id)->first();

        # check magazine exist
        if(!$magazine)
        {
            $msg = 'magazine not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $rating = Magazine_Rate::where('user_id',session('customer')->id)->where('maga_id',$request->maga_id)->first();
        $rating->rate       = $request->reat;
        $rating->save();

        $magazine->rate =  Magazine_Rate::where('maga_id' , $request->maga_id)->avg('rate');
        $magazine->save();

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $rating
        ],200);
    }

   
}
