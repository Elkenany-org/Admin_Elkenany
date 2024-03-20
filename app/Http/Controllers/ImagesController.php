<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Images_Home;
use Session;
use View;
use Image;
use File;

class ImagesController extends Controller
{
    # index
    public function index()
    {
        $gallary = Images_Home::latest()->get();
        return view('sections.gallary',compact('gallary'));
    }

    # Storehomeimage
    public function Storehomeimage(Request $request)
    {
       
        if($request->hasfile('images'))
        {
            foreach($request->images as $image){

            $photo=$image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/main/'.$name);
            
                $img = new Images_Home;
                $img->image = $name;
                $img->save();
            }
        }

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة  صور رئيسية ');
        return back();
    }

    # delete image
    public function DeleteImage(Request $request)
    {

        $image = Images_Home::where('id',$request->id)->first();
   
        File::delete('uploads/main/'.$image->image);
        
        MakeReport('بحذف الصورة ');
        $image->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
 
}
