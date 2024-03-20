<?php

namespace Modules\FodderStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FodderStock\Entities\Stock_Feeds;
use Modules\FodderStock\Entities\Stock_Fodder_Section;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use Modules\Guide\Entities\Company;
use Modules\Guide\Entities\Guide_Section;
use Modules\FodderStock\Entities\Fodder_Stock;
use Modules\FodderStock\Entities\Fodder_Stock_Move;
use Session;


class FodderStocksController extends Controller
{

    # index
    public function index()
    {
        $sections = Stock_Fodder_Section::latest()->get();
        return view('fodderstock::stocks.sectionstocks',compact('sections'));
    }

    # sub stocks
    public function subsstocks($id)
    {
        $section = Stock_Fodder_Section::where('id',$id)->latest()->first();
        $subs = Stock_Fodder_Sub::where('section_id',$id)->latest()->get();

        return view('fodderstock::stocks.stocks_subs',compact('subs','section'));
    }

    # stocks
    public function stocks($id)
    {
        $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
        $stocks = Fodder_Stock::where('sub_id',$id)->with('Section','StockFeed','Company')->latest()->get()->unique('company_id');

        return view('fodderstock::stocks.stocks_company',compact('stocks','section'));
    }

    # stocks
    public function stockss($id)
    {
        $company = Company::where('id',$id)->latest()->first();
        $stocks = Fodder_Stock::where('company_id',$id)->with('Section','StockFeed','Company')->latest()->get();
   
        return view('fodderstock::stocks.stocks',compact('stocks','company'));
    }

    # stocks
    public function companies()
    {
        $stocks = Fodder_Stock::with('Section','StockFeed','Company')->latest()->get()->unique('company_id');

        return view('fodderstock::stocks.companies',compact('stocks'));
    }

    # stocks
    public function stocksscompany($id)
    {
        $company = Company::where('id',$id)->latest()->first();
        $majs = Fodder_Stock::where('company_id' , $id)->pluck('sub_id')->toArray();
        $subs = Stock_Fodder_Sub::whereIn('id',$majs)->with('FodderStocks','Section')->latest()->get()->unique('id');
        $stocks = Fodder_Stock::where('company_id',$id)->with('Section','StockFeed','Company')->latest()->get();
   
        return view('fodderstock::stocks.stockssec',compact('stocks','company','subs'));
    }


    # add stocks
    public function addstocks()
    {
        $gsections  = Guide_Section::latest()->get();
        $sections   = Stock_Fodder_Section::latest()->get();
        $feeds      = Stock_Feeds::latest()->get();

        return view('fodderstock::stocks.add_fodders',compact('gsections','sections','feeds'));
    }

    # get sub sections ajax
    public function Getfeeds(Request $request)
    {
 
        $subs = Stock_Fodder_Sub::where('section_id',$request->section_id)->latest()->get();
        $sub = Stock_Fodder_Sub::where('section_id',$request->section_id)->latest()->first();
        $datas = Stock_Feeds::where('section_id',$sub->id)->latest()->get();
        return response()->json(['subs' => $subs,'datas' => $datas,], 200);
    }

    # get feed sections ajax
    public function Getfeedsfooder(Request $request)
    {
        $datas = Stock_Feeds::where('section_id',$request->section_id)->latest()->get();

        return response()->json(['datas' => $datas ,], 200);
    }


    # add stocks
    public function Storestocks(Request $request)
    {
        $request->validate([
            'section_id'         => 'required',
            'company_id'         => 'required',
            'fodder_id'          => 'required',
            'price'              => 'required|numeric',
 
        ]);
        if(!is_null($request->fodder_id))
        {
            foreach($request->fodder_id as $fodder)
            {
                $feed = Fodder_Stock::where('company_id',$request->company_id)->where('fodder_id',$fodder)->latest()->first();
                if(!$feed)
                {

                    $stocks             = new Fodder_Stock;
                    $stocks->section_id = $request->section_stocks_id;
                    $stocks->sub_id     = $request->sub_id;
                    $stocks->company_id = $request->company_id;
                    $stocks->fodder_id  = $fodder;
                    $stocks->save();

                    $move            = new Fodder_Stock_Move;
                    $move->section_id= $stocks->section_id;
                    $move->sub_id    = $stocks->sub_id;
                    $move->company_id= $stocks->company_id;
                    $move->fodder_id = $fodder;
                    $move->stock_id  = $stocks->id;
                    $move->change    = $request->change;
                    $move->price     = $request->price;
                    $move->status    = $request->status;
                    $move->save();

                    $company = Company::where('id',$move->company_id)->latest()->first();

                }else{
                    Session::flash('danger','هذا الصنف والشركة موجودين بالفعل ');
                    return back();
                }
            }
        }
        
        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة صنف  '.$company->name);
        return back();
    }
    # update members
    public function updateMember(Request $request)
    {

        $feoed = Fodder_Stock::where('id',$request->id)->latest()->first();

        $move = Fodder_Stock_Move::where('stock_id',$request->id)->latest()->first();
     
        $old_price = (float) $move->price;
       
        # create new movement
        $movement = new Fodder_Stock_Move();
        $movement->section_id= $move->section_id;
        $movement->company_id= $move->company_id;
        $movement->sub_id    = $feoed->sub_id;
        $movement->fodder_id = $move->fodder_id;
        $movement->stock_id  = $request->id;
        $movement->price     = $request->price;
        $movement->save();

        $member   = Fodder_Stock::where('id',$request->id)->first();
        $member->status = 1;
        $member->check = 0;
        $member->update();
        
        $movenew   = Fodder_Stock_Move::where('id',$movement->id)->first();
        $new_price = (float) $movenew->price;


        $total = $new_price - $old_price;
        $result = (float) $total;
        
        $movenew->change    = $total;

        if($result == 0)
        {
            $movenew->status    = 'equal';
        }
        if($result > 0)
        {
            $movenew->status    = 'up';
        
        }
        if($result < 0)
        {
            $movenew->status    = 'down';
        
        }
        $movenew->update();
        
        return $result;
    }

    # delete member
    public function Deletemember(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $member = Fodder_Stock::where('id',$request->id)->first();
        $sub   = Company::where('id', $member->company_id)->first();
        
        MakeReport('بحذف  عضو '.$member->id .' من شركة  '.$sub->name);
        $member->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # check members
    public function checkMember(Request $request)
    {
        $member   = Fodder_Stock::where('id',$request->id)->first();
        if($member->check == 0)
        {
            $member->check = 1;
            $member->status = 0;
            $member->save();

        }
        return  $member->check;
    }

    # show movements
    public function showmovements($id)
    {
        $member = Fodder_Stock::with('FodderStockMoves','Section','StockFeed','Company')->where('id',$id)->latest()->first();
        $movement = Fodder_Stock_Move::where('stock_id',$id)->latest()->paginate(10);
    
        return view('fodderstock::stocks.show_movements',compact('member','movement'));
    }
}
