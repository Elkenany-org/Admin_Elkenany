<?php

namespace Modules\Tenders\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Cities\Entities\City;
use Modules\SystemAds\Transformers\LogoBannerResource;
use Modules\Tenders\Entities\Tender;
use Modules\Tenders\Entities\Tender_Section;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\Data_Analysis;
use Modules\Store\Entities\Customer;
use Illuminate\Support\Facades\Input;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;

class ApiTenController extends Controller
{


    # show filter sections
    public function filterSections(Request $request)
    {

        $section_id = $request->input("section_id");

        # check section exist
        if(!$section_id)
        {
            $section = Tender_Section::where('selected','1')->first();
        }else{
            $section = Tender_Section::where('id',$section_id)->first();
        }

        $sections = Tender_Section::get();
        
        # check section exist
        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

   
        $list = [];

        foreach ($sections as $key => $sec)
        {
            $list['sections'][$key]['id']          = $sec->id;
            $list['sections'][$key]['name']        = $sec->name;
            $list['sections'][$key]['image']        = URL::to('uploads/tenders/home/'.$sec->image);
//            $list['sectors'][$key]['type']        = $sec->type;
            if($sec->id == $section->id){
                $list['sections'][$key]['selected']        = 1;

            }else{
                $list['sections'][$key]['selected']        = 0;
            }
    


        }

        $cities_id=Tender::where('section_id',$section->id)->pluck('city_id');
        $cities= City::whereIn('id',$cities_id)->get();
        foreach ($cities as $key => $city ){
            $list['cities'][$key]['id']= $city->id;
            $list['cities'][$key]['name']= $city->name;
        }
        $arr = [];
        $arrr = [];

        $arr['id']        = 0;
        $arr['name']       = 'الابجدي ';
        $arr['value ']        = 0;

        $list['sort'][]       = $arr;

        
        $arrr['id']        = 1;
        $arrr['name']       = 'الاكثر تداولا';
        $arrr['value']        = 1;

        $list['sort'][]       = $arrr;

        $arrr['id']        = 2;
        $arrr['name']       = 'الاحدث ';
        $arrr['value']        = 2;

        $list['sort'][]       = $arrr;

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }
 
    # show all news
    public function ShowNews(Request $request)
    {

        $section_id = $request->input("section_id");
        $sort       = $request->input("sort");
        $search     = $request->input("search");


//        # check section exist
//        if(!$section_id)
//        {
//            $section = Tender_Section::where('selected','1')->first();
//        }else{
            $section = Tender_Section::where('id',$section_id)->first();
//        }

        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $section->view_count=$section->view_count+1;
        $section->save();
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

                    $main_keyword = Data_Analysis_Keywords::where('keyword','tenders')->where('type',$section->id)->first();
                    if($main_keyword){
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
        }


        $page = System_Ads_Pages::with('SystemAds')->where('type','tenders')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


      


        if(!is_null($sort)){
            $news = Tender::where('section_id',$section->id)->orderBy('title');

            if($sort == 0){//alphabetic
                if(!is_null($search)){
                    if($request->hasHeader('android')){
                        $news = $news->where('title' , 'like' , "%". $search ."%")->get();
                    }else{
                        $news = $news->where('title' , 'like' , "%". $search ."%")->get();
                    }
                }else{
                    if($request->hasHeader('android')){
                        $news = $news->get();
                    }else{
                        $news = $news->paginate(10);
                    }
                }
               
            }elseif($sort == 1){//larger view count
                $news = Tender::where('section_id',$section->id)->orderBy('view_count' , 'desc');
                if(!is_null($search)){
                    if($request->hasHeader('android')){
                        $news= $news->where('title' , 'like' , "%". $search ."%")->get();
                    }else{
                        $news= $news->where('title' , 'like' , "%". $search ."%")->paginate(10);
                    }
                }else{
                    if($request->hasHeader('android')){
                        $news= $news->get();
                    }else{
                        $news= $news->paginate(10);
                    }
                }
           
            }
        }else{//newest
            $news = Tender::where('section_id',$section->id)->latest();

            if(!is_null($search)){

                if($request->hasHeader('android')){
                    $news = $news->where('title' , 'like' , "%". $search ."%")->get();

                }else{
                    $news = $news->where('title' , 'like' , "%". $search ."%")->paginate(10);
                }
            }else{
                if($request->hasHeader('android')){
                    $news = $news->get();
                }else{
                    $news = $news->paginate(10);
                }
            }
        }
        $list = [];
       

        $sections = Tender_Section::get();

        foreach ($sections as $key => $sec)
        {
            $list['sections'][$key]['id']          = $sec->id;
            $list['sections'][$key]['name']        = $sec->name;
            $list['sections'][$key]['type']        = $sec->type;
            if($sec->id == $section->id){
                $list['sections'][$key]['selected']        = 1;
            }else{
                $list['sections'][$key]['selected']        = 0;
            }



        }

        $list['banners'] = count($adss) == 0 ? [] : LogoBannerResource::collection($adss);
        $list['logos'] = count($logos) == 0 ? [] : LogoBannerResource::collection($logos);



        

        if(count($news) < 1)
        {
            $list['data'] = [];
            if(! $request->hasHeader('android')){
                $list['current_page']                     = $news->toArray()['current_page'];
                $list['last_page']                        = $news->toArray()['last_page'];
            }
        }

        foreach ($news as $key => $new)
        {
            $list['data'][$key]['id']          = $new->id;
            $list['data'][$key]['title']       = $new->title;
            $list['data'][$key]['image']       = URL::to('uploads/tenders/avatar/'.$new->image);
            $list['data'][$key]['created_at']  = convertToArabicDate($new->created_at);
            if(! $request->hasHeader('android')) {

                $list['current_page'] = $news->toArray()['current_page'];
                $list['last_page'] = $news->toArray()['last_page'];
                $list['first_page_url'] = $news->toArray()['first_page_url'];
                $list['next_page_url'] = $news->toArray()['next_page_url'];

                $list['last_page_url'] = $news->toArray()['last_page_url'];
            }

        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    
    # show one Tender
    public function ShwOneNew(Request $request)
    {

        $id = $request->input("id");
        $News = Tender::where('id',$id)->first();

            
        # check News exist
        if(!$News)
        {
            $msg = 'Tender not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $section = Tender_Section::where('id' , $News->section_id)->first();
        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('section_type',$section->type)->where('type','tenders')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
        $list = [];

        
            $newss = Tender::where('section_id',$News->section_id)->take(4)->inRandomOrder()->get();
      
       
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

        $News->view_count = $News->view_count+1;
        $News->save();

        $list['id']          = $News->id;
        $list['title']       = $News->title;
        $list['image']       = URL::to('uploads/tenders/avatar/'.$News->image);
        $list['desc']        = $News->desc;
        $list['created_at']  = convertToArabicDate($News->created_at);

        if(count($newss) == 0){
            $list['tenders'] = [];
        }else{
            foreach ($newss as $key => $new)
            {
                $list['tenders'][$key]['id']          = $new->id;
                $list['tenders'][$key]['title']       = $new->title;
                $list['tenders'][$key]['image']       = URL::to('uploads/tenders/avatar/'.$new->image);
                $list['tenders'][$key]['created_at']  = convertToArabicDate($new->created_at);
            

            }
        }



        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    #show home tenders
    public function TenderSections(Request $request){
        $sort       = $request->input("sort");
        $search     = $request->input("search");

        if(!is_null($sort)){
            if($sort == 0){//alphabetic
                if(!is_null($search)){
                    $sections = Tender_Section::where('name' , 'like' , "%". $search ."%")->orderBy('name');
                    if($request->hasHeader('android')){
                        $sections = $sections->get();

                    }else{
                        $sections = $sections->paginate(12);

                    }
                }else{
                    $sections = Tender_Section::orderBy('name');
                    if($request->hasHeader('android')){
                        $sections = $sections->get();

                    }else{
                        $sections = $sections->paginate(12);
                    }
                }

            }elseif($sort == 1){//larger view count
                if(!is_null($search)){
                    $sections = Tender_Section::where('name' , 'like' , "%". $search ."%")->orderBy('view_count' , 'desc');
                    if($request->hasHeader('android')){
                        $sections = $sections->get();

                    }else{
                        $sections = $sections->paginate(12);
                    }
                }else{
                    $sections = Tender_Section::orderBy('view_count' , 'desc');
                    if($request->hasHeader('android')){
                        $sections = $sections->get();

                    }else{
                        $sections = $sections->paginate(12);
                    }
                }
            }
        }
        else{//newest
            if(!is_null($search)){
                $sections = Tender_Section::where('name' , 'like' , "%". $search ."%")->latest();
                if($request->hasHeader('android')){
                    $sections = $sections->get();
                }else{
                    $sections = $sections->paginate(12);
                }
            }else{
                $sections = Tender_Section::latest();
                if($request->hasHeader('android')){
                    $sections = $sections->get();
                }else{
                    $sections = $sections->paginate(12);
                }
            }
        }

        $page = System_Ads_Pages::with('SystemAds')->where('type','tenders')->pluck('ads_id')->toArray();

        $ads = System_Ads::where('main','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::where('main','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $list['banners'] = count($ads) == 0 ? [] : LogoBannerResource::collection($ads);
        $list['logos'] = count($logos) == 0 ? [] : LogoBannerResource::collection($logos);

        if(count($sections) < 1)
        {
            if(! $request->hasHeader('android')){
            $list['sections'] = [];
            $list['current_page']                     = $sections->toArray()['current_page'];
            $list['last_page']                        = $sections->toArray()['last_page'];
        }
        }
        foreach ($sections as $key => $sec)
        {
            $list['sections'][$key]['id']          = $sec->id;
            $list['sections'][$key]['name']        = $sec->name;
            $list['sections'][$key]['image']        = URL::to('uploads/tenders/home/'.$sec->image);
            if(! $request->hasHeader('android')) {

                $list['current_page'] = $sections->toArray()['current_page'];
                $list['last_page'] = $sections->toArray()['last_page'];
                $list['first_page_url'] = $sections->toArray()['first_page_url'];
                $list['next_page_url'] = $sections->toArray()['next_page_url'];
                $list['last_page_url'] = $sections->toArray()['last_page_url'];
            }
        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    public function filterHomeSections()
    {
        $list = [];

        $arr['id']        = 0;
        $arr['name']       = 'الابجدي ';
        $arr['value ']        = 0;

        $list['sort'][]       = $arr;


        $arrr['id']        = 1;
        $arrr['name']       = 'الاكثر تداولا';
        $arrr['value']        = 1;

        $list['sort'][]       = $arrr;

        $arrr['id']        = 2;
        $arrr['name']       = 'الاحدث ';
        $arrr['value']        = 2;

        $list['sort'][]       = $arrr;

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

}
