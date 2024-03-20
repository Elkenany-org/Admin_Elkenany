<?php

namespace Modules\MedicineStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\MedicineStock\Entities\Medic_Section;
use Modules\MedicineStock\Entities\Medic_Stock;
use Modules\MedicineStock\Entities\Medic_Stock_all;
use Modules\MedicineStock\Entities\Medic_Subs;
use Modules\MedicineStock\Entities\Com_name_images;
use Modules\MedicineStock\Entities\Com_name;
use Modules\Guide\Entities\Company;
use Modules\Guide\Entities\Guide_Section;

use Modules\MedicineStock\Entities\Medic_member;
use Modules\MedicineStock\Entities\Medic_move;
use App\Main;

use Session;
use Image;
use File;
use View;


class MedicineStockController extends Controller
{
    # index
    public function index($id)
    {
        $section = Medic_Stock::where('id',$id)->latest()->first();
        $stocks = Medic_member::where('sub_id',$id)->with('Section','MedicSubs','Company','MedicStock','Comname')->latest()->get();
        return view('medicinestock::stocks.stocks',compact('stocks','section'));
    }

    # add stocks
    public function addstocks()
    {
        $gsections  = Guide_Section::latest()->get();
        $sections   = Medic_Section::latest()->get();
        $names      = Com_name::latest()->get();
        $subs      = Medic_Subs::latest()->get();

        return view('medicinestock::stocks.add_members',compact('gsections','sections','names','subs'));
    }
    # get sub sections ajax
    public function Getsections(Request $request)
    {

        $subs = Medic_Stock::where('section_id',$request->section_id)->latest()->get();
       
        return response()->json(['subs' => $subs,], 200);
    }

    # add stocks
    public function Storestocks(Request $request)
    {
        $request->validate([
            'section_id'         => 'required',
            'company_id'         => 'required',
            'name_id'            => 'required',
            'active_id'          => 'required',
            'price'              => 'required|numeric',

        ]);
     
     

        $stocks             = new Medic_member;
        $stocks->section_id = $request->section_stocks_id;
        $stocks->sub_id     = $request->sub_id;
        $stocks->company_id = $request->company_id;
        $stocks->name_id    = $request->name_id;
        $stocks->active_id  = $request->active_id;
        $stocks->save();

        $move            = new Medic_move;
        $move->section_id= $stocks->section_id;
        $move->sub_id    = $stocks->sub_id;
        $move->company_id= $stocks->company_id;
        $move->name_id   = $stocks->name_id;
        $move->active_id = $stocks->active_id;
        $move->member_id = $stocks->id;
        $move->change    = $request->change;
        $move->price     = $request->price;
        $move->status    = $request->status;
        $move->save();

        $company = Company::where('id',$move->company_id)->latest()->first();

     
        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة صنف  '.$company->name);
        return back();
    }

    # update members
    public function updateMember(Request $request)
    {

        $feoed = Medic_member::where('id',$request->id)->latest()->first();

        $move = Medic_move::where('member_id',$request->id)->latest()->first();
        
        $old_price = (float) $move->price;
        
        # create new movement
        $movement = new Medic_move();
        $movement->section_id= $move->section_id;
        $movement->company_id= $move->company_id;
        $movement->sub_id    = $feoed->sub_id;
        $movement->name_id   = $feoed->name_id;
        $movement->active_id = $feoed->active_id;
        $movement->member_id = $feoed->id;
        $movement->price     = $request->price;
        $movement->save();

        $member   = Medic_member::where('id',$request->id)->first();
        $member->status = 1;
        $member->check = 0;
        $member->update();
        
        $movenew   = Medic_move::where('id',$movement->id)->first();
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

        $member = Medic_member::where('id',$request->id)->first();
        $sub   = Company::where('id', $member->company_id)->first();
        
        MakeReport('بحذف  عضو '.$member->id .' من شركة  '.$sub->name);
        $member->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # check members
    public function checkMember(Request $request)
    {
        $member   = Medic_member::where('id',$request->id)->first();
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
        $member = Medic_member::with('Section','MedicSubs','Company','MedicStock','Comname')->where('id',$id)->latest()->first();
        $movement = Medic_move::where('member_id',$id)->latest()->paginate(10);
    
        return view('medicinestock::stocks.show_movements',compact('member','movement'));
    }
}

   
