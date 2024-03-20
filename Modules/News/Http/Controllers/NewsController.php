<?php

namespace Modules\News\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\News\Entities\News;
use Modules\News\Entities\News_images;
use Modules\News\Entities\News_Section;

use Modules\News\Entities\Multi_Sec;
use Session;
use Image;
use File;
class NewsController extends Controller
{
    use ApiResponse;
    # index
    public function Index()
    {
        $news = News::with('Section')->latest()->paginate(10);
        return view('news::news.news',compact('news'));
    }

    # add news page
    public function Addnews()
    {
        $sections = News_Section::latest()->get();
        return view('news::news.add_news',compact('sections'));
    }


    # add news page
    public function Addnewsm()
    {
        $sections = News_Section::latest()->get();
        return view('news::news.add_multi_news',compact('sections'));
    }

    # store news
    public function Storenews(Request $request)
    {
        $request->validate([
            'title'    => 'required',
            'desc'     => 'required',
            'image'    => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $news = new News;
        $news->title     = $request->title;
        $news->desc    = $request->desc;
        $news->section_id = $request->section_id;

        # upload image
        if(!is_null($request->image))
        {

//            $photo=$request->image;
//            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
//            Image::make($photo)->save('uploads/news/avatar/'.$name);
            $news->image = $this->storeImage($request->image,'news/avatar');
        }
        $news->save();



        MakeReport('بإضافة اخبار جديدة ' .$news->title);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # store news multi
    public function Storenewsm(Request $request)
    {
        $request->validate([
            'title'    => 'required',
            'desc'     => 'required',
            'image'    => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $news = new News;
        $news->title     = $request->title;
        $news->desc    = $request->desc;
        $news->section_id = $request->section_id;


        # upload image
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/news/avatar/'.$name);
            $news->image=$name;
        }
        $news->save();


        $datass  = News_Section::whereIn('id',$request->sections)->get();
        if($datass)
        {
            foreach($datass as $s){
                $secs = new Multi_Sec;
                $secs->new_id = $news->id;
                $secs->section_id = $s->id;
                $secs->save();
            }
        }



        MakeReport('بإضافة اخبار جديدة ' .$news->title);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # edit news
    public function Editnews($id)
    {
        $news = News::where('id',$id)->first();
        $sections = News_Section::latest()->get();
        $secs = Multi_Sec::where('new_id' , $id)->pluck('section_id')->toArray();
        if(!$secs){
            return view('news::news.edit_news',compact('news','sections'));
        }else{
            return view('news::news.edit_news_multi',compact('news','sections','secs'));
        }


    }

    # update news
    public function Updatenews(Request $request)
    {
        $request->validate([
            'title'    => 'required',
            'desc'     => 'required',
            'image'    => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        $news = News::where('id',$request->id)->first();
        $news->title     = $request->title;
        $news->desc    = $request->desc;
        $news->section_id = $request->section_id;

        if($request->meta){
            $news->meta    = $request->meta;
        }
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

    # update news
    public function Updatenewsm(Request $request)
    {
        $request->validate([
            'title'    => 'required',
            'desc'     => 'required',
            'image'    => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        $news = News::where('id',$request->id)->first();
        $news->title     = $request->title;
        $news->desc    = $request->desc;
        $news->section_id = $request->section_id;

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

        Multi_Sec::where('new_id',$news->id)->delete();
        $datass  = News_Section::whereIn('id',$request->sections)->get();
        if($datass)
        {
            foreach($datass as $s){
                $secs = new Multi_Sec;
                $secs->new_id = $news->id;
                $secs->section_id = $s->id;
                $secs->save();
            }
        }
        MakeReport('بتحديث اخبار ' .$news->title);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete news
    public function Deletenews(Request $request)
    {

        $news = News::where('id',$request->id)->first();

        MakeReport('بحذف خبر '.$news->title);
        $news->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # images
    public function storenewsImages(Request $request)
    {
        if($request->hasfile('images'))
        {
            foreach($request->images as $image){

                $photo=$image;
                $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
                Image::make($photo)->save('uploads/news/images/'.$name);

                $img = new News_images;
                $img->news_id = $request->id;
                $img->image = $name;
                $img->save();
            }
        }

        Session::flash('success','تم الاضافة');
        MakeReport('باضافة صور '.$img->image);
        return back();
    }

    # delete image
    public function DeletenewsImage(Request $request)
    {

        $image = News_images::where('id',$request->id)->first();
        if($image->image != 'default.png')
        {
            File::delete('uploads/news/images/'.$image->image);
        }
        MakeReport('بحذف الصورة '.$image->image);
        $image->delete();
        Session::flash('success','تم الحذف');
        return back();
    }


}