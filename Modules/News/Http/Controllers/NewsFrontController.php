<?php

namespace Modules\News\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\News\Entities\News_Section;
use Modules\News\Entities\News;
use Modules\News\Entities\News_images;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Modules\News\Entities\Multi_Sec;
use Session;
use Image;
use File;
use View;

class NewsFrontController extends Controller
{

    # news
    public function news(Request $request, $name)
    {

        $section = News_Section::with('News')->where('type',$name)->first();
        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','news')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
        if(count($majs) == 0)
        {
            $news = News::where('section_id',$section->id)->orderby('title')->paginate(10);
        }else{
            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->orderby('title')->paginate(10);
        }

        $secs = News_Section::get();
        $sort= '0';
        return view('news::fronts.news',compact('news','section','secs','sort','ads','logos'));
    }

    # news
    public function newssort(Request $request, $name)
    {

        $section = News_Section::with('News')->where('type',$name)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','news')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
        if(count($majs) == 0)
        {
            $news = News::where('section_id',$section->id)->orderby('view_count' , 'desc')->paginate(10);
        }else{
            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->orderby('view_count' , 'desc')->paginate(10);
        }


        $secs = News_Section::get();
        $sort= '1';
        return view('news::fronts.news',compact('news','section','secs','sort','ads','logos'));
    }

    # news
    public function newslast(Request $request, $name)
    {

        $section = News_Section::with('News')->where('type',$name)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','news')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
        if(count($majs) == 0)
        {
            $news = News::where('section_id',$section->id)->latest()->paginate(10);
        }else{
            $news = News::where('section_id',$section->id)->orWhereIn('id',$majs)->latest()->paginate(10);
        }


        $secs = News_Section::get();
        $sort= '2';
        return view('news::fronts.news',compact('news','section','secs','sort','ads','logos'));
    }

    # datas
    public function datas(Request $request)
    {

        $section = News_Section::where('id',$request->id)->first();
        $limit = 5;
        $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
        if(count($majs) == 0)
        {
            $datas = News::where('section_id',$section->id)->orderby('title')->take($limit)->get();
        }else{
            $datas = News::where('section_id',$section->id)->orWhereIn('id',$majs)->take($limit)->get();
        }

        $id = $request->id;


        return response()->json(['datas'=>$datas, 'limit'=>$limit, 'id'=>$id]);

    }

    # mores
    public function mores(Request $request)
    {

        $section = News_Section::where('id',$request->id)->first();
        $count = $request->count;
        $limit = $count + 5;

        $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();
        if(count($majs) == 0)
        {
            $datas = News::where('section_id',$section->id)->orderby('title')->skip($count)->take(5)->get();
        }else{
            $datas = News::where('section_id',$section->id)->orWhereIn('id',$majs)->skip($count)->take(5)->get();
        }

        $id = $request->id;


        return response()->json(['datas'=>$datas, 'limit'=>$limit, 'id'=>$id]);

    }

    # news
    public function onenews(Request $request, $id)
    {

        $new = News::where('id',$id)->first();

        $section = News_Section::where('id' , $new->section_id)->first();


        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('section_type',$section->type)->where('type','store')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->get();

        $new->view_count = $new->view_count + 1;
        $new->save();

        $majs = Multi_Sec::where('section_id',$new->section_id)->pluck('new_id')->toArray();
        if(count($majs) == 0)
        {
            $news = News::where('section_id',$new->section_id)->take(4)->inRandomOrder()->get();
        }else{
            $news = News::where('section_id',$new->section_id)->orWhereIn('id',$majs)->take(4)->inRandomOrder()->get();
        }

        return view('news::fronts.new',compact('news','new','adss','logos'));
    }



}
 
 