<?php

namespace Modules\Wafer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wafer\Entities\Wafer_Section;
use Modules\Wafer\Entities\Wafer_Farmer;
use Modules\Wafer\Entities\Wafer_Post;
use Modules\Wafer\Entities\Wafer_Order;
use Modules\Wafer\Entities\Wafer_Farmer_Order;
use Modules\Wafer\Entities\Wafer_Farmer_Image;
use Session;
use Image;
use File;
use View;

class FarmersController extends Controller
{
    # farmers
    public function Index()
    {
        $farmers = Wafer_Farmer::with('Section')->latest()->get();
        return view('wafer::farmers.farmers',compact('farmers'));
    }

    # add farmer page
    public function Addfarmer()
    {
        $sections = Wafer_Section::get();
        return view('wafer::farmers.add_farmers',compact('sections'));
    }

    # store farmer 
    public function Storefarmer(Request $request)
    {
        $request->validate([
            'name'            => 'required',
            'email'           => 'required|unique:wafer_farmers',
            'phone'           => 'required|unique:wafer_farmers',
            'password'        => 'required',
            'avatar'          => 'nullable|mimes:jpeg,png,jpg,gif,svg',
            'address'         => 'required',
            'farm_name'       => 'required',
            'section_id'      => 'required',
        ]);

        $farmer = new Wafer_Farmer;
        $farmer->name                = $request->name;
        $farmer->email               = $request->email;
        $farmer->phone               = $request->phone;
        $farmer->password            = bcrypt($request->password);
        $farmer->address             = $request->address;
        $farmer->latitude            = $request->latitude ;
        $farmer->longitude           = $request->longitude ;
        $farmer->section_id          = $request->section_id;
        $farmer->farm_name           = $request->farm_name;

        # upload avatar
        if(!is_null($request->avatar))
        {
            $photo=$request->avatar;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/farmers/avatar/'.$name);
            $farmer->avatar=$name;
        }
        $farmer->save();

        if($request->hasfile('images'))
        {
            foreach($request->images as $image)
            {

            $photo=$image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/farmers/images/'.$name);
            
                $img = new Wafer_Farmer_Image;
                $img->farm_id = $farmer->id;
                $img->image = $name;
                $img->save();
            }
        }
        MakeReport('بإضافة مزارع جديد ' .$farmer->name);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # edit farmer
    public function Editfarmer($id)
    {
        $farmer   = Wafer_Farmer::with('Section','WaferFarmerImages')->where('id',$id)->first();
        $sections = Wafer_Section::get();
        return view('wafer::farmers.edit_farmers',compact('farmer','sections'));
    }

    # edit farmer
    public function showfarmerpost($id)
    {
        $farmer   = Wafer_Farmer::with('WaferPosts.Section')->where('id',$id)->first();
        return view('wafer::farmers.posts',compact('farmer'));
    }

    # update farmer
    public function Updatefarmer(Request $request)
    {
        $request->validate([
            'name'            => 'required',
            'email'           => 'required|unique:wafer_farmers,email,'.$request->id,
            'phone'           => 'required|unique:wafer_farmers,phone,'.$request->id,
            'avatar'          => 'nullable|mimes:jpeg,png,jpg,gif,svg',
            'address'         => 'required',
            'farm_name'       => 'required',
            'section_id'      => 'required',
        ]);

        $farmer = Wafer_Farmer::where('id',$request->id)->first();
        $farmer->name                = $request->name;
        $farmer->email               = $request->email;
        $farmer->phone               = $request->phone;
        $farmer->password            = bcrypt($request->password);
        $farmer->address             = $request->address;
        $farmer->latitude            = $request->latitude ;
        $farmer->longitude           = $request->longitude ;
        $farmer->section_id          = $request->section_id;
        $farmer->farm_name           = $request->farm_name;

        # password
        if(!is_null($request->password))
        {
            $farmer->password = bcrypt($request->password);
        }

        # upload avatar
        if(!is_null($request->avatar))
        {
            # delete avatar
            if($farmer->avatar != 'default.png')
            {
                    File::delete('uploads/farmers/avatar/'.$farmer->avatar);
            }

            # upload new avatar
            $photo=$request->avatar;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/farmers/avatar/'.$name);
            $farmer->avatar=$name;
        }

        $farmer->save();
        MakeReport('بتحديث مزارع ' .$farmer->name);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # images 
    public function storeImagesfarmer(Request $request)
    {
        if($request->hasfile('image'))
        {
        foreach($request->image as $image){

        $photo=$image;
        $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
        Image::make($photo)->save('uploads/farmers/images/'.$name);
        
            $img = new Wafer_Farmer_Image;
            $img->farm_id = $request->id;
            $img->image = $name;
            $img->save();
        }
    }

        Session::flash('success','تم الاضافة');
        MakeReport(' باضافة صور لاعلان ');
        return back();
    }

    # delete image
    public function DeleteImagefarmer(Request $request)
    {

        $image = Wafer_Farmer_Image::where('id',$request->id)->first();
        File::delete('uploads/farmers/images/'.$image->image);
        MakeReport('بحذف الصورة ');
        $image->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # delete farmer
    public function Deletefarmer(Request $request)
    {

        $farmer = Wafer_Farmer::where('id',$request->id)->first();
        if($farmer->avatar != 'default.png')
        {
                File::delete('uploads/farmers/avatar/'.$farmer->avatar);
        }
        MakeReport('بحذف مزارع '.$farmer->name);
        $farmer->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # posts
    public function posts()
    {
        $posts = Wafer_Post::with('Section','WaferFarmer')->latest()->get();
        return view('wafer::posts.posts',compact('posts'));
    }

    # delete posts
    public function Deletepost(Request $request)
    {

        $post = Wafer_Post::where('id',$request->id)->first();
        MakeReport('بحذف منشور '.$post->item_type);
        $post->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # show post
    public function Showpost($id)
    {
        $post = Wafer_Post::with('Section','WaferFarmer','WaferOrders.WaferFarmer','WaferOrders.Customer')->where('id',$id)->first();

        return view('wafer::posts.post_show',compact('post'));
    }

    # delete order
    public function Deleteorder(Request $request)
    {

        $order = Wafer_Order::where('id',$request->id)->first();
        MakeReport('بحذف طلب ');
        $order->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # show order
    public function Showorder($id)
    {
        $order = Wafer_Order::with('WaferPost','Customer','WaferFarmer','WaferCars')->where('id',$id)->first();

        return view('wafer::posts.order_show',compact('order'));
    }

    # farmer order
    public function ordersfarmer()
    {
        $orders = Wafer_Farmer_Order::with('WaferFarmer')->latest()->get();
        return view('wafer::posts.farmer_order',compact('orders'));
    }

    # update order farmer
    public function Updatefarmerorder(Request $request)
    {

        $order = Wafer_Farmer_Order::where('id',$request->edit_id)->first();
        
        $order->management_response = $request->management_response;
        $order->status  = 1;
        $order->save();
        MakeReport('بتحديث طلب ' .$order->title);
        Session::flash('success','تم الحفظ');
        return back();
    }


    # delete order
    public function Deletefarmerorder(Request $request)
    {

        $order = Wafer_Farmer_Order::where('id',$request->id)->first();
        MakeReport('بحذف طلب ');
        $order->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
  
}
