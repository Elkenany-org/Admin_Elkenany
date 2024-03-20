<?php

namespace Modules\Tenders\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Tenders\Entities\Tender;
use Modules\Tenders\Entities\Tender_Section;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Session;
use Image;
use File;
use View;

class TendersFrontController extends Controller
{

    # tenders
    public function tenders(Request $request, $name)
    {

        $section = Tender_Section::with('Tenders')->where('type',$name)->first();
        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','tenders')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->get();

        $tenders = Tender::where('section_id',$section->id)->orderby('title')->paginate(10);
        $secs = Tender_Section::get();
        $sort= '0';
        return view('tenders::fronts.tenders',compact('tenders','section','secs','sort','ads','logos'));
    }

    # tenders
    public function tenderssort(Request $request, $name)
    {

        $section = Tender_Section::with('tenders')->where('type',$name)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','tenders')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->get();

        $tenders = Tender::where('section_id',$section->id)->orderby('view_count' , 'desc')->paginate(10);
        $secs = Tender_Section::get();
        $sort= '1';
        return view('tenders::fronts.tenders',compact('tenders','section','secs','sort','ads','logos'));
    }

    # datas
    public function datas(Request $request)
    {

        $section = Tender_Section::where('id',$request->id)->first();
        $limit = 5;
        $datas = Tender::where('section_id',$section->id)->orderby('title')->take($limit)->get();
        $id = $request->id;
    

        return response()->json(['datas'=>$datas, 'limit'=>$limit, 'id'=>$id]);

    }

    # mores
    public function mores(Request $request)
    {

        $section = Tender_Section::where('id',$request->id)->first();
        $count = $request->count;
        $limit = $count + 5;
        $datas = Tender::where('section_id',$section->id)->orderby('title')->skip($count)->take(5)->get();
        $id = $request->id;
    

        return response()->json(['datas'=>$datas, 'limit'=>$limit, 'id'=>$id]);

    }

    # tenders
    public function onetenders(Request $request, $id)
    {

        $tender = Tender::where('id',$id)->first();

        $section = Tender_Section::where('id' , $tender->section_id)->first();

        
        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('section_type',$section->type)->where('type','store')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->get();

        $tender->view_count = $tender->view_count + 1;
        $tender->save();
        $tenders = Tender::where('section_id',$tender->section_id)->take(4)->inRandomOrder()->get();
        return view('tenders::fronts.tender',compact('tenders','tender','adss','logos'));
    }


    
}
 
 