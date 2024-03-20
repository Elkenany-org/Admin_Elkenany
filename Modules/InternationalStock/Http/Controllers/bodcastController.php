<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InternationalStock\Entities\BodCast;
use Session;
use Image;
use File;
class bodcastController extends Controller
{
    # index
   public function Index()
   {
       $news = BodCast::latest()->get();
       return view('internationalstock::bodcast.bodcast',compact('news'));
   }

   # add news page
   public function Addnews()
   {
 
       return view('internationalstock::bodcast.add_bodcast');
   }

   # store news 
   public function Storenews(Request $request)
   {
       $request->validate([
           'title'    => 'required',
           'desc'     => 'required',
           'image'    => 'required|mimes:jpeg,png,jpg,gif,svg',
           'file' => 'required|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav',
       ]);

       $news = new BodCast;
       $news->title     = $request->title;
       $news->desc    = $request->desc;

        # upload image
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/news/avatar/'.$name);
            $news->image=$name;
        }

        # upload image
        if(!is_null($request->file))
        {
            $fo=$request->file;
           
            $namef =date('d-m-y').time().rand().'.'.$fo->getClientOriginalExtension();
            $destinationPath = 'uploads/news/file/';
            $fo->move($destinationPath, $namef);
            $news->file= $namef;
        }
        $news->save();
     

       
       MakeReport('بإضافة  بودكاست جديدة ' .$news->title);
       Session::flash('success','تم الحفظ');
       return back();
   }

   # edit news
   public function Editnews($id)
   {
       $news = BodCast::where('id',$id)->first();
       return view('internationalstock::bodcast.edit_bodcast',compact('news'));
   }

   # update news
   public function Updatenews(Request $request)
   {
       $request->validate([
        'title'    => 'required',
        'desc'     => 'required',
        'image'    => 'mimes:jpeg,png,jpg,gif,svg',
       ]);

       $news = BodCast::where('id',$request->id)->first();
       $news->title     = $request->title;
       $news->desc    = $request->desc;

        # upload avatar
        if(!is_null($request->image))
        {
 
            File::delete('uploads/news/avatar/'.$news->image);
            # upload new image
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/news/avatar/'.$name);
            $news->image=$name;
        }

        # upload image
        if(!is_null($request->file))
        {
            $fo=$request->file;
           
            $namef =date('d-m-y').time().rand().'.'.$fo->getClientOriginalExtension();
            $destinationPath = 'uploads/news/file/';
            $fo->move($destinationPath, $namef);
            $news->file= $namef;
        }

       $news->save();
       MakeReport('بتحديث  بودكاست ' .$news->title);
       Session::flash('success','تم الحفظ');
       return back();
   }

   # delete news
   public function Deletenews(Request $request)
   {

       $news = BodCast::where('id',$request->id)->first();

       MakeReport('بحذف  بودكاست '.$news->title);
       $news->delete();
       Session::flash('success','تم الحذف');
       return back();
   }

  
}
