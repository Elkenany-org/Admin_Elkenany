<?php

namespace Modules\Guide\Http\Controllers;

use App\Traits\SearchReg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Guide\Entities\Guide_Section;
use Modules\Guide\Entities\Guide_Sub_Section;
use Modules\Guide\Entities\Company;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use Modules\LocalStock\Entities\Local_Stock_Sub;
use Modules\Magazines\Entities\Magazine;
use Modules\News\Entities\News;
use Modules\Shows\Entities\Show;
use Modules\Store\Entities\Store_Ads;
use Modules\Consultants\Entities\Major;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\Guide\Entities\Company_Alboum_Images;
use Modules\Guide\Entities\Company_Social_media;
use Modules\Guide\Entities\Company_product;
use Modules\Guide\Entities\Company_Rate;
use Modules\Guide\Entities\Companies_Sec;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Modules\Cities\Entities\City;
use Modules\Countries\Entities\Country;
use Modules\Guide\Entities\Company_gallary;
use Modules\Guide\Entities\Company_transport;
use Modules\FodderStock\Entities\Fodder_Stock;
use Modules\SystemAds\Entities\Ads_User;
use Modules\SystemAds\Entities\Membership;
use Modules\SystemAds\Entities\Ads_Company;
use Modules\Store\Entities\Customer;
use Illuminate\Support\Facades\Input;
use Modules\Consultants\Entities\Doctor;
use FCM as FCM;
use LaravelFCM\Message\Topics;
use App\Configuration;
use Carbon\Carbon;
use App\Images_Home;
use App\Social;
use Session;
use File;
use Auth;
use App\Setting;

class GuideFrontController extends Controller
{
    use SearchReg;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $database = Configuration::first();

        $fcm_server_key = $database->fcm_server_key;

        $gallary = Images_Home::inRandomOrder()->get();
        $page = System_Ads_Pages::with('SystemAds')->where('type','home')->pluck('ads_id')->toArray();

        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $today = Carbon::now(); 

        $adsall = System_Ads::where('end_date', '<', $today->format('Y-m-d'))->where('status','1')->get();
        foreach($adsall as $val){
            $val->status = '3';
            $val->save();
        }

        $adsnots = System_Ads::where('end_date', '=', $today->format('Y-m-d'))->where('type','notification')->where('not_time', '=', date("H:i", strtotime($today)))->where('status','1')->where('app','web')->get();
        foreach($adsnots as $key =>$vall){
           
            # send notification fcm
            $title = trans($vall->title);
            $body  =  $vall->desc;
            $data  = ['foo'=>'bar'];
            $image = '';
            NotiForfcm($title,$body,$data,$image);

        }
     
     
    	return view('welcome',compact('gallary','logos','fcm_server_key'));
    }

    public function tok(Request $request)
    {
        $input = $request->all();
        $fcm_token = $input['token'];
        $Prov_id = $input['Prov_id'];
        $user = Customer::findOrFail($Prov_id);
        $user->web_fcm_token = $fcm_token;
        $user->update();
        $user->save();
        return response()->json([
            'sucess' => true,
            'message' => 'User Updated Successfully',
        ]);
    }

    public function ser(Request $request)
    {
        $keyword = $this->searchQuery($request->search);
        $guidesubs = Guide_Sub_Section::with('Section','Company')->where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $companies = Company::where('name' , 'REGEXP' , $keyword)->with('sections','SubSections')->take(50)->latest()->get();
        $localsubs = Local_Stock_Sub::where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $doctors = Doctor::with('DoctorServices','DoctorOrders')->where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $foddersubs = Stock_Fodder_Sub::where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $magazines = Magazine::with('sections')->where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $news = News::where('title' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $showes = Show::with('Section','City')->where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $stores = Store_Ads::with('StoreAdsimages')->where('title' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $search = $request->search;
        return view('guide::search',compact('companies','guidesubs','search','localsubs','foddersubs','magazines' ,'stores','doctors','news','showes'));
    }

    # sub sections
    public function SubSections($name)
    {
 
        
        $section = Guide_Section::withCount('SubSections')->where('type',$name)->first();
        $sections = Guide_Sub_Section::withCount('Company')->where('section_id',$section->id)->orderby('sort')->get();
        $secs = Guide_Section::get();
        $sort= null;

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','guide')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('main','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::where('main','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
        return view('guide::fronts.sections',compact('section','sections','secs','sort','adss','logos'));
    }

    # sub sections
    public function SubSectionsname($name)
    {

        
        $section = Guide_Section::with('SubSections')->where('type',$name)->first();
        $sections = Guide_Sub_Section::with('Section','Company')->where('section_id',$section->id)->orderby('name')->get();
        $secs = Guide_Section::get();
        $sort= '0';

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','guide')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('main','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::where('main','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        return view('guide::fronts.sections',compact('section','sections','secs','sort','adss','logos'));
    }

    # sub sections sort
    public function SubSectionssort($name)
    {

        $section = Guide_Section::with('SubSections')->where('type',$name)->first();
        $sections = Guide_Sub_Section::with('Section','Company')->where('section_id',$section->id)->orderBy('view_count' , 'desc')->get();
        $secs = Guide_Section::get();
        $sort= '1';

        
        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','guide')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('main','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::where('main','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        return view('guide::fronts.sections',compact('section','sections','secs','sort','adss','logos'));
    }

     
    # sub sections
    public function companies(Request $request, $id)
    {

        $country  = Input::get("country");
        $city  = Input::get("city");

        if(is_null($country) && is_null($city))
        {

            
            $section = Guide_Sub_Section::with('Company')->where('id',$id)->first();
            $parint = Guide_Section::with('SubSections')->where('id',$section->section_id)->first();
            $section->view_count = $section->view_count + 1;
            $section->save();

            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$section->id)->where('type','guide')->pluck('ads_id')->toArray();

            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

            $compsort = Company::with(['Country','City','SubSections','sections']);

            $companies = Company::with(['Country','City','SubSections','sections']);
    
            $compsort = $compsort->whereHas('SubSections',function($q) use ($id) {
                $q->where('sub_section_id',$id);
            });

            $compsort = $compsort->whereIn('id',$ads)->inRandomOrder()->get();

            $cooo =  $compsort->pluck('id')->toArray();

            $companies = $companies->whereHas('SubSections',function($q) use ($id) {
                $q->where('sub_section_id',$id);
            });
            $companies = $companies->whereNotIn('id',$cooo)->orderby('sort')->paginate(10);
            $secs = Guide_Section::with('SubSections')->get();
            $gubs = Guide_Sub_Section::get();
            $Country= null;
            $city= null;
            $cities = City::with('Company')->get();
            $countries = Country::with('Company')->get();
        }elseif(!is_null($country)){
            $section = Guide_Sub_Section::with('Company')->where('id',$id)->first();
            $parint = Guide_Section::with('SubSections')->where('id',$section->section_id)->first();

            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$section->id)->where('type','guide')->pluck('ads_id')->toArray();

            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

            $compsort = Company::with(['Country','City','SubSections','sections']);

            $companies = Company::with(['Country','City','SubSections','sections']);

            $compsort = $compsort->where('country_id',$country);
            $compsort = $compsort->whereHas('SubSections',function($q) use ($id) {
                $q->where('sub_section_id',$id);
            });

    
            $compsort = $compsort->whereIn('id',$ads)->inRandomOrder()->get();


            $cooo =  $compsort->pluck('id')->toArray();

            $companies = $companies->where('country_id',$country);
    
            $companies = $companies->whereHas('SubSections',function($q) use ($id) {
                $q->where('sub_section_id',$id);
            });
            $companies = $companies->whereNotIn('id',$cooo)->orderby('name')->paginate(10);
            $secs = Guide_Section::with('SubSections')->get();
            $countries = Country::with('Company')->get();
            $cities = City::where('country_id',$country)->with('Company')->get();
            $Country= $country;
            $city= null;
            $gubs = Guide_Sub_Section::get();
        
        }elseif(!is_null($city)){

            
            $section = Guide_Sub_Section::with('Company')->where('id',$id)->first();
            $parint = Guide_Section::with('SubSections')->where('id',$section->section_id)->first();
    
            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$section->id)->where('type','guide')->pluck('ads_id')->toArray();
    
            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
    
            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
    
            $cis = City::where('id' , $city)->first();
    
            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();
    
            $compsort = Company::with(['Country','City','SubSections','sections']);
    
            $companies = Company::with(['Country','City','SubSections','sections']);
    
            $compsort = $compsort->where('city_id',$city);
            $compsort = $compsort->whereHas('SubSections',function($q) use ($id) {
                $q->where('sub_section_id',$id);
            });
     
            $compsort = $compsort->whereIn('id',$ads)->inRandomOrder()->get();
    
    
            $cooo =  $compsort->pluck('id')->toArray();

    
            $companies = $companies->where('city_id',$city);
     
            $companies = $companies->whereHas('SubSections',function($q) use ($id) {
                $q->where('sub_section_id',$id);
            });
            $companies = $companies->whereNotIn('id',$cooo)->orderby('name')->paginate(10);
    
            $secs = Guide_Section::with('SubSections')->get();
            $cities = City::with('Company')->where('country_id' , $cis->country_id)->get();
            $countries = Country::with('Company')->get();
            $city= $city;
            $Country= $cis->country_id;
            $gubs = Guide_Sub_Section::get();
        
        }




        return view('guide::fronts.companies',compact('companies','section','secs','city','parint','Country','cities','countries','ads','adss','logos','compsort','gubs'));
    }

    # sub sections sort name
    public function sortcompaniesname(Request $request, $id)
    {

        $section = Guide_Sub_Section::with('Company')->where('id',$id)->first();
        $parint = Guide_Section::with('SubSections')->where('id',$section->section_id)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$section->id)->where('type','guide')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::with(['Country','City','SubSections','sections']);

        $companies = Company::with(['Country','City','SubSections','sections']);
 
        $compsort = $compsort->whereHas('SubSections',function($q) use ($id) {
            $q->where('sub_section_id',$id);
        });

        $compsort = $compsort->whereIn('id',$ads)->inRandomOrder()->get();

        $cooo =  $compsort->pluck('id')->toArray();

        $companies = $companies->whereHas('SubSections',function($q) use ($id) {
            $q->where('sub_section_id',$id);
        });
        $companies = $companies->whereNotIn('id',$cooo)->orderby('name')->paginate(10);
        $secs = Guide_Section::with('SubSections')->get();
        $cities = City::with('Company')->get();
        $countries = Country::with('Company')->get();
        $sort= '2';
        $gubs = Guide_Sub_Section::get();
       
        return view('guide::fronts.companies',compact('companies','gubs','section','secs','sort','parint','cities','countries','ads','adss','logos','compsort'));
    }


    # sub sections sort rate
    public function sortcompaniesrate(Request $request, $id)
    {

        $section = Guide_Sub_Section::with('Company')->where('id',$id)->first();
        $parint = Guide_Section::with('SubSections')->where('id',$section->section_id)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$section->id)->where('type','guide')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::with(['Country','City','SubSections','sections']);

        $companies = Company::with(['Country','City','SubSections','sections']);
 
        $compsort = $compsort->whereHas('SubSections',function($q) use ($id) {
            $q->where('sub_section_id',$id);
        });

        $compsort = $compsort->whereIn('id',$ads)->inRandomOrder()->get();

        $cooo =  $compsort->pluck('id')->toArray();

        $companies = $companies->whereHas('SubSections',function($q) use ($id) {
            $q->where('sub_section_id',$id);
        });
        $companies = $companies->whereNotIn('id',$cooo)->orderby('rate' , 'desc')->paginate(10);
        $secs = Guide_Section::with('SubSections')->get();
        $cities = City::with('Company')->get();
        $countries = Country::with('Company')->get();
        $sort= '1';
        $gubs = Guide_Sub_Section::get();
       
        return view('guide::fronts.companies',compact('companies','gubs','section','secs','sort','parint','cities','countries','ads','adss','logos','compsort'));
    }

    # sub sections sort city
    public function sortcompaniescity(Request $request, $id)
    {

        $section = Guide_Sub_Section::with('Company')->where('id','1')->first();
        $parint = Guide_Section::with('SubSections')->where('id','1')->first();

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$section->id)->where('type','guide')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();



        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::with(['Country','City','SubSections','sections']);

        $companies = Company::with(['Country','City','SubSections','sections']);

        $compsort = $compsort->where('city_id',$id);
 
        $compsort = $compsort->whereIn('id',$ads)->inRandomOrder()->get();


        $cooo =  $compsort->pluck('id')->toArray();

        $companies = $companies->where('city_id',$id);
 

        $companies = $companies->whereNotIn('id',$cooo)->orderby('name')->paginate(10);

        $secs = Guide_Section::with('SubSections')->get();
        $cities = City::with('Company')->get();
        $countries = Country::with('Company')->get();
        $city= $id;
        $gubs = Guide_Sub_Section::get();
     
        return view('guide::fronts.companies',compact('companies','gubs','section','secs','city','parint','cities','countries','ads','adss','logos','compsort'));
    }


    # sub sections sort Country
    public function sortcompaniescountry(Request $request, $id)
    {

        $section = Guide_Sub_Section::with('Company')->where('id','1')->first();
        $parint = Guide_Section::with('SubSections')->where('id','1')->first();

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$section->id)->where('type','guide')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::with(['Country','City','SubSections','sections']);

        $companies = Company::with(['Country','City','SubSections','sections']);

        $compsort = $compsort->where('country_id',$id);
 
        $compsort = $compsort->whereIn('id',$ads)->inRandomOrder()->get();


        $cooo =  $compsort->pluck('id')->toArray();

        $companies = $companies->where('country_id',$id);
 

        $companies = $companies->whereNotIn('id',$cooo)->orderby('name')->paginate(10);
        $secs = Guide_Section::with('SubSections')->get();
        $countries = Country::with('Company')->get();
        $cities = City::where('country_id',$id)->with('Company')->get();
        $Country= $id;
        $gubs = Guide_Sub_Section::get();
    
        return view('guide::fronts.companies',compact('companies','section','gubs','secs','Country','parint','countries','cities','ads','adss','logos','compsort'));
    }


    # company
    public function company($id)
    {
        $companies = Company::with('Companyaddress','Companygallary.CompanyAlboumImages','FodderStocks.Section','CompanyAlboumImages','CompanySocialmedia.Social','Companyproduct','LocalStockMember.Section','CompanyRates')->where('id',$id)->first();
        $phone     = $companies->phones;
        $phones    = json_decode($phone);
        $email     = $companies->emails;
        $emails    = json_decode($email);
        $mobile    = $companies->mobiles;
        $mobiles   = json_decode($mobile);
        $fax    = $companies->faxs;
        $faxs   = json_decode($fax);
        $stocks    = Fodder_Stock::where('company_id',$companies->id)->with('subSection')->latest()->get()->unique('company_id');
        $social    = Social::with('CompanySocialmedia')->latest()->get();
        $cities = City::get();
        $transports = Company_transport::where('company_id',$id)->where('city_id','1')->latest()->get();

        $address = [];
        foreach ($companies->Companyaddress as $key => $value){
            if($value->type != "")
                $address[$value->type][] = $value;
            krsort($address);
        }
        $rating = '';
        if(Auth::guard('customer')->user())
        {
            $rating = Company_Rate::with('Company')->where('user_id',Auth::guard('customer')->user()->id)->where('company_id',$companies->id)->first();
//            return view('guide::fronts.company',compact('address','companies','stocks','faxs','phones','transports','emails','cities','mobiles','social','rating'));
        }
        return view('guide::fronts.company',compact('address','companies','stocks','faxs','phones','transports','emails','cities','mobiles','social','rating'));
    }

    # get transrorts ajax
    public function Gettransports(Request $request)
    {
        $datas = Company_transport::where('company_id',$request->company_id)->where('city_id',$request->city_id)->where('product_type','0')->latest()->get();
        return $datas;
    }

    # get transrorts fooder ajax
    public function Gettransportsfooder(Request $request)
    {
        $datas = Company_transport::where('company_id',$request->company_id)->where('city_id',$request->city_id)->where('product_type','1')->latest()->get();
        return $datas;
    }

    # get sections ajax
    public function Getsections(Request $request)
    {
        $datas = Guide_Sub_Section::withCount('Company')->where('section_id',$request->section_id)->orderBy('sort')->get();
        $seco = Guide_Section::where('id',$request->section_id)->first();

        $subss = Guide_Sub_Section::where('section_id',$request->section_id)->pluck('id')->toArray();

        $page = System_Ads_Pages::with('SystemAds')->whereIn('sub_id',$subss)->where('type','guide')->pluck('ads_id')->toArray();

        $ads = System_Ads::with('SystemAdsPages')->where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $secs = Guide_Section::get();
        return response()->json(['datas' => $datas , 'secs' => $secs, 'seco' => $seco, 'ads' => $ads,], 200);
    }

    # get sections ajax
    public function Getsectionssort(Request $request)
    {

        if($request->sort == "0"){
            $datas = Guide_Sub_Section::with('Section')->withCount('Company')->where('section_id',$request->section_id)->orderby('name')->get();
        }else{
            $datas = Guide_Sub_Section::with('Section')->withCount('Company')->where('section_id',$request->section_id)->orderBy('view_count' , 'desc')->get();
        }
      
        $subss = Guide_Sub_Section::with('Section','Company')->where('section_id',$request->section_id)->pluck('id')->toArray();

        $page = System_Ads_Pages::with('SystemAds')->whereIn('sub_id',$subss)->where('type','guide')->pluck('ads_id')->toArray();

        $ads = System_Ads::with('SystemAdsPages')->where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $seco = Guide_Section::where('id',$request->section_id)->first();
        $secs = Guide_Section::get();
        return response()->json(['datas' => $datas , 'secs' => $secs, 'seco' => $seco, 'ads' => $ads,], 200);
    }

    # get sub sections ajax
    public function GetSubSections(Request $request)
    {
        $datas = Guide_Sub_Section::where('section_id',$request->section_id)->latest()->get();
        return $datas;
    }

    # get sub sections ajax
    public function GetSubSectionsserchname(Request $request)
    {
        $keyword = $this->searchQuery($request->search);
        $seco = Guide_Section::where('id',$request->section_id)->first();
        $datas = Guide_Sub_Section::with('Section')->withCount('Company')->where('section_id',$request->section_id)->where('name' , 'REGEXP' ,  $keyword )->take(50)->latest()->get();
        $subss = Guide_Sub_Section::with('Section')->withCount('Company')->where('section_id',$request->section_id)->pluck('id')->toArray();

        $page = System_Ads_Pages::with('SystemAds')->whereIn('sub_id',$subss)->where('type','guide')->pluck('ads_id')->toArray();

        $ads = System_Ads::with('SystemAdsPages')->where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        return response()->json(['datas' => $datas , 'seco' => $seco, 'ads' => $ads,], 200);
    }

    # get companies ajax
    public function Getcompanies(Request $request)
    {
        $majs = Companies_Sec::where('sub_section_id' , $request->id)->pluck('company_id')->toArray();
        $datas = Company::with('sections','SubSections')->whereIn('id',$majs)->get();

        return $datas;
    }

    # get companies ajax
    public function Getcompaniessearchname(Request $request)
    {
        $keyword = $this->searchQuery($request->search);
        $datas = Company::with('sections','SubSections')->where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();

        return $datas;
    }

    # get companies ajax
    public function Getrating(Request $request)
    {
        if($request->id)
        {   
            $majs = Companies_Sec::where('sub_section_id' , $request->id)->pluck('company_id')->toArray();
            $datas = Company::with('sections','SubSections')->whereIn('id',$majs)->orderby('rate' , 'desc')->get();

        }else{
            $datas = Company::with('sections','SubSections')->orderBy('rate' , 'desc')->get();
        }
        return $datas;
    }

    # add rating
    public function rating(Request $request)
    {

        $rating = Company_Rate::where('user_id',Auth::guard('customer')->user()->id)->where('company_id',$request->company_id)->first();
        if($rating){
            $rating->rate       = $request->reat;
            $rating->save();
        }else{
            $rating = new Company_Rate;
            $rating->rate       = $request->reat;
            $rating->company_id       = $request->company_id;
            $rating->user_id       = Auth::guard('customer')->user()->id;
            $rating->save();
        }

        $company = Company::findOrFail($request->company_id);
        $company->rate =  Company_Rate::where('company_id' , $request->company_id)->avg('rate');
        $company->save();

        return $rating;
    }

    # add rating
    public function updaterating(Request $request)
    {
  
        $rating = Company_Rate::with('Company')->where('user_id',Auth::guard('customer')->user()->id)->where('company_id',$request->company_id)->first();
        $rating->rate       = $request->reat;
        $rating->save();

        $company = Company::findOrFail($request->company_id);
        $company->rate =  Company_Rate::where('company_id' , $request->company_id)->avg('rate');
        $company->save();

        return $rating;
    }
    

    public function customerRate($company_id)
    {
        $company = Company_Rate::where('company_id',$company_id)->count();
        return $company;
    }


    public function getrateOfCompany($company_id)
    {
        $company = Company::where('id',$company_id)->first();
        $rate = $company ? $company->rate : '0';
        return response()->json($rate);
    }
}
 
 