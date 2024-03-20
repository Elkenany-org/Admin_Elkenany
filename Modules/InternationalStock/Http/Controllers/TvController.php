<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InternationalStock\Entities\Tv;
use Session;
use Image;
use File;
class TvController extends Controller
{
    # index
   public function Index()
   {
       $news = Tv::latest()->get();
       return view('internationalstock::tvs.tvs',compact('news'));
   }

   # add news page
   public function Addnews()
   {
 
       return view('internationalstock::tvs.add_tvs');
   }

   # store news 
   public function Storenews(Request $request)
   {
       $request->validate([
           'title'    => 'required',
           'desc'     => 'required',
           'link'     => 'required',
           'image'    => 'required|mimes:jpeg,png,jpg,gif,svg',
       ]);

       $news = new Tv;
       $news->title     = $request->title;
       $news->desc    = $request->desc;
       $news->link    = $request->link;

        # upload image
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/news/avatar/'.$name);
            $news->image=$name;
        }
        $news->save();
     

       
       MakeReport('بإضافة  تقرير جديدة ' .$news->title);
       Session::flash('success','تم الحفظ');
       return back();
   }

   # edit news
   public function Editnews($id)
   {
       $news = Tv::where('id',$id)->first();
       return view('internationalstock::tvs.edit_tvs',compact('news'));
   }

   # update news
   public function Updatenews(Request $request)
   {
       $request->validate([
        'title'    => 'required',
        'desc'     => 'required',
        'link'     => 'required',
        'image'    => 'mimes:jpeg,png,jpg,gif,svg',
       ]);

       $news = Tv::where('id',$request->id)->first();
       $news->title     = $request->title;
       $news->desc    = $request->desc;
       $news->link    = $request->link;

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

       $news->save();
       MakeReport('بتحديث  تقرير ' .$news->title);
       Session::flash('success','تم الحفظ');
       return back();
   }

   # delete news
   public function Deletenews(Request $request)
   {

       $news = Tv::where('id',$request->id)->first();

       MakeReport('بحذف  تقرير '.$news->title);
       $news->delete();
       Session::flash('success','تم الحذف');
       return back();
   }

  
}
