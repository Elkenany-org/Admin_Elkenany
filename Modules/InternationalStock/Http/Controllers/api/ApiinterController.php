<?php

namespace Modules\InternationalStock\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\InternationalStock\Entities\Ports;
use Modules\InternationalStock\Entities\Ships_Product;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Modules\InternationalStock\Entities\Ships;
use Modules\Guide\Entities\Company;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Modules\Store\Entities\Customer;
use Modules\InternationalStock\Entities\Inter_Rep;
use Modules\InternationalStock\Entities\L_news;
use Modules\InternationalStock\Entities\Tec_Analysis;
use Modules\InternationalStock\Entities\BodCast;
use Carbon\Carbon;
use App\Social;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;

class ApiinterController extends Controller
{
    
    # show filter
    public function filter()
    {

 
        $list = [];

        

    
        $arr = [];
        $arrr = [];

        $arr['id']        = 1;
        $arr['name']       = 'الابجدي ';
        $arr['value ']        = 0;

        $list['sort'][]       = $arr;

        
        $arrr['id']        = 2;
        $arrr['name']       = 'الاكثر تداولا';
        $arrr['value']        = 1;

        $list['sort'][]       = $arrr;


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show all Inter_Rep
    public function Showreports(Request $request)
    {

        $sort       = Input::get("sort");
        $search     = Input::get("search");


    



        if(!is_null($sort)){
            if($sort == 0){
                if(!is_null($search)){
               
                    $news = Inter_Rep::where('title' , 'like' , "%". $search ."%")->orderBy('title')->paginate(10);
          

                }else{
                 
                        $news = Inter_Rep::orderBy('title')->paginate(10);
                 
                    
                }
                
            }elseif($sort == 1){
                if(!is_null($search)){

                  
                    $news = Inter_Rep::where('title' , 'like' , "%". $search ."%")->orderBy('view_count' , 'desc')->paginate(10);
                  


                }else{
                  
                    $news = Inter_Rep::orderBy('view_count' , 'desc')->paginate(10);
                   
    
                }
            
            }
        }else{
            if(!is_null($search)){
              
                $news = Inter_Rep::where('title' , 'like' , "%". $search ."%")->latest()->paginate(10);
               


            }else{

             
                $news = Inter_Rep::orderBy('title')->paginate(10);
              
            }
        }

        $list = [];
    



        if(count($news) < 1)
        {
            $list['data'] = [];
            $list['current_page']                     = $news->toArray()['current_page'];
            $list['last_page']                        = $news->toArray()['last_page'];
        }

        foreach ($news as $key => $new)
        {
            $list['data'][$key]['id']          = $new->id;
            $list['data'][$key]['title']       = $new->title;
            $list['data'][$key]['image']       = URL::to('uploads/news/avatar/'.$new->image);
            $list['data'][$key]['created_at']  = Date::parse($new->created_at)->diffForHumans();
            $list['current_page']              = $news->toArray()['current_page'];
            $list['last_page']                 = $news->toArray()['last_page'];
            $list['first_page_url']            = $news->toArray()['first_page_url'];
            $list['next_page_url']             = $news->toArray()['next_page_url'];
            $list['last_page_url']             = $news->toArray()['last_page_url'];

        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

  
    # show one reports
    public function ShwOnereport()
    {

        $id = Input::get("id");
        $News = Inter_Rep::where('id',$id)->first();

            
        # check News exist
        if(!$News)
        {
            $msg = 'report not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

    
        $list = [];

        
            $newss = Inter_Rep::take(4)->inRandomOrder()->get();
    


        $list['id']          = $News->id;
        $list['title']       = $News->title;
        $list['image']       = URL::to('uploads/news/avatar/'.$News->image);
        $list['desc']        = $News->desc;
        $list['created_at']  = Date::parse($News->created_at)->diffForHumans();

        if(count($newss) == 0){
            $list['news'] = [];
        }else{
            foreach ($newss as $key => $new)
            {
                $list['news'][$key]['id']          = $new->id;
                $list['news'][$key]['title']       = $new->title;
                $list['news'][$key]['image']       = URL::to('uploads/news/avatar/'.$new->image);
                $list['news'][$key]['created_at']  = Date::parse($new->created_at)->diffForHumans();
            

            }
        }



        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show all last news
    public function Showlast(Request $request)
    {

        $sort       = Input::get("sort");
        $search     = Input::get("search");


    



        if(!is_null($sort)){
            if($sort == 0){
                if(!is_null($search)){
                
                    $news = L_news::where('title' , 'like' , "%". $search ."%")->orderBy('title')->paginate(10);
        

                }else{
                
                        $news = L_news::orderBy('title')->paginate(10);
                
                    
                }
                
            }elseif($sort == 1){
                if(!is_null($search)){

                
                    $news = L_news::where('title' , 'like' , "%". $search ."%")->orderBy('view_count' , 'desc')->paginate(10);
                


                }else{
                
                    $news = L_news::orderBy('view_count' , 'desc')->paginate(10);
                    
    
                }
            
            }
        }else{
            if(!is_null($search)){
            
                $news = L_news::where('title' , 'like' , "%". $search ."%")->latest()->paginate(10);
                


            }else{

            
                $news = L_news::orderBy('title')->paginate(10);
            
            }
        }

        $list = [];
    



        if(count($news) < 1)
        {
            $list['data'] = [];
            $list['current_page']                     = $news->toArray()['current_page'];
            $list['last_page']                        = $news->toArray()['last_page'];
        }

        foreach ($news as $key => $new)
        {
            $list['data'][$key]['id']          = $new->id;
            $list['data'][$key]['title']       = $new->title;
            $list['data'][$key]['image']       = URL::to('uploads/news/avatar/'.$new->image);
            $list['data'][$key]['created_at']  = Date::parse($new->created_at)->diffForHumans();
            $list['current_page']              = $news->toArray()['current_page'];
            $list['last_page']                 = $news->toArray()['last_page'];
            $list['first_page_url']            = $news->toArray()['first_page_url'];
            $list['next_page_url']             = $news->toArray()['next_page_url'];
            $list['last_page_url']             = $news->toArray()['last_page_url'];

        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    # show one last
    public function ShwOnelastt()
    {

        $id = Input::get("id");
        $News = L_news::where('id',$id)->first();

            
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

    
        $list = [];

        
            $newss = L_news::take(4)->inRandomOrder()->get();
    


        $list['id']          = $News->id;
        $list['title']       = $News->title;
        $list['image']       = URL::to('uploads/news/avatar/'.$News->image);
        $list['desc']        = $News->desc;
        $list['created_at']  = Date::parse($News->created_at)->diffForHumans();

        if(count($newss) == 0){
            $list['news'] = [];
        }else{
            foreach ($newss as $key => $new)
            {
                $list['news'][$key]['id']          = $new->id;
                $list['news'][$key]['title']       = $new->title;
                $list['news'][$key]['image']       = URL::to('uploads/news/avatar/'.$new->image);
                $list['news'][$key]['created_at']  = Date::parse($new->created_at)->diffForHumans();
            

            }
        }



        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }
   

    # show all analysis
    public function Showanalysis(Request $request)
    {

        $sort       = Input::get("sort");
        $search     = Input::get("search");


    



        if(!is_null($sort)){
            if($sort == 0){
                if(!is_null($search)){
                
                    $news = Tec_Analysis::where('title' , 'like' , "%". $search ."%")->orderBy('title')->paginate(10);
        

                }else{
                
                        $news = Tec_Analysis::orderBy('title')->paginate(10);
                
                    
                }
                
            }elseif($sort == 1){
                if(!is_null($search)){

                
                    $news = Tec_Analysis::where('title' , 'like' , "%". $search ."%")->orderBy('view_count' , 'desc')->paginate(10);
                


                }else{
                
                    $news = Tec_Analysis::orderBy('view_count' , 'desc')->paginate(10);
                    
    
                }
            
            }
        }else{
            if(!is_null($search)){
            
                $news = Tec_Analysis::where('title' , 'like' , "%". $search ."%")->latest()->paginate(10);
                


            }else{

            
                $news = Tec_Analysis::orderBy('title')->paginate(10);
            
            }
        }

        $list = [];
    



        if(count($news) < 1)
        {
            $list['data'] = [];
            $list['current_page']                     = $news->toArray()['current_page'];
            $list['last_page']                        = $news->toArray()['last_page'];
        }

        foreach ($news as $key => $new)
        {
            $list['data'][$key]['id']          = $new->id;
            $list['data'][$key]['title']       = $new->title;
            $list['data'][$key]['image']       = URL::to('uploads/news/avatar/'.$new->image);
            $list['data'][$key]['created_at']  = Date::parse($new->created_at)->diffForHumans();
            $list['current_page']              = $news->toArray()['current_page'];
            $list['last_page']                 = $news->toArray()['last_page'];
            $list['first_page_url']            = $news->toArray()['first_page_url'];
            $list['next_page_url']             = $news->toArray()['next_page_url'];
            $list['last_page_url']             = $news->toArray()['last_page_url'];

        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    # show one analysis
    public function ShwOneanalysis()
    {

        $id = Input::get("id");
        $News = Tec_Analysis::where('id',$id)->first();

            
        # check News exist
        if(!$News)
        {
            $msg = 'analysis not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

    
        $list = [];

        
            $newss = Tec_Analysis::take(4)->inRandomOrder()->get();
    


        $list['id']          = $News->id;
        $list['title']       = $News->title;
        $list['image']       = URL::to('uploads/news/avatar/'.$News->image);
        $list['desc']        = $News->desc;
        $list['created_at']  = Date::parse($News->created_at)->diffForHumans();

        if(count($newss) == 0){
            $list['analysis'] = [];
        }else{
            foreach ($newss as $key => $new)
            {
                $list['analysis'][$key]['id']          = $new->id;
                $list['analysis'][$key]['title']       = $new->title;
                $list['analysis'][$key]['image']       = URL::to('uploads/news/avatar/'.$new->image);
                $list['analysis'][$key]['created_at']  = Date::parse($new->created_at)->diffForHumans();
            

            }
        }



        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    # show all bodcast
    public function bodcasts(Request $request)
    {

        $sort       = Input::get("sort");
        $search     = Input::get("search");


    



        if(!is_null($sort)){
            if($sort == 0){
                if(!is_null($search)){
                
                    $news = BodCast::where('title' , 'like' , "%". $search ."%")->orderBy('title')->paginate(10);
        

                }else{
                
                        $news = BodCast::orderBy('title')->paginate(10);
                
                    
                }
                
            }elseif($sort == 1){
                if(!is_null($search)){

                
                    $news = BodCast::where('title' , 'like' , "%". $search ."%")->orderBy('view_count' , 'desc')->paginate(10);
                


                }else{
                
                    $news = BodCast::orderBy('view_count' , 'desc')->paginate(10);
                    
    
                }
            
            }
        }else{
            if(!is_null($search)){
            
                $news = BodCast::where('title' , 'like' , "%". $search ."%")->latest()->paginate(10);
                


            }else{

            
                $news = BodCast::orderBy('title')->paginate(10);
            
            }
        }

        $list = [];
    



        if(count($news) < 1)
        {
            $list['data'] = [];
            $list['current_page']                     = $news->toArray()['current_page'];
            $list['last_page']                        = $news->toArray()['last_page'];
        }

        foreach ($news as $key => $new)
        {
            $list['data'][$key]['id']          = $new->id;
            $list['data'][$key]['title']       = $new->title;
            $list['data'][$key]['image']       = URL::to('uploads/news/avatar/'.$new->image);
            $list['data'][$key]['created_at']  = Date::parse($new->created_at)->diffForHumans();
            $list['current_page']              = $news->toArray()['current_page'];
            $list['last_page']                 = $news->toArray()['last_page'];
            $list['first_page_url']            = $news->toArray()['first_page_url'];
            $list['next_page_url']             = $news->toArray()['next_page_url'];
            $list['last_page_url']             = $news->toArray()['last_page_url'];

        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    # show one bodcast
    public function bodcast()
    {

        $id = Input::get("id");
        $News = BodCast::where('id',$id)->first();

            
        # check News exist
        if(!$News)
        {
            $msg = 'bodcast not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

    
        $list = [];

        
            $newss = BodCast::take(4)->inRandomOrder()->get();
    


        $list['id']          = $News->id;
        $list['title']       = $News->title;
        $list['file']        = URL::to('uploads/news/file/'.$News->file);
        $list['image']       = URL::to('uploads/news/avatar/'.$News->image);
        $list['desc']        = $News->desc;
        $list['created_at']  = Date::parse($News->created_at)->diffForHumans();

        if(count($newss) == 0){
            $list['analysis'] = [];
        }else{
            foreach ($newss as $key => $new)
            {
                $list['analysis'][$key]['id']          = $new->id;
                $list['analysis'][$key]['title']       = $new->title;
                $list['analysis'][$key]['image']       = URL::to('uploads/news/avatar/'.$new->image);
                $list['analysis'][$key]['created_at']  = Date::parse($new->created_at)->diffForHumans();
            

            }
        }



        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }
}
