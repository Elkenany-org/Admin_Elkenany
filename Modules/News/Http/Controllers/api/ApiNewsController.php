<?php

namespace Modules\News\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Traits\SearchReg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\News\Entities\News;
use Modules\News\Entities\News_Section;
use Modules\News\Entities\News_images;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\Data_Analysis;
use Modules\Store\Entities\Customer;
use Illuminate\Support\Facades\Input;
use Modules\News\Entities\Multi_Sec;
use Modules\SystemAds\Transformers\LogoBannerResource;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;
use Carbon\Carbon;
class ApiNewsController extends Controller
{
    use SearchReg;

    # show filter sections
    public function filterSections(Request $request)
    {

        $section_id = $request->input("section_id");

        # check section exist
        if(!$section_id)
        {
            $section = News_Section::where('selected','1')->first();
        }else{
            $section = News_Section::where('id',$section_id)->first();
        }


        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);
        }
        $sections = News_Section::get();


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


        $list['sort'] = [
            [
                'id'=>1,
                'name'=>'الأحدث',
                'value'=>1,
            ],[
                'id'=>2,
                'name'=>'الأبجدي',
                'value'=>2,
            ],[
                'id'=>3,
                'name'=>'الأكثر تداولا',
                'value'=>3,
            ]
        ];

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }
 
    # show all news
    public function ShowNews(Request $request)
    {

        $type = $request->input("type");
        $sort       = $request->input("sort");
        $search     = $request->input("search");
        $section_id = $request->input("section_id");

        if(!is_null($search)){
            $keyword = $this->searchQuery($search);
        }
        # check section exist
        if(!$section_id)
        {
            $section = News_Section::where('selected','1')->first();
        }else{
            $section = News_Section::where('id',$section_id)->first();
        }
     

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

                    $main_keyword = Data_Analysis_Keywords::where('keyword','news')->where('type',$section->id)->first();
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
  

        $page = System_Ads_Pages::with('SystemAds')->where('type','news')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


      


        if(!is_null($sort)){
            if($sort == 2){
                if(!is_null($search)){
                    $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
                    if(count($majs) == 0)
                    {
                        if($request->hasHeader('android')){
                            $news = News::where('section_id',$section->id)->where('title' , 'REGEXP' , $keyword)->orderBy('title')->get();

                        }else{
                            $news = News::where('section_id',$section->id)->where('title' , 'REGEXP' , $keyword)->orderBy('title')->paginate(10);

                        }
                    }else{
                        if($request->hasHeader('android')){
                            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->where('title' , 'REGEXP' , $keyword)->orderBy('title')->get();

                        }else{
                            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->where('title' , 'REGEXP' , $keyword)->orderBy('title')->paginate(10);

                        }
                    }
                   


                }else{
                    $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
                    if(count($majs) == 0)
                    {
                        if($request->hasHeader('android')){
                            $news = News::where('section_id',$section->id)->orderBy('title')->get();

                        }else{
                            $news = News::where('section_id',$section->id)->orderBy('title')->paginate(10);

                        }
                    }else{
                        if($request->hasHeader('android')){
                            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->orderBy('title')->get();

                        }else{
                            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->orderBy('title')->paginate(10);

                        }
                    }
                   
                }
               
            }elseif($sort == 3){
                if(!is_null($search)){

                    $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
                    if(count($majs) == 0)
                    {
                        if($request->hasHeader('android')){
                         $news = News::where('section_id',$section->id)->where('title' , 'REGEXP' , $keyword)->orderBy('view_count' , 'desc')->get();

                        }else{
                            $news = News::where('section_id',$section->id)->where('title' , 'REGEXP' , $keyword)->orderBy('view_count' , 'desc')->paginate(10);

                        }
                    }else{
                        if($request->hasHeader('android')){
                            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->where('title' , 'REGEXP' , $keyword)->orderBy('view_count' , 'desc')->get();

                        }else{
                            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->where('title' , 'REGEXP' , $keyword)->orderBy('view_count' , 'desc')->paginate(10);

                        }
                    }


                }else{
                    $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
                    if(count($majs) == 0)
                    {
                        if($request->hasHeader('android')){
                            $news = News::where('section_id',$section->id)->orderBy('view_count' , 'desc')->get();

                    }else{
                            $news = News::where('section_id',$section->id)->orderBy('view_count' , 'desc')->paginate(10);

                    }
                    }else{
                        if($request->hasHeader('android')){
                            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->orderBy('view_count' , 'desc')->get();

                        }else{
                            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->orderBy('view_count' , 'desc')->paginate(10);

                        }
                    }
   
                }
           
            }elseif($sort == 1 || $sort == 0){
                if(!is_null($search)){

                    $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
                    if(count($majs) == 0)
                    {
                        if($request->hasHeader('android')){
                            $news = News::where('section_id',$section->id)->where('title' , 'REGEXP' , $keyword)->latest()->get();

                        }else{
                            $news = News::where('section_id',$section->id)->where('title' , 'REGEXP' , $keyword)->latest()->paginate(10);

                        }
                    }else{
                        if($request->hasHeader('android')){
                            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->where('title' , 'REGEXP' , $keyword)->latest()->get();

                        }else{
                            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->where('title' , 'REGEXP' , $keyword)->latest()->paginate(10);

                        }
                    }


                }else{
                    $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
                    if(count($majs) == 0)
                    {
                        if($request->hasHeader('android')){
                            $news = News::where('section_id',$section->id)->latest()->get();

                        }else{
                            $news = News::where('section_id',$section->id)->latest()->paginate(10);

                        }
                    }else{
                        if($request->hasHeader('android')){
                            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->latest()->get();

                        }else{
                            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->latest()->paginate(10);

                        }
                    }

                }

            }
        }else{
            if(!is_null($search)){
                
                $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
                if(count($majs) == 0)
                {
                    if($request->hasHeader('android')){
                        $news = News::where('section_id',$section->id)->where('title' , 'REGEXP' , $keyword)->latest()->get();

                    }else{
                        $news = News::where('section_id',$section->id)->where('title' , 'REGEXP' , $keyword)->latest()->paginate(10);

                    }
                }else{
                    if($request->hasHeader('android')){
                        $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->where('title' , 'REGEXP' , $keyword)->latest()->get();

                    }else{
                        $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->where('title' , 'REGEXP' , $keyword)->latest()->paginate(10);

                    }
                }


            }else{

                $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
                if(count($majs) == 0)
                {
                    if($request->hasHeader('android')){
                        $news = News::where('section_id',$section->id)->orderBy('title')->get();

                    }else{
                        $news = News::where('section_id',$section->id)->orderBy('title')->paginate(10);

                    }
                }else{
                    if($request->hasHeader('android')){
                        $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->orderBy('title')->get();

                    }else{
                        $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->orderBy('title')->paginate(10);

                    }
                }
            }
        }
        $list = [];
       

        $sections = News_Section::get();

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
            $list['data'][$key]['image']       = URL::to('uploads/news/avatar/'.$new->image);
//            $list['data'][$key]['created_at']  = date(' Y-m-d ', strtotime($new->created_at));
            $list['data'][$key]['created_at']  = convertToArabicDate($new->created_at);
            if(! $request->hasHeader('android')){
                $list['current_page']              = $news->toArray()['current_page'];
                $list['last_page']                 = $news->toArray()['last_page'];
                $list['first_page_url']            = $news->toArray()['first_page_url'];
                $list['next_page_url']             = $news->toArray()['next_page_url'];
                $list['last_page_url']             = $news->toArray()['last_page_url'];
            }


        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    
    # show one news
    public function ShwOneNew(Request $request)
    {

        $id = $request->input("id");
        $News = News::where('id',$id)->first();

            
        # check News exist
        if(!$News)
        {
            $msg = 'News not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $section = News_Section::where('id' , $News->section_id)->first();
        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('type','news')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
        $list = [];

        $majs = Multi_Sec::where('section_id',$News->section_id)->pluck('new_id')->toArray();
        if(count($majs) == 0)
        { 
            $newss = News::where('section_id',$News->section_id)->take(4)->inRandomOrder()->get();
        }else{
            $newss = News::where('section_id',$News->section_id)->orWhereIn('id',$majs)->take(4)->inRandomOrder()->get();
        }
       
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


        $list['id']          = $News->id;
        $list['title']       = $News->title;
        $list['image']       = URL::to('uploads/news/avatar/'.$News->image);
        $list['desc']        = $News->desc;
        $list['created_at']  = convertToArabicDate($News->created_at);


        if(count($newss) == 0){
            $list['news'] = [];
        }else{
            foreach ($newss as $key => $new)
            {
                $list['news'][$key]['id']          = $new->id;
                $list['news'][$key]['title']       = $new->title;
                $list['news'][$key]['image']       = URL::to('uploads/news/avatar/'.$new->image);
                $list['news'][$key]['created_at']  = convertToArabicDate($new->created_at);
            

            }
        }



        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

   
}
