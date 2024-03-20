<?php

namespace Modules\Guide\Http\Controllers\api\v2;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Modules\Analysis\Entities\Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\Guide\Entities\Company;
use Modules\Guide\Entities\Guide_Sub_Section;
use Modules\Guide\Entities\Guide_Section;
use Modules\Cities\Entities\City;
use Modules\Countries\Entities\Country;
use Modules\Store\Entities\Customer;
use Modules\SystemAds\Entities\System_Ads;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Transformers\LogoBannerResource;
use Modules\Guide\Transformers\CompanyResource;
use Modules\Guide\Transformers\CompanySubsResource;

class CompanyController extends Controller
{
    use ApiResponse;

    public function filter_companies(Request $request)
    {

        $id = Input::get("sector_id");
        $country_id = Input::get("country_id");
        $city_id = Input::get("city_id");
        $sort = Input::get("sort");

        $guide_subsection = Guide_Sub_Section::where('id',$id)->first();

        # check section exist
        if(!$guide_subsection) { return $this->ErrorMsgWithStatus('section not found'); }

        $guide_section = Guide_Section::query();

        $sections = $guide_section->select('id','name','type')->get();
                    $sections->map(function ($section) use ($id) {
                        $section['selected'] = $section->id == $id ? 1 : 0;
                       return $section;
                    });


        $countries=   Country::select('id','name')->get();
        $countries->map(function($country) use($country_id) {
            $country['selected'] = $country->id == $country_id ? 1 : 0;
            return $country;
        });

        $cities   =   City::select('id','name');
        if($country_id){
            $cities->where('country_id',$country_id);
        }
        $cities   =   $cities->get();

        $cities->map(function($city) use($city_id) {
            $city['selected'] = $city->id == $city_id ? 1 : 0;
            return $city;
        });


        $section = $guide_section->with('SubSections')->where('id',$id)->first();

        $data = [];
        foreach ($section->SubSections as $key => $sen) {
            $data[] = [
                'id'=>$sen->id,
                'name'=>$sen->name,
            ];
        }

        $sort1 = 0;
        $sort2 = 0;
        if(isset($sort) &&  $sort == 0){
            $sort1 = 1;
        }elseif($sort == 1){
            $sort2 = 1;
        }
        $sorts = $this->SortRecentAlphaWithSelected($sort1,$sort2);
        return $this->returnData(['sectors'=>$sections,'sub_sections'=>$data ,'countries'=>$countries,'cities'=>$cities,'sort'=>$sorts]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        $type       = Input::get("type");
        $sort       = Input::get("sort");
        $search     = Input::get("search");

        # check section exist
        if(!$type) { return $this->ReturnMsg('you should send type of section');}

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

                    $main_keyword = Data_Analysis_Keywords::where('type',$type)->where('keyword','guide')->first();
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

        $section = Guide_Section::where('type',$type)->first();

        # check section exist
        if(!$section) {return $this->ReturnMsg('section type not correct');}

        $subs = Guide_Sub_Section::with('Company')->where('section_id',$section->id);

        if($search)
        {
            $keyword = $this->searchQuery($search);
            $subs = $subs->where('name' , 'REGEXP' , $keyword);
        }

        if($sort)
        {
            if($sort == 0)
            {
                $subs = $subs->orderby('name')->get();
            }else{
                $subs = $subs->orderBy('view_count' , 'desc')->get();
            }
        }else{
            $subs = $subs->get();
        }


        $list = [];
        $sections = Guide_Section::select('id','name','type')->get();
        $sections->map(function($sec) use($section){
            $selected = 0;
            if($section->id && $section->id == $sec->id){
                $this->section_name = $sec->name;
                $selected = 1;
            }
            $sec['selected'] = $selected;
            return $section;
        });
        $list['sector_name'] = $section->name;
        $list['count'] = 'قسم '.count($subs);
        $list['sectors'] = $sections;


        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','guide')->pluck('ads_id')->toArray();

        $ads = System_Ads::where('main','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::where('main','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $list['banners'] = LogoBannerResource::collection($ads);
        $list['logos'] = LogoBannerResource::collection($logos);

        $list['sub_sections'] = CompanySubsResource::collection($subs);


        return $this->ReturnData($list);
    }

    /**
     * @return mixed
     */
    public function show()
    {

        $sub_id     = Input::get("sub_id");
        $country_id = Input::get("country_id");
        $city_id    = Input::get("city_id");
        $section_id = Input::get("section_id");
        $sort       = Input::get("sort");
        $search     = Input::get("search");

        $section = Guide_Sub_Section::where('id',$sub_id)->first();

        # check section exist
        if(!$section) { return $this->ErrorMsgWithStatus('section not found'); }
        $section_id = $section->section_id;


        $section->view_count = $section->view_count + 1;
        $section->save();

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub_id)->where('section_type',$section->type)->where('type','guide')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::with(['Country','City','SubSections','sections']);

        $compsort = $compsort->whereHas('SubSections',function($q) use ($sub_id) {
            $q->where('sub_section_id',$sub_id);
        });

        $compsort = $compsort->whereIn('id',$ads)->inRandomOrder()->get();

        $cooo =  $compsort->pluck('id')->toArray();

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
            $companies = $companies->where('name' , 'REGEXP' , $keyword);
        }

        if($sort)
        {
            if($sort == 0)
            {
                $companies = $companies->whereNotIn('id',$cooo)->orderby('name')->paginate(10);
            }else{
                $companies = $companies->whereNotIn('id',$cooo)->orderBy('rate' , 'desc')->paginate(10);
            }
        }else{
            $companies = $companies->whereNotIn('id',$cooo)->paginate(10);
        }


        $list = [];

        $sections = Guide_Section::select('id','name','type')->get();
        $sections->map(function($section) use($section_id){
            $selected = 0;
            if($section_id && $section_id == $section->id){
                $this->section_name = $section->name;
                $selected = 1;
            }
            $section['selected'] = $selected;
            return $section;
        });

        $list['sectors'] = $sections;
        $list['sector_name'] = $section->name;
        $list['count'] = 'شركه '.$companies->total();



        $list['banners'] = LogoBannerResource::collection($adss);
        $list['logos'] = LogoBannerResource::collection($logos);
        $list['compsort'] = CompanyResource::collection($logos);



        if(count($companies) == 0)
        {
            $list['data'] = [];
            $list['current_page']                     = $companies->toArray()['current_page'];
            $list['last_page']                        = $companies->toArray()['last_page'];
        }

        foreach ($companies as $company)
        {
            $list['data'][] = new CompanyResource($company);
            $list['current_page']                    = $companies->toArray()['current_page'];
            $list['last_page']                       = $companies->toArray()['last_page'];
            $list['first_page_url']                  = $companies->toArray()['first_page_url'];
            $list['next_page_url']                   = $companies->toArray()['next_page_url'];
            $list['last_page_url']                   = $companies->toArray()['last_page_url'];

        }

        return $this->ReturnData($list);
    }
}
