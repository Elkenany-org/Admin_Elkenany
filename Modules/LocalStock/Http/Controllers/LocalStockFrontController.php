<?php

namespace Modules\LocalStock\Http\Controllers;

use App\Traits\SearchReg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Guide\Entities\Company;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\LocalStock\Entities\Local_Stock_Sub;
use Modules\LocalStock\Entities\Local_Stock_Detials;
use Modules\LocalStock\Entities\Local_Stock_Member;
use Modules\LocalStock\Entities\Local_Stock_Movement;
use Modules\LocalStock\Entities\Local_Stock_Columns;
use Modules\LocalStock\Entities\Sec_All;
use Modules\FodderStock\Entities\Fodder_Stock;
use Modules\FodderStock\Entities\Fodder_Stock_Move;
use Modules\FodderStock\Entities\Stock_Feeds;
use Modules\FodderStock\Entities\Stock_Fodder_Section;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use Modules\LocalStock\Http\Services\GuideService;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Session;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Auth;
use function foo\func;

class LocalStockFrontController extends Controller
{
    use SearchReg;

    /**
     * @param $name
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($name)
    {
        $keyword = '';
        $sort = '';
        if(isset($_GET['keyword'])){
            $keyword = $this->searchQuery($_GET['keyword']);
        }

        $secs = Local_Stock_Sections::get();
        $page = System_Ads_Pages::where('section_type',$name)->where('type','localstock')->pluck('ads_id')->toArray();

        $banners = System_Ads::where('main','1')->whereIn('id',$page)->whereIn('type',['banner','logo'])->where('status','1')->inRandomOrder()->get();

        $section = Local_Stock_Sections::with('LocalStockSub')->where('type',$name)->first();

        $sections =  Local_Stock_Sub::whereIn('id',$section->LocalSunIds());

        if(isset($_GET['sort'])){
            if($_GET['sort'] == 1){
                $sections->orderByDesc('view_count');
            }
            if($_GET['sort'] == 0){
                $sections->orderBy('name' , 'ASC');
            }
        }else{
            $sections->orderBy('sort');
        }

        if($keyword != ""){
            $sections->where('name' , 'REGEXP' , $keyword);
        }
        $sections = $sections->get();

        $subs = Stock_Fodder_Sub::whereHas('Section',function ($q) use($name){ $q->where('type',$name); });
        if($keyword != ""){
            $subs->where('name' , 'REGEXP' , $keyword);
        }
        if(isset($_GET['sort'])){
            if($_GET['sort'] == 0){
                $subs->orderby('name');
            }
        }else{
            $subs->orderby('sort');
        }
        $subs = $subs->get();

        return view('localstock::fronts.sections',compact('banners','section','sections','secs','subs'));

    }

    # index
    public function indexname($name)
    {
        $keyword = '';
        if(isset($_GET['keyword'])){
            $keyword = $this->searchQuery($_GET['keyword']);
        }


        $section = Local_Stock_Sections::with(['LocalStockSub'=>function($q) use($keyword){
            if(isset($keyword) && $keyword != ""){
                $q->where('name' , 'REGEXP' , $keyword);
            }
        }])->with(['LocalStockSubs'=>function($q) use($keyword){
            if(isset($keyword) && $keyword != ""){
                $q->where('name' , 'REGEXP' , $keyword);
            }
        }])->where('type',$name)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','localstock')->pluck('ads_id')->toArray();

        $banners = System_Ads::where('main','1')->whereIn('id',$page)->whereIn('type',['banner','logo'])->where('status','1')->inRandomOrder()->get();

        if($section){
            $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
            if(count($majs) == 0)
            { 
                $sections = Local_Stock_Sub::where('section_id',$section->id)->orderby('name')->get();
            }else{
                $sections = Local_Stock_Sub::where('section_id',$section->id)->orWhereIn('id',$majs)->orderby('name')->get();
            }
            
            $sectionsf = Stock_Fodder_Section::with('FodderStocks')->where('type',$name)->first();
            $subs = Stock_Fodder_Sub::where('section_id',$sectionsf->id);
            if(isset($keyword) && $keyword != ""){
                $subs->where('name' , 'REGEXP' , $keyword);
            }
            $subs = $subs->orderby('name')->get();
            $secs = Local_Stock_Sections::get();
            $sort= '0';
            return view('localstock::fronts.sections',compact('banners','section','sections','secs','sort','subs'));
        }
        $sections = null;
        $sectionsf = Stock_Fodder_Section::with('FodderStocks')->where('type',$name)->first();
        $subs = Stock_Fodder_Sub::where('section_id',$sectionsf->id);
        if(isset($keyword) && $keyword != ""){
            $subs->where('name' , 'REGEXP' , $keyword);
        }
        $subs =  $subs->orderby('name')->get();
        $secs = Local_Stock_Sections::get();
        $sort= '0';
        return view('localstock::fronts.sections',compact('banners','section','sections','secs','sort','subs'));
    }

    # index
    public function sort($name)
    {
        $section = Local_Stock_Sections::with('LocalStockSub')->where('type',$name)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','localstock')->pluck('ads_id')->toArray();

        $banners = System_Ads::where('main','1')->whereIn('id',$page)->whereIn('type',['banner','logo'])->where('status','1')->inRandomOrder()->get();

        $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
        if(count($majs) == 0)
        { 
            $sections = Local_Stock_Sub::where('section_id',$section->id)->orderBy('view_count' , 'desc')->get();
        }else{
            $sections = Local_Stock_Sub::where('section_id',$section->id)->orWhereIn('id',$majs)->orderBy('view_count' , 'desc')->get();
        }
       
        $sectionsf = Stock_Fodder_Section::with('FodderStocks')->where('type',$name)->first();
        $subs = Stock_Fodder_Sub::where('section_id',$sectionsf->id)->orderby('name')->get();
        $secs = Local_Stock_Sections::get();
        $sort= '1';
        return view('localstock::fronts.sections',compact('banners','section','sectionsf','sections','secs','sort','subs'));
    }

    # show members
    public function Members($id, $type = null, GuideService $service)
    {

        $date = Input::get("date");
        $status = 'new';

        $section = Local_Stock_Sub::with('LocalStockColumns')->where('id', $id)->first(); //,'LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts'
        $section->view_count = $section->view_count + 1;
        $section->save();

        if ($type) {
            $section->section_id = Local_Stock_Sections::where('type', $type)->first()->id;
        }

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id', $id)->where('type', 'localstock')->pluck('ads_id')->toArray();

        $banners = $this->BannersLogo($id, 'localstock');

        $ads = System_Ads::where('sub', '1')->whereIn('id', $page)->where('type', 'sort')->where('status', '1')->pluck('company_id');

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


        $secs = Local_Stock_Sections::get();

        $maj = Sec_All::where('sub_id', $section->id)->pluck('section_id')->toArray();
        $majs = Sec_All::where('section_id', $section->section_id)->orWhereIn('section_id', $maj)->pluck('sub_id')->toArray();

        $subs = Local_Stock_Sub::where('section_id', $section->section_id)->orWhereIn('id', $majs)->orderBy('sort')->get();
        $subs->map(function ($sub) use($secs,$section){
            $sub['section'] = "";
            if(is_null($sub->section_id)){
                foreach ($secs as $sec){
                    if($section->section_id == $sec->id){
                        $sub['section'] = $sec->type;
                    }
                }
            }
           return $sub;
        });
        $olddate=$date;

        $moves = $service->Members($id,$ads,$date);
        $movessort = $service->RankingMembers($id,$ads,$date);


        while(count($moves) == 0 && count($movessort) == 0) {
            $date = date("Y-m-d", strtotime($date . "-1 day"));
            $day = date('l', strtotime($date));

            if ($day == 'Friday') {
                $date = date('Y-m-d', strtotime($date . ' -1 day'));
            }
            $status = 'old';
            $moves = $service->Members($id,$ads,$date);
            $movessort = $service->RankingMembers($id,$ads,$date);
        }

//        return $moves;
        return view('localstock::fronts.section',compact('banners','section','secs','date','status','moves','subs','movessort'));
        
    }



    # get sections ajax
    public function Getsections(Request $request)
    {
        $majs = Sec_All::where('section_id' , $request->section_id)->pluck('sub_id')->toArray();
        if(count($majs) == 0)
        { 
            $datas = Local_Stock_Sub::where('section_id',$request->section_id)->orderby('name', 'ASC')->get();

            $subss = Local_Stock_Sub::where('section_id',$request->section_id)->pluck('id')->toArray();
        }else{
            $datas = Local_Stock_Sub::where('section_id',$request->section_id)->orWhereIn('id',$majs)->orderby('name', 'ASC')->get();

            $subss = Local_Stock_Sub::where('section_id',$request->section_id)->orWhereIn('id',$majs)->pluck('id')->toArray();
        }
        
        $section = Local_Stock_Sections::with('LocalStockSub')->where('id',$request->section_id)->first();
        $sec = Stock_Fodder_Section::with('FodderStocks')->where('name',$section->name)->first();
        $subs = Stock_Fodder_Sub::where('section_id',$sec->id)->orderby('name')->get();


        $page = System_Ads_Pages::with('SystemAds')->whereIn('sub_id',$subss)->where('type','localstock')->pluck('ads_id')->toArray();

        $ads = System_Ads::with('SystemAdsPages')->where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $subsf = Stock_Fodder_Sub::where('section_id',$sec->id)->pluck('id')->toArray();

        $pagef = System_Ads_Pages::with('SystemAds')->whereIn('sub_id',$subsf)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $adsf = System_Ads::with('SystemAdsPages')->where('sub','1')->whereIn('id',$pagef)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        return response()->json(['datas'=>$datas, 'subs'=>$subs, 'sec'=>$sec,'section'=>$section, 'ads'=>$ads,'adsf'=>$adsf]);
    }

    # get sections ajax
    public function Getsectionssort(Request $request)
    {
        $majs = Sec_All::where('section_id' , $request->section_id)->pluck('sub_id')->toArray();
        if($request->sort == '0'){

            if(count($majs) == 0)
            { 
                $datas = Local_Stock_Sub::where('section_id',$request->section_id)->orderby('name', 'ASC')->get();

                $subss = Local_Stock_Sub::where('section_id',$request->section_id)->pluck('id')->toArray();
            }else{
                $datas = Local_Stock_Sub::where('section_id',$request->section_id)->orWhereIn('id',$majs)->orderby('name', 'ASC')->get();

                $subss = Local_Stock_Sub::where('section_id',$request->section_id)->orWhereIn('id',$majs)->pluck('id')->toArray();
            }
            
            $section = Local_Stock_Sections::with('LocalStockSub')->where('id',$request->section_id)->first();
            $sec = Stock_Fodder_Section::with('FodderStocks')->where('name',$section->name)->first();
            $subs = Stock_Fodder_Sub::where('section_id',$sec->id)->orderby('name')->get();
    
        }else{

            if(count($majs) == 0)
            { 
                $datas = Local_Stock_Sub::where('section_id',$request->section_id)->orderBy('view_count' , 'desc')->get();

                $subss = Local_Stock_Sub::where('section_id',$request->section_id)->pluck('id')->toArray();
            }else{
                $datas = Local_Stock_Sub::where('section_id',$request->section_id)->orWhereIn('id',$majs)->orderBy('view_count' , 'desc')->get();

                $subss = Local_Stock_Sub::where('section_id',$request->section_id)->orWhereIn('id',$majs)->pluck('id')->toArray();
            }
            
            $section = Local_Stock_Sections::with('LocalStockSub')->where('id',$request->section_id)->first();
            $sec = Stock_Fodder_Section::with('FodderStocks')->where('name',$section->name)->first();
            $subs = Stock_Fodder_Sub::where('section_id',$sec->id)->orderby('name')->get();
    
        }

        $page = System_Ads_Pages::with('SystemAds')->whereIn('sub_id',$subss)->where('type','localstock')->pluck('ads_id')->toArray();

        $ads = System_Ads::with('SystemAdsPages')->where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $subsf = Stock_Fodder_Sub::where('section_id',$sec->id)->pluck('id')->toArray();

        $pagef = System_Ads_Pages::with('SystemAds')->whereIn('sub_id',$subsf)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $adsf = System_Ads::with('SystemAdsPages')->where('sub','1')->whereIn('id',$pagef)->where('type','logo')->where('status','1')->inRandomOrder()->get();
       
        return response()->json(['datas'=>$datas, 'subs'=>$subs, 'sec'=>$sec,'section'=>$section, 'ads'=>$ads,'adsf'=>$adsf]);
    }

    # get sections ajax
    public function Getsectionsname(Request $request)
    {
     
        $keyword = $this->searchQuery($request->search);
        $datas = Local_Stock_Sub::where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
     
        $subs = Stock_Fodder_Sub::where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();

        $subss = Local_Stock_Sub::pluck('id')->toArray();


        $page = System_Ads_Pages::with('SystemAds')->whereIn('sub_id',$subss)->where('type','localstock')->pluck('ads_id')->toArray();

        $ads = System_Ads::with('SystemAdsPages')->where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $subsf = Stock_Fodder_Sub::pluck('id')->toArray();

        $pagef = System_Ads_Pages::with('SystemAds')->whereIn('sub_id',$subsf)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $adsf = System_Ads::with('SystemAdsPages')->where('sub','1')->whereIn('id',$pagef)->where('type','logo')->where('status','1')->inRandomOrder()->get();
       

        return response()->json(['datas'=>$datas, 'subs'=>$subs, 'ads'=>$ads,'adsf'=>$adsf]);
    }

    # show members
    public function comprison($id)
    {
        $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
    
        return view('localstock::fronts.comprison',compact('section'));
    }

    # get companies
    public function Getcompanies(Request $request)
    {
 
            $datas  = Local_Stock_Member::with('LocalStockMovement.LocalStockDetials.LocalStockColumns','Company','LocalStockproducts')->whereIn('id',$request->companies)->get();

        return response()->json(['datas' => $datas], 200);
        
    }

    # show members
    public function comprisonfodder($id)
    {
        $section = Stock_Fodder_Sub::with('StockFeeds','FodderStocks.Company')->where('id',$id)->first();
        $sections = Fodder_Stock::with('Section','Company')->where('sub_id',$section->id)->get()->unique('company_id');
    
        return view('localstock::fronts.comprisonfodder',compact('section','sections'));
    }

    # get companies
    public function Getcompaniesfodder(Request $request)
    {
        
        $comp  = Company::whereIn('id',$request->companies)->get();
        $datas  = Fodder_Stock::with('Section','StockFeed','Company','FodderStockMoves')->whereIn('fodder_id',$request->items)->whereIn('company_id',$request->companies)->get();

        return response()->json(['datas' => $datas , 'comp' => $comp,], 200);
        
    }

    # show statistic
    public function statistic($id)
    {
        $from  = Input::get("from");
        $to    = Input::get("to");
        $majs = Sec_All::where('section_id' , $id)->pluck('sub_id')->toArray();
        if(is_null($from) || is_null($to))
        {
            
            $section = Local_Stock_Sections::where('id',$id)->first();
            $sections = Local_Stock_Sub::with('LocalStockCounts')->where('section_id',$id)->orWhereIn('id',$majs)->get();
        
        }else{
            if(Auth::guard('customer')->user()){
                if(Auth::guard('customer')->user()->memb == '1')
            {
             
                $section = Local_Stock_Sections::where('id',$id)->first();
                $sections = Local_Stock_Sub::with('LocalStockCounts')->where('section_id',$id)->orWhereIn('id',$majs)->get();

            }else{

                if($from < Carbon::now()->subDays(7)){
                    Session::flash('danger',' ليست لديك الصلاحية');
                    return back();
                }else{
                 
                    $section = Local_Stock_Sections::where('id',$id)->first();
                    $sections = Local_Stock_Sub::with('LocalStockCounts')->where('section_id',$id)->orWhereIn('id',$majs)->get();
                }
              

            }
            }else{
                if($from < Carbon::now()->subDays(7)){
                    Session::flash('danger',' ليست لديك الصلاحية');
                    return back();
                }else{
                 
                    $section = Local_Stock_Sections::where('id',$id)->first();
                    $sections = Local_Stock_Sub::with('LocalStockCounts')->where('section_id',$id)->orWhereIn('id',$majs)->get();
                }
              
            }
            
        
       }
        
    
        return view('localstock::fronts.local_changes',compact('sections','section','from','to'));
    }
    # show statistic drop
    public function statisticdrop(Request $request)
    {
        $from  = Input::get("from");
        $to    = Input::get("to");
        if(is_null($request->local))
        {
            $section = Local_Stock_Sections::where('id',$request->section)->first();
            $sections = Local_Stock_Sub::with('LocalStockCounts')->where('section_id',$request->section)->get();
        }else{
            $section = Local_Stock_Sections::where('id',$request->section)->first();
            $sections = Local_Stock_Sub::with('LocalStockCounts')->whereIn('id',$request->local)->where('section_id',$request->section)->get();
        }
        
   
    
        return view('localstock::fronts.local_changes',compact('sections','section','from','to'));
    }

    # show statistic drop
    public function statisticdropmember(Request $request)
    {
    $from  = Input::get("from");
    $to    = Input::get("to");

        $all_members = Local_Stock_Member::with('Company','LocalStockproducts')->where('section_id',$request->section)->get();
    if(is_null($from) || is_null($to))
    {
        if(is_null($request->local))
        {
            
            $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$request->section)->first();
            $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$request->section)->get();
        }else{
            $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$request->section)->first();
            $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$request->section)->whereIn('id',$request->local)->get();
        
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
                    $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$request->section)->first();
                    $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                    $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$request->section)->whereIn('id',$request->local)->whereIn('id',$mov)->get();
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
                        $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$request->section)->first();
                        $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                        $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$request->section)->whereIn('id',$request->local)->whereIn('id',$mov)->get();
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
                    $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$request->section)->first();
                    $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                    $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$request->section)->whereIn('id',$request->local)->whereIn('id',$mov)->get();
                }
            }
        }
       
       
        
    }
        

    
        return view('localstock::fronts.member_changes',compact('all_members','members','section','from','to'));
    }

    # show statistic detials
    public function detials($id)
    {
        $from  = Input::get("from");
        $to    = Input::get("to");

        $section = Local_Stock_Sub::where('id',$id)->first();
        $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$id)->get();
        
        return view('localstock::fronts.local_detials',compact('members','section','from','to'));
    }

    # show statistic detials member
    public function detialsmember($id)
    {
        $from  = Input::get("from");
        $to    = Input::get("to");

        $member = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('id',$id)->first();
        
        return view('localstock::fronts.member_detials',compact('member','from','to'));
    }

     # show statistic members
     public function statisticmembers(Request $request,$id)
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

         $all_members = Local_Stock_Member::with('Company','LocalStockproducts')->where('section_id',$id)->get();
             if ($local){
                 $all_members->map(function ($member) use($local){
                     $member['selected'] = in_array($member->id,$local) ? 1 : 0;
                     return $member;
                 });
             }

            
        $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
        $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$id);
        if($local){
            $members->whereIn('id',$local);
        }
        if($from && $to){
            $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('member_id');
            $members->whereIn('id',$mov);
        }
        $members = $members->get();


        return view('localstock::fronts.member_changes',compact('all_members','members','section','from','to'));
     }

   

}

