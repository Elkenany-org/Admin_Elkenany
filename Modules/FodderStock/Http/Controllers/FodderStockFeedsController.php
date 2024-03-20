<?php

namespace Modules\FodderStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FodderStock\Entities\Stock_Feeds;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use Modules\FodderStock\Entities\Mini_Sub;
use Modules\FodderStock\Entities\Stock_Fodder_Section;
use Session;

class FodderStockFeedsController extends Controller
{

    # index
    public function sections()
    {
        $sections = Stock_Fodder_Section::latest()->get();
        return view('fodderstock::feeds.sections',compact('sections'));
    }

    # sub stocks
    public function subsstocks($id)
    {
        $section = Stock_Fodder_Section::where('id',$id)->latest()->first();
        $subs = Stock_Fodder_Sub::where('section_id',$id)->latest()->get();

        return view('fodderstock::feeds.subs',compact('subs','section'));
    }

    # index
    public function index($id)
    {
        $sub  = Stock_Fodder_Sub::with('Section')->where('id',$id)->latest()->first();
        $feeds = Stock_Feeds::where('section_id',$id)->with('Section','MiniSub')->latest()->get();
//        $sections = Stock_Fodder_Sub::with('Section')->latest()->get();
        $minies = Mini_Sub::with('Section')->where('section_id',$id)->latest()->get();
        return view('fodderstock::feeds.feeds',compact('feeds','sub','minies'));
    }

    # add feed
    public function Storefeed(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'section_id'   => 'required',
        ]);
        if($request->fixed === "1"){
            $feeds = Stock_Feeds::with('Section')->where('section_id',$request->section_id)->latest()->get();
            foreach($feeds as $fad){
                $fod = Stock_Feeds::where('id',$fad->id)->first();
                $fod->fixed       = 0;
                $fod->update();

            }
        }

        $feed = new Stock_Feeds;
        $feed->name       = $request->name;
        $feed->fixed       = $request->fixed;
        $feed->section_id = $request->section_id;
        $feed->mini_id = $request->mini_id;
        $feed->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة صنف علف  '.$feed->name);
        return back();
    }

    

    # update feed
    public function Updatefeed(Request $request)
    {

        $request->validate([
            'edit_id'          => 'required',
            'edit_name'        => 'required',
//            'edit_section_id'  => 'required',
        ]);

        if($request->edit_fixed === "1"){
            $feeds = Stock_Feeds::with('Section')->where('section_id',$request->edit_section_id)->latest()->get();
            foreach($feeds as $fad){
                $fod = Stock_Feeds::where('id',$fad->id)->first();
                $fod->fixed       = 0;
                $fod->update();

            }
        }

        $feed = Stock_Feeds::findOrFail($request->edit_id);
        $feed->name       = $request->edit_name;
        $feed->fixed       = $request->edit_fixed;
//        $feed->section_id = $request->edit_section_id;
        $feed->mini_id = $request->edit_mini_id;
        $feed->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  صنف علف '.$feed->name);
        return back();
    }


    public function UpdateAllFeedsItem(Request $request)
    {

       $arr =  explode(",",$request->edit_all_ids);
       if(count($arr) > 0){
//           Stock_Feeds::whereIN('id',$arr)->update(['mini_id',$request->mini_id]);
           foreach ($arr as $id){
               $stock = Stock_Feeds::find($id);
               $stock->mini_id = $request->mini_id;
               $stock->save();
           }
       }
        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  صنف علف '.$stock->name);
        return back();
    }
    # delete feed
    public function Deletefeed(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $feed = Stock_Feeds::where('id',$request->id)->first();
        MakeReport('بحذف صنف علف '.$feed->name);
        $feed->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # index
    public function sectionsmini()
    {
        $sections = Stock_Fodder_Section::latest()->get();
        return view('fodderstock::feeds.sectionsmini',compact('sections'));
    }

    # sub stocks
    public function subsstocksmini($id)
    {
        $section = Stock_Fodder_Section::where('id',$id)->latest()->first();
        $subs = Stock_Fodder_Sub::where('section_id',$id)->latest()->get();

        return view('fodderstock::feeds.subsmini',compact('subs','section'));
    }

    # index
    public function indexmini($id)
    {
        $sub  = Stock_Fodder_Sub::with('Section')->where('id',$id)->latest()->first();
        $feeds = Mini_Sub::where('section_id',$id)->with('Section')->latest()->get();
//        $sections = Stock_Fodder_Sub::with('Section')->latest()->get();
        return view('fodderstock::feeds.mini',compact('feeds','sub'));
    }

    # add feed
    public function Storefeedmini(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'section_id'   => 'required',
        ]);
      

        $feed = new Mini_Sub;
        $feed->name       = $request->name;
        $feed->section_id = $request->section_id;
        $feed->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة صنف علف  '.$feed->name);
        return back();
    }

    

    # update feed
    public function Updatefeedmini(Request $request)
    {
        $request->validate([
            'edit_id'          => 'required',
            'edit_name'        => 'required',
//            'edit_section_id'  => 'required',
        ]);


        $feed = Mini_Sub::findOrFail($request->edit_id);
        $feed->name       = $request->edit_name;
//        $feed->section_id = $request->edit_section_id;
        $feed->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  صنف علف '.$feed->name);
        return back();
    }

    # delete feed
    public function Deletefeedmini(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $feed = Mini_Sub::where('id',$request->id)->first();
        MakeReport('بحذف صنف علف '.$feed->name);
        $feed->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
  

}
