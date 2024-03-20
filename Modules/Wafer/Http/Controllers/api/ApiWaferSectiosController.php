<?php

namespace Modules\Wafer\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Wafer\Entities\Wafer_Section;
use Modules\Wafer\Entities\Wafer_Farmer;
use Modules\Wafer\Entities\Wafer_Post;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;

class ApiWaferSectiosController extends Controller
{

    # show all sections
    public function showsections()
    {
        $sections = Wafer_Section::with('WaferFarmers','WaferPosts')->latest()->get();

        $list = [];

        foreach ($sections as $key => $section)
        {
            $list[$key]['id']                  = $section->id;
            $list[$key]['name']                = $section->name;
            $list[$key]['farmers_count']       = count($section->WaferFarmers);
            $list[$key]['posts_count']         = count($section->WaferPosts);
            $list[$key]['image']               = URL::to('uploads/sections/avatar/'.$section->image);
            $list[$key]['created_at']          = Date::parse($section->created_at)->diffForHumans();

        }

        return response()->json([
            'status'   => '1',
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show one section
    public function showsection($id)
    {
        $section = Wafer_Section::where('id',$id)->first();
        $farmers = Wafer_Farmer::with('Section','WaferPosts')->where('section_id',$id)->latest()->paginate(20);
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

        $list = [];
        if(count($farmers) < 1)
        {
            $list['data'] = [];
            $list['current_page']                     = $farmers->toArray()['current_page'];
            $list['last_page']                        = $farmers->toArray()['last_page'];
        }

        foreach ($farmers as $key => $farmer)
        {
            $list['data'][$key]['id']                  = $farmer->id;
            $list['data'][$key]['name']                = $farmer->name;
            $list['data'][$key]['email']               = $farmer->email;
            $list['data'][$key]['address']             = $farmer->address;
            $list['data'][$key]['farm_name']           = $farmer->farm_name;
            $list['data'][$key]['Section']             = $farmer->Section->name;
            $list['data'][$key]['posts_count']         = count($farmer->WaferPosts);
            $list['data'][$key]['image']               = URL::to('uploads/farmers/avatar/'.$farmer->avatar);
            $list['data'][$key]['created_at']          = Date::parse($farmer->created_at)->diffForHumans();

            $list['current_page']             = $farmers->toArray()['current_page'];
            $list['last_page']                = $farmers->toArray()['last_page'];
            $list['first_page_url']           = $farmers->toArray()['first_page_url'];
            $list['next_page_url']            = $farmers->toArray()['next_page_url'];
            $list['last_page_url']            = $farmers->toArray()['last_page_url'];

        }

        return response()->json([
            'status'   => '1',
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }
    

    # show posts
    public function showposts($id)
    {
        $section = Wafer_Section::where('id',$id)->first();
        $posts = Wafer_Post::with('Section','WaferFarmer')->where('section_id',$id)->latest()->paginate(20);
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

        $list = [];
        if(count($posts) < 1)
        {
            $list['data'] = [];
            $list['current_page']                     = $posts->toArray()['current_page'];
            $list['last_page']                        = $posts->toArray()['last_page'];
        }

        foreach ($posts as $key => $post)
        {
            $list['data'][$key]['id']                  = $post->id;
            $list['data'][$key]['item_type']           = $post->item_type;
            $list['data'][$key]['item_age']            = $post->item_age;
            $list['data'][$key]['price']               = $post->price;
            $list['data'][$key]['address']             = $post->address;
            $list['data'][$key]['Section']             = $post->Section->name;
            $list['data'][$key]['farmer']              = $post->WaferFarmer->name;
            $list['data'][$key]['created_at']          = Date::parse($post->created_at)->diffForHumans();

            $list['current_page']             = $posts->toArray()['current_page'];
            $list['last_page']                = $posts->toArray()['last_page'];
            $list['first_page_url']           = $posts->toArray()['first_page_url'];
            $list['next_page_url']            = $posts->toArray()['next_page_url'];
            $list['last_page_url']            = $posts->toArray()['last_page_url'];

        }

        return response()->json([
            'status'   => '1',
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }
    
   
}
