<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InternationalStock\Entities\Tec_Analysis;
use Session;
use Image;
use File;
class TecAnalysisController extends Controller
{
    # index
   public function Index()
   {
       $news = Tec_Analysis::latest()->get();
       return view('internationalstock::analysis.analysis',compact('news'));
   }

   # add news page
   public function Addnews()
   {
 
       return view('internationalstock::analysis.add_analysis');
   }

   # store news 
   public function Storenews(Request $request)
   {
       $request->validate([
           'title'    => 'required',
           'desc'     => 'required',
           'image'    => 'required|mimes:jpeg,png,jpg,gif,svg',
       ]);

       $news = new Tec_Analysis;
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
     

       
       MakeReport('بإضافة تحليل فني جديدة ' .$news->title);
       Session::flash('success','تم الحفظ');
       return back();
   }

   # edit news
   public function Editnews($id)
   {
       $news = Tec_Analysis::where('id',$id)->first();
       return view('internationalstock::analysis.edit_analysis',compact('news'));
   }

   # update news
   public function Updatenews(Request $request)
   {
       $request->validate([
        'title'    => 'required',
        'desc'     => 'required',
        'image'    => 'mimes:jpeg,png,jpg,gif,svg',
       ]);

       $news = Tec_Analysis::where('id',$request->id)->first();
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
       MakeReport('بتحديث تحليل فني ' .$news->title);
       Session::flash('success','تم الحفظ');
       return back();
   }

   # delete news
   public function Deletenews(Request $request)
   {

       $news = Tec_Analysis::where('id',$request->id)->first();

       MakeReport('بحذف تحليل فني '.$news->title);
       $news->delete();
       Session::flash('success','تم الحذف');
       return back();
   }

  
}
