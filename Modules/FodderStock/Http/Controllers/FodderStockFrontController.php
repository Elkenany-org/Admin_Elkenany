<?php

namespace Modules\FodderStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FodderStock\Entities\Stock_Feeds;
use Modules\FodderStock\Entities\Stock_Fodder_Section;
use Modules\FodderStock\Http\Services\FodderService;
use Modules\Guide\Entities\Company;
use Modules\Guide\Entities\Guide_Section;
use Modules\FodderStock\Entities\Fodder_Stock;
use Modules\FodderStock\Entities\Fodder_Stock_Move;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Session;
use Auth;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class FodderStockFrontController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @param FodderService $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function stocks(Request $request,$id,FodderService $service)
    {

        $date  = Input::get("date");
        $company  = Input::get("company_id");
        $fodder_id  = Input::get("fodder_id");

        $status = 'new';
        $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
        $fod = null;
        if($fodder_id){
            $fod   = Stock_Feeds::where('id',$fodder_id)->first();

            if(!$fod){
                return view('fodderstock::fronts.no_data');
            }
        }else{
            if(!$company){
                $fod = Stock_Feeds::where('section_id',$id)->where('fixed','1')->first();
            }
        }


        $subs = Stock_Fodder_Sub::where('section_id',$section->section_id)->get();

        $page = System_Ads_Pages::where('sub_id',$id)->where('type','fodderstock')->pluck('ads_id');

        $banners = System_Ads::where('sub','1')->whereIn('id',$page)->whereIn('type',['logo','banner'])->where('status','1')->inRandomOrder()->get();

        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id');

        $secs = Local_Stock_Sections::get();

        $fodss = Fodder_Stock::with('Company')->where('sub_id',$id)->latest()->get()->unique('company_id');


        $feeds = Stock_Feeds::where('section_id',$id)->orderBy('fixed' , 'desc')->get();

        if(!$section){
            return view('fodderstock::fronts.no_data');
        }


        if(Auth::guard('customer')->user() && Auth::guard('customer')->user()->memb != 1){
            if($date && $date < Carbon::now()->subDays(7)) {
                return redirect()->back()->with(['danger' => ' ليست لديك الصلاحية']);
            }
        }else{
            if($date && $date < Carbon::now()->subDays(7) && !Auth::guard('customer')->user()){
                return redirect()->back()->with(['danger'=>' ليست لديك الصلاحية']);
            }
        }
        if(isset($date)){
            $date  = Input::get("date");
        }else{
            $date = date('Y-m-d');
        }

        $moves = $service->Members($request,$id,$ads,$date,$fod);
        $movessort = $service->RankingMembers($request,$id,$ads,$date,$fod);

        while(count($moves) == 0 && count($movessort) == 0)
        {
           $date = date("Y-m-d", strtotime($date ."-1 day"));
           $day = date('l', strtotime($date));

            if($day == 'Friday'){
                $date = date( 'Y-m-d', strtotime( $date . ' -1 day' ) );
            }
            $status = 'old';
            $moves = $service->Members($request,$id,$ads,$date,$fod);
            $movessort = $service->RankingMembers($request,$id,$ads,$date,$fod);
        }

        $mv = [];
        foreach ($movessort as $k =>$v){
            $mv[] = $v->id;
        }

        if ($request->ajax()) {
            $view = view('fodderstock::fronts.section_data_table',compact('moves','mv'))->render();
            return response()->json(['html'=>$view]);
        }
        return view('fodderstock::fronts.sectiondate',compact('date','status','banners','section','moves','feeds','secs','fodss','fod','movessort','subs','mv'));
    }

    
    # feeds
    public function feeds(Request $request, $id)
    {
        $now = Carbon::now();
        $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();

        $subs = Stock_Fodder_Sub::where('section_id',$section->section_id)->get();

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$section->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();

       

        $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$section->id)->latest()->get()->unique('company_id');
        $fod   = Stock_Feeds::where('id',$request->id)->first();
        $feeds = Stock_Feeds::where('section_id',$section->id)->latest()->get();
        $secs = Local_Stock_Sections::get();
        $moves = Fodder_Stock_Move::where('sub_id',$section->id)->where('fodder_id',$request->id)->with('Section','StockFeed','Company','FodderStock')->latest()->get()->unique('stock_id');
        
        $movessort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$section->id)->where('fodder_id',$request->id)->with('Section','StockFeed','Company','FodderStock')->latest()->get()->unique('stock_id');

        $mv = [];
        foreach ($movessort as $k =>$v){
            $mv[] = $v->id;
        }
        return view('fodderstock::fronts.sectiondate',compact('feeds','moves','section','fod','secs','now','fodss','movessort','adss','logos','subs','mv'));
    }


    # companies
    public function companies(Request $request, $id) 
    {
        $now = Carbon::now();
        $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();

        $subs = Stock_Fodder_Sub::get();

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$section->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();

       
        $movessort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$section->id)->where('company_id',$request->id)->with('Section','StockFeed','Company','FodderStock')->latest()->get()->unique('stock_id');

        $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$section->id)->latest()->get()->unique('company_id');
        $fod = Stock_Feeds::where('section_id',$section->id)->where('fixed','1')->first();
        $feeds = Stock_Feeds::where('section_id',$section->id)->latest()->get();
        $secs = Local_Stock_Sections::get();
        $moves = Fodder_Stock_Move::where('sub_id',$section->id)->where('company_id',$request->id)->with('Section','StockFeed','Company','FodderStock')->latest()->get()->unique('stock_id');

        $mv = [];
        foreach ($movessort as $k =>$v){
            $mv[] = $v->id;
        }
        return view('fodderstock::fronts.sectiondate',compact('feeds','moves','section','fod','secs','now','fodss','adss','logos','subs','movessort','mv'));
    }

    # show statistic detials member
    public function detialsmember($id)
    {
        $from  = Input::get("from");
        $to    = Input::get("to");

        $member = Fodder_Stock::with('StockFeed','Section','FodderStockMoves','Company')->where('id',$id)->first();

        return view('fodderstock::fronts.member_detials',compact('member','from','to'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function feedstst(Request $request, $id)
    {

        $from  = Input::get("from");
        $to    = Input::get("to");
        $local = $request->query('local');

        if(Auth::guard('customer')->user() && Auth::guard('customer')->user()->memb != 1){
            if($from && $from < Carbon::now()->subDays(7)) {
                return redirect()->back()->with(['danger' => ' ليست لديك الصلاحية']);
            }
        }else{
            if($from && $from < Carbon::now()->subDays(7) && !Auth::guard('customer')->user()){
                return redirect()->back()->with(['danger'=>' ليست لديك الصلاحية']);
            }
        }

        $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
        $feeds = Fodder_Stock::with('Section','Company')->where('sub_id',$id);
//        $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$id);
        if($from){
            $mov = Fodder_Stock_Move::whereBetween( 'created_at', [$from, $to])->pluck('stock_id');
            $feeds->whereIn('id',$mov);
        }
        if ($local){
            $feeds->whereIn('id',$local);
        }
        $feeds = $feeds->latest()->get();

        if(count($feeds) > 0){
            $companies = Company::select('id','name')->whereHas('FodderStocks',function ($q) use($id,$local) {
                $q->where('sub_id',$id);
                if($local){
                    $q->whereIn('id',$local);
                }
            })->get();
        }else{
            $companies = [];
        }


        $all_feeds = Fodder_Stock::with('StockFeed','Company')->where('sub_id',$id)->latest()->get();
        if ($local){
            $all_feeds->map(function ($member) use($local){
                $member['selected'] = in_array($member->id,$local) ? 1 : 0;
                return $member;
            });
        }

        return view('fodderstock::fronts.member_changes',compact('all_feeds','companies','feeds','section','from','to'));
    }

        # show statistic drop
        public function statisticdropmember(Request $request)
        {
        $from  = Input::get("from");
        $to    = Input::get("to");
        if(is_null($from) || is_null($to))
        {
            if(is_null($request->local))
            {
                
                $section = Stock_Fodder_Sub::where('id',$request->section)->latest()->first();
                $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$request->section)->latest()->get();
            }else{
                $section = Stock_Fodder_Sub::where('id',$request->section)->latest()->first();
                $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$request->section)->whereIn('id',$request->local)->latest()->get();
          
            }
        }else{

            if(Auth::guard('customer')->user()){
                if(Auth::guard('customer')->user()->memb == '1')
                {
                    if(is_null($request->local))
                    {
                        
                        $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$request->section)->first();
                        $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                        $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$request->section)->whereIn('id',$mov)->get();
                    }else{
                        $section = Stock_Fodder_Sub::where('id',$request->section)->latest()->first();
                        $mov = Fodder_Stock_Move::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('stock_id')->toArray();
                        $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$request->section)->whereIn('id',$request->local)->whereIn('id',$mov)->get();
                    }
                    
    
                }else{
    
                    if($from < Carbon::now()->subDays(7)){
                        Session::flash('danger',' ليست لديك الصلاحية');
                        return back();
                    }else{
                        if(is_null($request->local))
                        {
                            
                            $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$request->section)->first();
                            $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                            $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$request->section)->whereIn('id',$mov)->get();
                        }else{
                            $section = Stock_Fodder_Sub::where('id',$request->section)->latest()->first();
                            $mov = Fodder_Stock_Move::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('stock_id')->toArray();
                            $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$request->section)->whereIn('id',$request->local)->whereIn('id',$mov)->get();
                        }
                
                    }
                  
    
                }
            }else{
                if($from < Carbon::now()->subDays(7)){
                    Session::flash('danger',' ليست لديك الصلاحية');
                    return back();
                }else{
                    if(is_null($request->local))
                    {
                        
                        $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$request->section)->first();
                        $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                        $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$request->section)->whereIn('id',$mov)->get();
                    }else{
                        $section = Stock_Fodder_Sub::where('id',$request->section)->latest()->first();
                        $mov = Fodder_Stock_Move::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('stock_id')->toArray();
                        $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$request->section)->whereIn('id',$request->local)->whereIn('id',$mov)->get();
                    }
            
                }
              
            }
           
          
        }
            
    
        
        return view('fodderstock::fronts.member_changes',compact('feeds','section','from','to'));
        }

}
