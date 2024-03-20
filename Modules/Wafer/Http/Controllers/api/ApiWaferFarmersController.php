<?php

namespace Modules\Wafer\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Wafer\Entities\Wafer_Section;
use Modules\Wafer\Entities\Wafer_Farmer;
use Modules\Wafer\Entities\Wafer_Post;
use Modules\Wafer\Entities\Wafer_Order;
use Modules\Wafer\Entities\Wafer_Farmer_Order;
use Modules\Wafer\Entities\Wafer_Car;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;

class ApiWaferFarmersController extends Controller
{
    # show one farmer
    public function showfarmer($id)
    {
        $farmer = Wafer_Farmer::with('Section','WaferFarmerImages','WaferPosts')->where('id',$id)->first();
        # check farmer exist
        if(!$farmer)
        {
            $msg = 'farmer not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $list = [];
        $img = [];

        $list['id']                  = $farmer->id;
        $list['name']                = $farmer->name;
        $list['email']               = $farmer->email;
        $list['address']             = $farmer->address;
        $list['Section']             = $farmer->Section->name;
        $list['farm_name']           = $farmer->farm_name;
        $list['latitude']            = $farmer->latitude;
        $list['longitude']           = $farmer->longitude;
        $list['phone']               = $farmer->phone;
        $list['posts_count']         = count($farmer->WaferPosts);
        $list['image']               = URL::to('uploads/farmers/avatar/'.$farmer->avatar);
        $list['created_at']          = Date::parse($farmer->created_at)->diffForHumans();
        // images
        foreach ($farmer->WaferFarmerImages as $ke => $image)
        {
            $img[$ke]['id']         = $image->id;
            $img[$ke]['image']      = URL::to('uploads/farmers/avatar/'.$image->image);
        }

        $list['images']         = $img;

        return response()->json([
            'status'   => '1',
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show posts
    public function showfposts($id)
    {
        $farmer = Wafer_Farmer::where('id',$id)->first();
        $posts = Wafer_Post::with('Section','WaferFarmer')->where('farm_id',$id)->latest()->paginate(20);
        # check farmer exist
        if(!$farmer)
        {
            $msg = 'farmer not found';
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

    # show post
    public function showpost($id)
    {
        $post = Wafer_Post::with('Section','WaferFarmer')->where('id',$id)->first();
        # check post exist
        if(!$post)
        {
            $msg = 'post not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $list = [];
       
        $list['id']                  = $post->id;
        $list['item_type']           = $post->item_type;
        $list['item_age']            = $post->item_age;
        $list['price']               = $post->price;
        $list['status']              = $post->status;
        $list['address']             = $post->address;
        $list['latitude']            = $post->latitude;
        $list['longitude']           = $post->longitude;
        $list['date_of_sale']        = $post->date_of_sale;
        $list['average_weight']      = $post->average_weight;
        $list['Section']             = $post->Section->name;
        $list['farmer']              = $post->WaferFarmer->name;
        $list['created_at']          = Date::parse($post->created_at)->diffForHumans();


        return response()->json([
            'status'   => '1',
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # add post
    public function addpost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_type'     => 'required',
            'item_age'      => 'required',
            'price'         => 'required',
            'average_weight'=> 'required',
            'longitude'     => 'required',
            'latitude'      => 'required',
            'address'       => 'required',
            'date_of_sale'  => 'required',
            'section_id'    => 'required',
        ]);

        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['item_type']))
            {
                $msg = 'item_type is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['item_age']))
            {
                $msg = 'item_age is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['price']))
            {
                $msg = 'price is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['average_weight']))
            {
                $msg = 'average_weight is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['longitude']))
            {
                $msg = 'longitude is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['latitude']))
            {
                $msg = 'latitude is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['address']))
            {
                $msg = 'address is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['date_of_sale']))
            {
                $msg = 'date_of_sale is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['section_id']))
            {
                $msg = 'section_id is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        # create new post
        $post = new Wafer_Post;
        $post->farm_id         = 1;
        $post->item_type       = $request->item_type;
        $post->item_age        = $request->item_age;
        $post->price           = $request->price;
        $post->average_weight  = $request->average_weight;
        $post->longitude       = $request->longitude;
        $post->latitude        = $request->latitude;
        $post->address         = $request->address;
        $post->date_of_sale    = $request->date_of_sale;
        $post->section_id      = $request->section_id;
        $post->save();


        
        return response()->json([
            'status'   => '1',
            'message'  => null,
            'error'    => null,
        ],200);

    }

    # show all order to post
    public function showorders($id)
    {
        $post = Wafer_Post::with('Section','WaferOrders.WaferFarmer','WaferOrders.Customer')->where('id',$id)->first();
        # check post exist
        if(!$post)
        {
            $msg = 'post not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $list = [];
        // orders
        foreach ($post->WaferOrders as $ke => $order)
        {
            $list[$ke]['id']             = $order->id;
            $list[$ke]['user']           = $order->Customer->name;
            $list[$ke]['farm_name']      = $order->WaferFarmer->farm_name;
            $list[$ke]['status']         = $order->status;
            $list[$ke]['created_at']     = Date::parse($order->created_at)->diffForHumans();
        }


        return response()->json([
            'status'   => '1',
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show order to post
    public function showorder($id)
    {
        $order = Wafer_Order::with('WaferCars','WaferPost','WaferFarmer','Customer')->where('id',$id)->first();
        # check order exist
        if(!$order)
        {
            $msg = 'order not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $push = [];
        $list = [];

        $push['id']             = $order->id;
        $push['user']           = $order->Customer->name;
        $push['farm_name']      = $order->WaferFarmer->farm_name;
        $push['post']           = $order->WaferPost->id;
        $push['status']         = $order->status;
        $push['created_at']     = Date::parse($order->created_at)->diffForHumans();
        // cars
        foreach ($order->WaferCars as $ke => $car)
        {
            $list[$ke]['id']             = $car->id;
            $list[$ke]['name']           = $car->name;
            $list[$ke]['order']          = $order->id;
            $list[$ke]['phone']          = $car->phone;
            $list[$ke]['car_id']         = $car->car_id;
        }


        return response()->json([
            'status'   => '1',
            'message'  => null,
            'error'    => null,
            'data'     => $push
        ],200);

    }

    # add order to mangement
    public function addorderm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'content'   => 'required',
        ]);

        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['title']))
            {
                $msg = 'title is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['content']))
            {
                $msg = 'content is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        # create new order
        $order = new Wafer_Farmer_Order;
        $order->farm_id     = 1;
        $order->title       = $request->title;
        $order->content     = $request->content;
        $order->save();


        
        return response()->json([
            'status'   => '1',
            'message'  => null,
            'error'    => null,
        ],200);

    }

    # show all order management
    public function showmanageorders()
    {
        $orders = Wafer_Farmer_Order::with('WaferFarmer')->where('farm_id',1)->latest()->get();

        $list = [];

        foreach ($orders as $key => $order)
        {
            $list[$key]['id']                    = $order->id;
            $list[$key]['title']                 = $order->title;
            $list[$key]['content']               = $order->content;
            $list[$key]['management_response']   = $order->management_response;
            $list[$key]['farmer']                = $order->WaferFarmer->name;
            $list[$key]['status']                = $order->status;
            $list[$key]['created_at']            = Date::parse($order->created_at)->diffForHumans();

        }

        return response()->json([
            'status'   => '1',
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # add order to post
    public function addorderp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farm_id'  => 'required',
            'post_id'  => 'required',
            'cars'     => 'required',
        ]);

        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['farm_id']))
            {
                $msg = 'farm_id is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['post_id']))
            {
                $msg = 'post_id is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['cars']))
            {
                $msg = 'cars is required';
                return response()->json([
                    'status'   => '0',
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }
        # create new order
        $order = new Wafer_Order;
        $order->user_id       = 1;
        $order->farm_id       = $request->farm_id;
        $order->post_id       = $request->post_id;
        $order->save();


        $cars = json_decode($request->cars);
        if(!is_array($cars))
        {
            $msg = 'data must be an array';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }
        foreach($cars as $car){

            foreach($car as $value){

        
                $carr = new Wafer_Car;
                $carr->name        = $value->name;
                $carr->car_id      = $value->car_id;
                $carr->phone       = $value->phone;
                $carr->order_id    = $order->id;
                $carr->save();
            }
        }

        
        return response()->json([
            'status'   => '1',
            'message'  => null,
            'error'    => null,
        ],200);

    }

    
   
}
