<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InternationalStock\Entities\L_news;
use Session;
use Image;
use File;
class LatestNewsController extends Controller
{
    # index
   public function Index()
   {
       $news = L_news::latest()->get();
       return view('internationalstock::news.news',compact('news'));
   }

   # add news page
   public function Addnews()
   {
 
       return view('internationalstock::news.add_news');
   }

   # store news 
   public function Storenews(Request $request)
   {
       $request->validate([
           'title'    => 'required',
           'desc'     => 'required',
           'image'    => 'required|mimes:jpeg,png,jpg,gif,svg',
       ]);

       $news = new L_news;
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
     

       
       MakeReport('بإضافة اخبار جديدة ' .$news->title);
       Session::flash('success','تم الحفظ');
       return back();
   }

   # edit news
   public function Editnews($id)
   {
       $news = L_news::where('id',$id)->first();
       return view('internationalstock::news.edit_news',compact('news'));
   }

   # update news
   public function Updatenews(Request $request)
   {
       $request->validate([
        'title'    => 'required',
        'desc'     => 'required',
        'image'    => 'mimes:jpeg,png,jpg,gif,svg',
       ]);

       $news = L_news::where('id',$request->id)->first();
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
       MakeReport('بتحديث اخبار ' .$news->title);
       Session::flash('success','تم الحفظ');
       return back();
   }

   # delete news
   public function Deletenews(Request $request)
   {

       $news = L_news::where('id',$request->id)->first();

       MakeReport('بحذف خبر '.$news->title);
       $news->delete();
       Session::flash('success','تم الحذف');
       return back();
   }

  
}
