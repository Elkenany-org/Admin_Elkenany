<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InternationalStock\Entities\Inter_Rep;
use Session;
use Image;
use File;
class InterRepController extends Controller
{
    # index
   public function Index()
   {
       $news = Inter_Rep::latest()->get();
       return view('internationalstock::reports.reports',compact('news'));
   }

   # add news page
   public function Addnews()
   {
 
       return view('internationalstock::reports.add_reports');
   }

   # store news 
   public function Storenews(Request $request)
   {
       $request->validate([
           'title'    => 'required',
           'desc'     => 'required',
           'image'    => 'required|mimes:jpeg,png,jpg,gif,svg',
       ]);

       $news = new Inter_Rep;
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
        $news->save();
     

       
       MakeReport('بإضافة  تقرير جديدة ' .$news->title);
       Session::flash('success','تم الحفظ');
       return back();
   }

   # edit news
   public function Editnews($id)
   {
       $news = Inter_Rep::where('id',$id)->first();
       return view('internationalstock::reports.edit_reports',compact('news'));
   }

   # update news
   public function Updatenews(Request $request)
   {
       $request->validate([
        'title'    => 'required',
        'desc'     => 'required',
        'image'    => 'mimes:jpeg,png,jpg,gif,svg',
       ]);

       $news = Inter_Rep::where('id',$request->id)->first();
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

       $news->save();
       MakeReport('بتحديث  تقرير ' .$news->title);
       Session::flash('success','تم الحفظ');
       return back();
   }

   # delete news
   public function Deletenews(Request $request)
   {

       $news = Inter_Rep::where('id',$request->id)->first();

       MakeReport('بحذف  تقرير '.$news->title);
       $news->delete();
       Session::flash('success','تم الحذف');
       return back();
   }

  
}
