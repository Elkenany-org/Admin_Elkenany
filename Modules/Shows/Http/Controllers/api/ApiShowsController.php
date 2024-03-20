<?php

namespace Modules\Shows\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Setting;
use App\Traits\ApiResponse;
use App\Traits\SearchReg;
use Auth;
use Date;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Image;
use Modules\Analysis\Entities\Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\Cities\Entities\City;
use Modules\Countries\Entities\Country;
use Modules\Magazines\Entities\Magazin_magazines;
use Modules\Shows\Entities\Interested;
use Modules\Shows\Entities\Place;
use Modules\Shows\Entities\Show;
use Modules\Shows\Entities\Show_Going;
use Modules\Shows\Entities\Show_Reat;
use Modules\Shows\Entities\Show_Section;
use Modules\Store\Entities\Customer;
use Modules\SystemAds\Entities\System_Ads;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Transformers\LogoBannerResource;
use Modules\Us\Entities\Contuct;
use Modules\Us\Entities\Office;
use Session;
use URL;
use Validator;
use View;

class ApiShowsController extends Controller
{
    use SearchReg,ApiResponse;

    public function offices()
    {
        $offices = Office::get();
        $list = [];

        $phon = [];
        $eme = [];
        $mob = [];
        $faxx = [];

        foreach ($offices as $key => $sec)
        {
            $list['offices'][$key]['id']          = $sec->id;
            $list['offices'][$key]['name']        = $sec->name;
            $list['offices'][$key]['address']        = $sec->address;
            $list['offices'][$key]['latitude']        = $sec->latitude;
            $list['offices'][$key]['longitude']        = $sec->longitude;
            $list['offices'][$key]['desc']        = $sec->desc;
            if($sec->status == 0){
                $list['offices'][$key]['status']        = 1;
            }else{
                $list['offices'][$key]['selected']        = 0;
            }


            foreach ($sec->phones() as $kp => $pho)
            {
                $list['offices'][$key]['phones'][$kp]['phone']        = $pho;
            }
    
            foreach ($sec->emails() as $k => $em)
            {
                $list['offices'][$key]['emails'][$k]['email']        = $em;
            }
    
            foreach ($sec->mobiles() as $ke => $mo)
            {
                $list['offices'][$key]['mobiles'][$ke]['mobile']        = $mo;
            }
    
            foreach ($sec->faxs() as $kx => $fa)
            {
                $list['offices'][$key]['faxs'][$kx]['fax']        = $fa;
            }
    
    


        }


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }

    # add contuct
    public function contuct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'required',
            'company'      => 'required',
            'phone'        => 'required',
            'desc'         => 'required',
            'job'          => 'required',
        ]);

        foreach ((array) $validator->errors() as $value)
		{
			if(isset($value['name']))
			{
				$msg = 'name is required';
				return response()->json([
					'message'  => null,
					'error'    => $msg,
				],400);
			}elseif(isset($value['desc']))
            {
                $msg = 'desc is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['job']))
            {
                $msg = 'job is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['email']))
            {
                $msg = 'email is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['phone']) && is_null($request->phone))
            {
                $msg = 'phone is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['company']))
            {
                $msg = 'company is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }
    
        $contuct = new Contuct;
        $contuct->name         = $request->name;
        $contuct->email        = $request->email;
        $contuct->company      = $request->company;
        $contuct->phone        = $request->phone;
        $contuct->desc         = $request->desc;
        $contuct->job          = $request->job;
        $contuct->save();

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $contuct
        ],200);
    }

    public function about()
    {
        $setting = Setting::first();
        $list = [];

        $list['about'] = $setting->about_ar;


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }


    


    # show shows
    public function showshows(Request $request)
    {

        $type    = $request->input("type");
        $city_id = $request->input("city_id");
        $sort    = $request->input("sort");
        $search  = $request->input("search");
        $country_id = $request->input("country_id");
        $section_id    = $request->input("section_id");

        # check section exist
        if(!$section_id)
        {
            $section = Show_Section::with('Shows')->where('selected','1')->first();
        }else{
            $section = Show_Section::with('Shows')->where('id',$section_id)->first();
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

                    $main_keyword = Data_Analysis_Keywords::where('keyword','show')->where('type',$section->id)->first();
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
     

        $page = System_Ads_Pages::with('SystemAds')->where('type','shows')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $shows = Show::with(['City','Section']);


        if(!is_null($section))
        {
            $shows = $shows->whereHas('Section',function($q) use ($section) {
                $q->where('section_id',$section->id);
            });
        }


        if(!is_null($city_id))
        {

            if($city_id == 0){
                $cities = City::where('country_id',$country_id)->get()->pluck('id');
                $shows = $shows->whereIn('city_id',$cities);
            }else{
                $shows = $shows->where('city_id',$city_id);
            }

        }

        if(!is_null($country_id))
        {
            $shows = $shows->where('country_id',$country_id);
        }

        if(!is_null($search)){
            $keyword = $this->searchQuery($search);
        }
        if(!is_null($sort))
        {
            if($sort == 0){
                if(!is_null($search)){
                    if($request->hasHeader('android')){
                        $shows = $shows->where('name' , 'REGEXP' , $keyword)->orderby('name')->get();

                    }else{
                        $shows = $shows->where('name' , 'REGEXP' , $keyword)->orderby('name')->paginate(10);
                    }
                }else{
                    if($request->hasHeader('android')){
                        $shows = $shows->orderby('name')->get();

                    }else{
                        $shows = $shows->orderby('name')->paginate(10);
                    }
                }
            }else{
                if(!is_null($search)){
                    if($request->hasHeader('android')){
                        $shows = $shows->where('name' , 'REGEXP' , $keyword)->orderby('rate' , 'desc')->get();

                    }else{
                        $shows = $shows->where('name' , 'REGEXP' , $keyword)->orderby('rate' , 'desc')->paginate(10);
                    }
                }else{
                    if($request->hasHeader('android')){
                        $shows = $shows->orderby('rate' , 'desc')->get();

                    }else{
                        $shows = $shows->orderby('rate' , 'desc')->paginate(10);
                    }
                }
            }
        }else{
            if(!is_null($search)){
                if($request->hasHeader('android')){
                    $shows = $shows->where('name' , 'REGEXP' , $keyword)->orderby('name')->get();

                }else{
                    $shows = $shows->where('name' , 'REGEXP' , $keyword)->orderby('name')->paginate(10);
                }
            }else{
                if($request->hasHeader('android')){
                    $shows = $shows->get();
                }else{
                    $shows = $shows->paginate(10);
                }
            }
        }

        $list = [];

        $sections = Show_Section::get();

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


        if(count($shows) < 1)
        {
            $list['data'] = [];
            if(! $request->hasHeader('android')){
                $list['current_page']                     = $shows->toArray()['current_page'];
                $list['last_page']                        = $shows->toArray()['last_page'];
            }

        }

        foreach ($shows as $key => $show)
        {
            $list['data'][$key]['id']                = $show->id;
            $list['data'][$key]['name']              = $show->name;
            $list['data'][$key]['rate']              = $show->rate;
            $list['data'][$key]['image']             = URL::to('uploads/show/images/'.$show->image);
            $list['data'][$key]['desc']              = Str::limit($show->desc, 60, '...');
            $list['data'][$key]['address']           = $show->City->name;
            $list['data'][$key]['view_count']        = $show->view_count;
            if(count($show->time()) > 0){

                $list['data'][$key]['date']             = $show->time()[0];
            }else{
                $list['data'][$key]['date']             = null;
            }
             

         

            if(!is_null($request->header('Authorization')))
            {
                $token = $request->header('Authorization'); 
                $token = explode(' ',$token);
                if(count( $token) == 2)
                {

                    $customer = Customer::where('api_token',$token[1])->first();
                    if($customer){
                        $going = Show_Going::where('show_id',$show->id)->where('user_id', $customer->id)->first();

                        if(!$going)
                        {
                            $list['data'][$key]['going_state']           = false;
            
                        }else{
                            $list['data'][$key]['going_state']           = true;
            
                        }
                    }else{
                        $list['data'][$key]['going_state']           = null;
                    }
                   

                }else{
                    $list['data'][$key]['going_state']           = null;
                }
            }else{
                $list['data'][$key]['going_state']           = null;
            }
            $list['data'][$key]['deeb_link']          = URL::to('api/showes/one-show/?id='.$show->id);
//            $list['data'][$key]['link']          = route('front_one_show',$show->id);
            $list['data'][$key]['link']          = '';
            if(! $request->hasHeader('android')) {
                $list['current_page']                    = $shows->toArray()['current_page'];
                $list['last_page']                       = $shows->toArray()['last_page'];
                $list['first_page_url']                  = $shows->toArray()['first_page_url'];
                $list['next_page_url']                   = $shows->toArray()['next_page_url'];
                $list['last_page_url']                   = $shows->toArray()['last_page_url'];
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

        $section = Show_Section::with('Shows')->where('id',$section_id)->first();
        # check section exist
        if(!$section_id)
        {
            $section = Show_Section::with('Shows')->where('selected','1')->first();
        }else{
            $section = Show_Section::with('Shows')->where('id',$section_id)->first();
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

        $sections = Show_Section::get();
        $countries=   Country::get();
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


        $list['cities'][0]['id'] = 0;
        $list['cities'][0]['name'] = 'الكل';
        foreach ($cities as $key => $city)
        {
            $list['cities'][$key+1]['id']          = $city->id;
            $list['cities'][$key+1]['name']        = $city->name;
        }

        foreach ($countries as $key => $country)
        {
            $list['countries'][$key]['id']          = $country->id;
            $list['countries'][$key]['name']        = $country->name;
            if(isset($country_id) && $country->id == $country_id){
                    $list['countries'][$key]['selected']        = 1;
            }else{
                $list['countries'][$key]['selected']        = 0;
            }
        }

        $list['sort'] = [
            [
                'id'=> 1,
                'name'=>'الابجدي',
                'value'=>0
            ],[
                'id'=> 2,
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

 

    # show shows
    public function Show(Request $request)
    {

        $id = $request->input("id");

        $show = Show::with('ShowImgs','ShowOrgs.Organ.ShowOrgs.Show','ShowTacs','Showers','City')->where('id',$id)->first();

        # check section exist
        if(!$show)
        {
            $msg = 'shows not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('type','shows')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $show->view_count = $show->view_count + 1;
        $show->save();


        $list = [];
        $img = [];
        $time = [];
        $watch = [];
        $tict = [];
        $organ = [];


        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


            }

        }


        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


            }
        }


        $list['id']             = $show->id;
        $list['name']           = $show->name;
        $list['short_desc']     = $show->desc;
        $list['view_count']     = $show->view_count;
        $list['address']        = $show->City->name;
        $list['rate']           = $show->rate;
        $list['count_Showers']  = count($show->Showers);
        $list['image']          = URL::to('uploads/show/images/'.$show->image);

        $list['created_at']     = Date::parse($show->created_at)->diffForHumans();


        foreach ($show->time() as $key => $tim)
        {
            //date=>time
            $time[$key]['date']        = $tim;
        }
        //times=>dates
        $list['dates']         = $time;

        foreach ($show->watch() as $key => $wat)
        {
            //time=>watch
            $watch[$key]['time']        = $wat;
        }
        //watches=>times
        $list['times']         = $watch;

        // teckits
        foreach ($show->ShowTacs as $kk => $tec)
        {
            //name=>status
            $tict[$kk]['status']       = $tec->name;
            $tict[$kk]['price']      = $tec->price;

        }

        $list['tickets']        = $tict;

        // images
        foreach ($show->ShowImgs as $Ki => $value)
        {
            $img[$Ki]['image']     = URL::to('uploads/show/alboum/'.$value->image);
            $img[$Ki]['id']        = $value->id;
        }

        $list['images']         = $img;

        // organisers
        foreach ($show->ShowOrgs as $Kpd => $value)
        {
            $organ[$Kpd]['name']      = $value->Organ->name;
            $organ[$Kpd]['id']        = $value->Organ->id;
        }

        $list['organisers']         = $organ;

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

//    public function Show()
//    {
//
//        $id = $request->input("id");
//
//        $show = Show::with('ShowImgs','ShowOrgs.Organ.ShowOrgs.Show','ShowTacs','Showers','City')->where('id',$id)->first();
//
//        # check section exist
//        if(!$show)
//        {
//            $msg = 'shows not found';
//            return response()->json([
//                'status'   => '0',
//                'message'  => null,
//                'error'    => $msg,
//            ],400);
//        }
//
//        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('type','shows')->pluck('ads_id')->toArray();
//
//        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
//        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
//
//
//        $show->view_count = $show->view_count + 1;
//        $show->save();
//
//
//        $list = [];
//        $img = [];
//        $time = [];
//        $watch = [];
//        $tict = [];
//        $organ = [];
//
//
//        if(count($adss) == 0){
//            $list['banners'] = [];
//        }else{
//            foreach ($adss as $key => $ad)
//            {
//                $list['banners'][$key]['id']          = $ad->id;
//                $list['banners'][$key]['link']        = $ad->link;
//                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);
//
//
//            }
//
//        }
//
//
//        if(count($logos) == 0){
//            $list['logos'] = [];
//        }else{
//            foreach ($logos as $key => $logo)
//            {
//                $list['logos'][$key]['id']          = $logo->id;
//                $list['logos'][$key]['link']        = $logo->link;
//                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);
//
//
//            }
//        }
//
//
//        $list['id']             = $show->id;
//        $list['name']           = $show->name;
//        $list['short_desc']     = $show->desc;
//        $list['view_count']     = $show->view_count;
//        $list['address']        = $show->City->name;
//        $list['rate']           = $show->rate;
//        $list['count_Showers']  = count($show->Showers);
//        $list['image']          = URL::to('uploads/show/images/'.$show->image);
//
//        $list['created_at']     = Date::parse($show->created_at)->diffForHumans();
//
//
//        foreach ($show->time() as $key => $tim)
//        {
//            $time[$key]['phone']        = $tim;
//        }
//
//        $list['times']         = $time;
//
//        foreach ($show->watch() as $key => $wat)
//        {
//            $watch[$key]['watch']        = $wat;
//        }
//
//        $list['watches']         = $watch;
//
//
//        // teckits
//        foreach ($show->ShowTacs as $kk => $tec)
//        {
//            $tict[$kk]['name']       = $tec->name;
//            $tict[$kk]['price']      = $tec->price;
//
//        }
//
//        $list['teckits']        = $tict;
//
//        // images
//        foreach ($show->ShowImgs as $Ki => $value)
//		{
//            $img[$Ki]['image']     = URL::to('uploads/show/alboum/'.$value->image);
//            $img[$Ki]['id']        = $value->id;
//        }
//
//        $list['images']         = $img;
//
//        // organisers
//        foreach ($show->ShowOrgs as $Kpd => $value)
//        {
//            $organ[$Kpd]['name']      = $value->Organ->name;
//            $organ[$Kpd]['id']        = $value->Organ->id;
//        }
//
//        $list['organisers']         = $organ;
//
//        return response()->json([
//            'message'  => null,
//            'error'    => null,
//            'data'     => $list
//        ],200);
//
//    }

  
    # Show
    public function oneShowrev(Request $request)
    {

        $id = $request->input("id");

        $show = Show::with('ShowImgs','ShowOrgs.Organ.ShowOrgs.Show','ShowTacs','ShowReats')->where('id',$id)->first();

        # check section exist
        if(!$show)
        {
            $msg = 'shows not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('type','shows')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
    

        $list = [];

     
        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


            }

        }
       
        
        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


            }
        }



        if(count($show->ShowReats) == 0){
            $list['review'] = [];
        }else{
            foreach ($show->ShowReats as $Kpd => $value)
            {
                $list['review'][$Kpd]['name']       = $value->name;
                $list['review'][$Kpd]['email']      = $value->email;
                $list['review'][$Kpd]['desc']       = $value->desc;
                $list['review'][$Kpd]['created_at'] = $value->created_at;
                $list['review'][$Kpd]['rate']       = $value->rate;
                $list['review'][$Kpd]['id']         = $value->id;
            }

        }
 

        $list['rate']           = $show->rate;


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
       
    }

    # Showers
    public function Showers(Request $request)
    {

        $id = $request->input("id");

        $list = [];

        $show = Show::with('ShowImgs','ShowOrgs.Organ.ShowOrgs.Show','ShowTacs','Showers')->where('id',$id)->first();
    

        # check section exist
        if(!$show)
        {
            $msg = 'shows not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $page = System_Ads_Pages::with('SystemAds')->where('status','0')->where('type','localstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
    

        

     
        if(count($adss)==0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


            }

        }
       
        
        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


            }
        }




        if(count($show->Showers) == 0){
            $list['Showers'] = [];

        }else{
            foreach ($show->Showers as $Kpd => $value)
            {
                $list['Showers'][$Kpd]['name']       = $value->name;
                $list['Showers'][$Kpd]['image']      = URL::to('uploads/show/shower/'.$value->image);
                $list['Showers'][$Kpd]['id']         = $value->id;
            }

        }





        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    
    }
    // # Showers
    // public function Showers(Request $request)
    // {

    //     $id = $request->input("id");

    //     $list = [];

    //     $show = Show::with('ShowImgs','ShowOrgs.Organ.ShowOrgs.Show','ShowTacs','Showers')->where('id',$id)->first();
    

    //     # check section exist
    //     if(!$show)
    //     {
    //         $msg = 'shows not found';
    //         return response()->json([
    //             'message'  => null,
    //             'error'    => $msg,
    //         ],400);	
    //     }

    //     $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('type','shows')->pluck('ads_id')->toArray();

    //     $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
    //     $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
    

        

     
    //     if(count($adss) == 0){
    //         $list['banners'] = [];
    //     }else{
    //         foreach ($adss as $key => $ad)
    //         {
    //             $list['banners'][$key]['id']          = $ad->id;
    //             $list['banners'][$key]['link']        = $ad->link;
    //             $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


    //         }

    //     }
       
        
    //     if(count($logos) == 0){
    //         $list['logos'] = [];
    //     }else{
    //         foreach ($logos as $key => $logo)
    //         {
    //             $list['logos'][$key]['id']          = $logo->id;
    //             $list['logos'][$key]['link']        = $logo->link;
    //             $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


    //         }
    //     }




    //     if(count($show->Showers) == 0){
    //         $list['Showers'] = [];

    //     }else{
    //         foreach ($show->Showers as $Kpd => $value)
    //         {
    //             $list['Showers'][$Kpd]['name']       = $value->name;
    //             $list['Showers'][$Kpd]['image']      = URL::to('uploads/show/shower/'.$value->image);
    //             $list['Showers'][$Kpd]['id']         = $value->id;
    //         }

    //     }





    //     return response()->json([
    //         'message'  => null,
    //         'error'    => null,
    //         'data'     => $list
    //     ],200);
    
    // }

    # speakers
    public function speakers(Request $request)
    {

        $id = $request->input("id");

        $show = Show::with('ShowImgs','ShowOrgs.Organ.ShowOrgs.Show','ShowTacs','Speakers')->where('id',$id)->first();
    

        # check section exist
        if(!$show)
        {
            $msg = 'shows not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }
        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('type','shows')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
    

        $list = [];

       
        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


            }

        }
       
        
        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


            }
        }



        if(count($show->Speakers) == 0){
            $list['Speakers'] = [];
        }else{
            foreach ($show->Speakers as $Kpd => $value)
            {
                $list['Speakers'][$Kpd]['name']       = $value->name;
                $list['Speakers'][$Kpd]['type']       = $value->type;
                $list['Speakers'][$Kpd]['image']      = URL::to('uploads/show/speaker/'.$value->image);
                $list['Speakers'][$Kpd]['id']         = $value->id;
            }
        }


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    
    }

    # add place
    public function place(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'required',
            'company'      => 'required',
            'phone'        => 'required',
            'desc'         => 'required',
            'show_id'      => 'required',
        ]);
        
        foreach ((array) $validator->errors() as $value)
		{
			if(isset($value['name']))
			{
				$msg = 'name is required';
				return response()->json([
					'message'  => null,
					'error'    => $msg,
				],400);
			}elseif(isset($value['email']) && is_null($request->email))
            {
                $msg = 'email is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['company']))
            {
                $msg = 'company is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['phone']))
            {
                $msg = 'phone is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['desc']))
            {
                $msg = 'desc is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['show_id']))
            {
                $msg = 'show_id is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        $show = Show::where('id',$request->show_id)->first();
    

        # check section exist
        if(!$show)
        {
            $msg = 'shows not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

     
        $place = new Place;
        $place->name         = $request->name;
        $place->email        = $request->email;
        $place->company      = $request->company;
        $place->phone        = $request->phone;
        $place->desc         = $request->desc;
        $place->show_id      = $request->show_id;
        $place->save();

        return response()->json([
            'message'  => 'تم الارسال',
            'error'    => null,
        
        ],200);
    }


    # add rating
    public function rating(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'required',
            'desc'         => 'required',
            'show_id'      => 'required',
        ]);
        
        foreach ((array) $validator->errors() as $value)
		{
			if(isset($value['name']))
			{
				$msg = 'name is required';
				return response()->json([
					'message'  => null,
					'error'    => $msg,
				],400);
			}elseif(isset($value['email']) && is_null($request->email))
            {
                $msg = 'email is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['desc']))
            {
                $msg = 'desc is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['show_id']))
            {
                $msg = 'show_id is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        
        $show = Show::where('id',$request->show_id)->first();
    

        # check section exist
        if(!$show)
        {
            $msg = 'shows not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $rating = new Show_Reat;
        $rating->desc        = $request->desc;
        $rating->show_id     = $request->show_id;
        $rating->name        = $request->name;
        $rating->email       = $request->email;
        $rating->rate        = $request->rate;
        $rating->save();

        if(!is_null($request->rate)){
            $show = Show::findOrFail($request->show_id);
            $show->rate =  Show_Reat::where('show_id' , $request->show_id)->avg('rate');
            $show->save();
        }
     

        return response()->json([
            'message'  => 'تم الارسال',
            'error'    => null,
        ],200);
    }
   
    # add going
    public function going(Request $request)
    {

        
        $validator = Validator::make($request->all(), [
   
            'show_id'      => 'required',
        ]);
        
        foreach ((array) $validator->errors() as $value)
		{
			if(isset($value['show_id']))
            {
                $msg = 'show_id is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        
        $show = Show::where('id',$request->show_id)->first();
    

        # check section exist
        if(!$show)
        {
            $msg = 'shows not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $going = new Show_Going;
        $going->show_id     = $request->show_id;
        $going->user_id     = session('customer')->id;
        $going->save();


        return response()->json([
            'message'  => 'تم التحديد',
            'error'    => null,
        ],200);
    }

    # not going
    public function notgoing(Request $request)
    {

        $validator = Validator::make($request->all(), [
   
            'show_id'      => 'required',
        ]);
        
        foreach ((array) $validator->errors() as $value)
		{
			if(isset($value['show_id']))
            {
                $msg = 'show_id is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        
        $show = Show::where('id',$request->show_id)->first();
    

        # check section exist
        if(!$show)
        {
            $msg = 'shows not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $going = Show_Going::where('show_id',$request->show_id)->where('user_id', session('customer')->id)->first();

        $going->delete();

        return response()->json([
            'message'  => 'تم التحديد',
            'error'    => null,
        ],200);
    }

    # add going
    public function inter(Request $request)
    {

        
        $validator = Validator::make($request->all(), [

            'show_id'      => 'required',
        ]);
        
        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['show_id']))
            {
                $msg = 'show_id is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        
        $show = Show::where('id',$request->show_id)->first();
    

        # check section exist
        if(!$show)
        {
            $msg = 'shows not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $going = new Interested;
        $going->show_id     = $request->show_id;
        $going->user_id     = session('customer')->id;
        $going->save();


        return response()->json([
            'message'  => 'تم التحديد',
            'error'    => null,
        ],200);
    }

    # not going
    public function notinter(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'show_id'      => 'required',
        ]);
        
        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['show_id']))
            {
                $msg = 'show_id is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        
        $show = Show::where('id',$request->show_id)->first();
    

        # check section exist
        if(!$show)
        {
            $msg = 'shows not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $going = Interested::where('show_id',$request->show_id)->where('user_id', session('customer')->id)->first();

        $going->delete();

        return response()->json([
            'message'  => 'تم التحديد',
            'error'    => null,
        ],200);
    }
}
