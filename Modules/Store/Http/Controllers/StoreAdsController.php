<?php

namespace Modules\Store\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Store\Entities\Customer;
use Modules\Store\Entities\Notification_Ads_Users;
use Modules\Store\Entities\Store_Ads;
use Modules\Store\Entities\Store_Ads_images;
use Modules\Store\Entities\Store_Ads_Comment;
use Modules\Store\Entities\Store_Section;
use Session;
use Image;
use File;
use Auth;

class StoreAdsController extends Controller
{
   # index
   public function Index()
   {
       $stores = Store_Ads::with('Customer','User')->latest()->get();
       return view('store::stores.stores',compact('stores'));
   }

   # add ads page
   public function Addstoreads()
   {
       $sections = Store_Section::get();
       return view('store::stores.add_stores',compact('sections'));
   }

   # store ads 
   public function Storestoreads(Request $request)
   {
       $request->validate([
           'title'    => 'required',
           'desc'     => 'required',
           'phone'     => 'required',
           'salary'     => 'required',
       ]);

       $store = new Store_Ads;
       $store->title     = $request->title;
       $store->desc    = $request->desc;
       $store->phone    = $request->phone;
       $store->address    = $request->address;
       $store->con_type    = $request->con_type;
       $store->salary    = $request->salary;
       $store->section_id    = $request->section_id;
       $store->admin_id    = Auth::user()->id;
       $store->save();

       if($request->hasfile('image'))
       {
            foreach($request->image as $image){

            $photo=$image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/stores/alboum/'.$name);
            
                $img = new Store_Ads_images;
                $img->ads_id = $store->id;
                $img->image = $name;
                $img->save();
            }
        }
       MakeReport('بإضافة  اعلان في المتجر ' .$store->title);
       Session::flash('success','تم الحفظ');
       return redirect()->route('Editstoreads',$store->id);
   }

   # edit ads
   public function Editstoreads($id)
   {
       $store = Store_Ads::with('StoreAdsimages','StoreAdsComments')->where('id',$id)->first();
       $sections = Store_Section::get();
       return view('store::stores.edit_stores',compact('store','sections'));
   }

   # update ads
    public function Updatestoreads(Request $request)
    {
        $request->validate([
            'title'      => 'required',
            'desc'       => 'required',
            'phone'     => 'required',
            'salary'     => 'required',
            'approved'   => 'required'
        ]);

        $store = Store_Ads::where('id',$request->id)->first();
        $store->title     = $request->title;
        $store->desc    = $request->desc;
        $store->address    = $request->address;
        $store->con_type    = $request->con_type;
        $store->phone    = $request->phone;
        $store->salary    = $request->salary;
        $store->section_id    = $request->section_id;
        $store->approved    = $request->approved;
        $store->message    = $request->message;

        $store->save();

        if($request->approved == '1'){
            $noty = new Notification_Ads_Users;
            $noty->user_id     = $store->user_id;
            $noty->notification_ads_id      = 2;
            $noty->ads_id      = $store->id;
            $noty->save();
        } else if($request->approved == '0'){
            $noty = new Notification_Ads_Users;
            $noty->user_id     = $store->user_id;
            $noty->notification_ads_id      = 3;
            $noty->ads_id      = $store->id;
            $noty->save();
        }
        MakeReport('بتحديث اعلان في المتجر ' .$store->title);
        Session::flash('success','تم الحفظ');
        return back();
    }

   # delete ads
   public function Deletestoreads(Request $request)
   {

       $store = Store_Ads::where('id',$request->id)->first();

       MakeReport('بحذف اعلان '.$store->title);
       $store->delete();
       Session::flash('success','تم الحذف');
       return back();
   }
   
    # images 
    public function storeImages(Request $request)
    {
        if($request->hasfile('image'))
        {
        foreach($request->image as $image){

        $photo=$image;
        $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
        Image::make($photo)->save('uploads/stores/alboum/'.$name);
        
            $img = new Store_Ads_images;
            $img->ads_id = $request->id;
            $img->image = $name;
            $img->save();
        }
    }

        Session::flash('success','تم الاضافة');
        MakeReport(' باضافة صور لاعلان ');
        return back();
    }

    # delete image
    public function DeleteImage(Request $request)
    {

        $image = Store_Ads_images::where('id',$request->id)->first();
        if($image->image != 'default.png')
        {
                File::delete('uploads/stores/alboum/'.$image->image);
        }
        MakeReport('بحذف الصورة ');
        $image->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
    
    # delete comment
    public function Deletecomment(Request $request)
    {

        $comment = Store_Ads_Comment::where('id',$request->id)->first();
        MakeReport('بحذف التعليق ');
        $comment->delete();
        Session::flash('success','تم الحذف');
        return back();
    }	
 


}
