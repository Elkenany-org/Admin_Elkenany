<?php

namespace Modules\LocalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Guide\Entities\Company;
use Modules\Guide\Entities\Guide_Section;
use Modules\Guide\Entities\Companies_Sec;
use Modules\LocalStock\Entities\Local_Stock_product;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\LocalStock\Entities\Local_Stock_Sub;
use Modules\LocalStock\Entities\Local_Stock_Detials;
use Modules\LocalStock\Entities\Local_Stock_Member;
use Modules\LocalStock\Entities\Local_Stock_Movement;
use Modules\LocalStock\Entities\Local_Stock_Columns;
use Modules\LocalStock\Entities\Member_Count;
use Modules\LocalStock\Entities\Local_Stock_Count;
use Session;

class LocalStockMemberController extends Controller
{
    

    # show members
    public function showMember($id)
    {
        $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
    
    	return view('localstock::sections.show_section',compact('section'));
    }

    # add members
    public function addMember($id)
    {
        $section   = Local_Stock_Sub::with('LocalStockColumns')->where('id',$id)->first();
        $sections = Guide_Section::latest()->get();
        $products  = Local_Stock_product::latest()->get();
    	return view('localstock::sections.add_members',compact('section','sections','products'));
    }

    # get companies ajax
    public function Getcompanies(Request $request)
    {
        $majs = Companies_Sec::where('section_id' , $request->section_id)->pluck('company_id')->toArray();
        $datas = Company::whereIn('id',$majs)->latest()->get();
        return $datas;
    }

    # get search companies ajax
    public function Searchcompany(Request $request)
    {
        $majs = Companies_Sec::where('section_id' , $request->section_id)->pluck('company_id')->toArray();
        $datas = Company::whereIn('id',$majs)->where('name' , 'like' , "%". $request->search ."%")->take(20)->latest()->get();
        return $datas;
    }

    # get search product ajax
    public function Searchproduct(Request $request)
    {
        $datas = Local_Stock_product::where('name' , 'like' , "%". $request->search ."%")->take(20)->latest()->get();
        return $datas;
    }

    # store members
    public function storeMember(Request $request)
    {
        $section   = Local_Stock_Sub::with('LocalStockColumns')->where('id',$request->id)->first();


      

            $Member = new Local_Stock_Member() ;
            $Member->product_id   =  $request->product_id ;
            $Member->company_id   =  $request->company_id ;
            $Member->section_id   =  $request->id ;
            $Member->save();

            $movement = new Local_Stock_Movement() ;
            $movement->member_id  =  $Member->id ;
            $movement->section_id =  $request->id;
            $movement->save();

            $change = new Member_Count() ;
            $change->member_id  =  $Member->id ;
            $change->change =  0;
            $change->save();

            $change->result =  Member_Count::where('member_id' , $Member->id)->sum('change') / Member_Count::where('member_id' , $Member->id)->count();
            $change->update();

            $memb   = Local_Stock_Member::where('id', $Member->id)->first();
            $memb->change =   $change->result;
            $memb->update();


            $changesub = new Local_Stock_Count() ;
            $changesub->section_id  =  $request->id ;
            $changesub->change =  0;
            $changesub->save();

            $changesub->result =  Local_Stock_Count::where('section_id' , $request->id)->sum('change') / Local_Stock_Count::where('section_id' , $request->id)->count();
            $changesub->update();

            $sub   = Local_Stock_Sub::where('id', $request->id)->first();
            $sub->change =   $changesub->result;
            $sub->update();

            $array = [];

            foreach(array_combine($request->column_id, $request->value) as $column_id => $value)
            {
                $column = Local_Stock_Columns::where('id',$column_id)->first();
                if($column->type === 'price')
                {
                    $array[] = ['value' => $value, 'section_id' => $request->id, 'column_type' => 'price', 'member_id' => $Member->id, 'movement_id' => $movement->id,'column_id' => $column_id];
                
                }elseif($column->type === 'change')
                {
                    $array[] = ['value' => $value, 'section_id' => $request->id, 'column_type' => 'change', 'member_id' => $Member->id, 'movement_id' => $movement->id,'column_id' => $column_id];
                    
                    

                }elseif($column->type === 'state')
                {
                    $array[] = ['value' => $value, 'section_id' => $request->id, 'column_type' => 'state', 'member_id' => $Member->id, 'movement_id' => $movement->id,'column_id' => $column_id];
                
                }else{
                    $array[] = ['value' => $value, 'section_id' => $request->id,'column_type' => '', 'member_id' => $Member->id, 'movement_id' => $movement->id,'column_id' => $column_id];
                    
                }
            }

            Local_Stock_Detials::insert($array);
            Session::flash('success','تم الحفظ');
            MakeReport('باضافة عضو '.$Member->id.' لبورصة '.$section->name);
            return back();
    }

    # update members
    public function updateMember(Request $request)
    {
        $maxAttempts = 3;
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {

            try {
                # get last movement to get price column
                $move = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$request->id)->latest()->first();
                foreach($move->LocalStockDetials as $d)
                {
                    if($d->LocalStockColumns->type === 'price')
                    {
                        $old_price = (float) $d->value;
                    }
                }

                # create new movement
                $movement = new Local_Stock_Movement() ;
                $movement->member_id  =  $request->id;
                $movement->section_id =  $request->section;
                $movement->save();
                foreach(array_combine($request->Mco_id, $request->value) as $Mco_id => $value)
                {
                    $column = Local_Stock_Columns::where('id',$Mco_id)->first();
                    if($column->type === 'price')
                    {
                        Local_Stock_Detials::create(['value' => $value, 'section_id' => $request->section, 'column_type' => 'price','member_id' => $request->id, 'movement_id' => $movement->id,'column_id' => $Mco_id]);

                    }elseif($column->type === 'change')
                    {
                        Local_Stock_Detials::insert(['value' => $value, 'section_id' => $request->section, 'column_type' => 'change', 'member_id' => $request->id, 'movement_id' => $movement->id,'column_id' => $Mco_id]);

                    }elseif($column->type === 'state')
                    {
                        Local_Stock_Detials::insert(['value' => $value, 'section_id' => $request->section, 'column_type' => 'state', 'member_id' => $request->id, 'movement_id' => $movement->id,'column_id' => $Mco_id]);

                    }else{
                        Local_Stock_Detials::insert(['value' => $value, 'section_id' => $request->section,'column_type' => '', 'member_id' => $request->id, 'movement_id' => $movement->id,'column_id' => $Mco_id]);

                    }

                }

                $member   = Local_Stock_Member::where('id',$request->id)->first();
                $member->status = 1;
                $member->check = 0;
                $member->save();

                # change and state
                $movenew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$request->id)->latest()->first();
                foreach($movenew->LocalStockDetials as $d)
                {
                    if($d->LocalStockColumns->type === 'price')
                    {
                        $new_price = (float) $d->value;
                    }

                    $total = $new_price - $old_price;

                    $result = (float) $total;



                    if($d->LocalStockColumns->type === 'change')
                    {
                        $change = Local_Stock_Detials::where('movement_id',$movenew->id)->where('column_id',$d->LocalStockColumns->id)->update(['value' => $total,]);

                        $change = new Member_Count() ;
                        $change->member_id  =  $member->id ;
                        $change->change =  $result;
                        $change->save();

                        $change->result =  Member_Count::where('member_id' , $member->id)->sum('change') / Member_Count::where('member_id' , $member->id)->count();
                        $change->update();

                        $memb   = Local_Stock_Member::where('id', $member->id)->first();
                        $memb->change =   $change->result;
                        $memb->update();

                        $changesub = new Local_Stock_Count() ;
                        $changesub->section_id  =  $member->section_id ;
                        $changesub->change =  $result;
                        $changesub->save();

                        $changesub->result =  Local_Stock_Count::where('section_id' , $member->section_id)->sum('change') / Local_Stock_Count::where('section_id' , $member->section_id)->count();
                        $changesub->update();

                        $sub   = Local_Stock_Sub::where('id', $member->section_id)->first();
                        $sub->change =   $changesub->result;
                        $sub->update();
                    }

                    if($d->LocalStockColumns->type === 'state')
                    {
                        if($result == 0)
                        {
                            Local_Stock_Detials::where('movement_id',$movenew->id)->where('column_id',$d->LocalStockColumns->id)->update(['value' => 'equal',]);
                        }
                        if($result > 0)
                        {
                            Local_Stock_Detials::where('movement_id',$movenew->id)->where('column_id',$d->LocalStockColumns->id)->update(['value' => 'up',]);

                        }
                        if($result < 0)
                        {
                            Local_Stock_Detials::where('movement_id',$movenew->id)->where('column_id',$d->LocalStockColumns->id)->update(['value' => 'down',]);

                        }

                    }

                }

                return $result;

            } catch (\Exception $e) {

                if ($attempt == $maxAttempts) {
                    \Log::error("Attempt $attempt failed: " . $e->getMessage()); // Log the error message
                }
                // Wait for some time before retrying (optional)
                usleep(1000000); // Sleep for 1 second before trying again
            }
        }
    }

    # delete member
    public function Deletemember(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $member = Local_Stock_Member::where('id',$request->id)->first();
        $sub   = Local_Stock_Sub::where('id', $member->section_id)->first();
        
        MakeReport('بحذف  عضو '.$member->id .' من بورصة  '.$sub->name);
        $member->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
 

    # check members
    public function checkMember(Request $request)
    {
        $member   = Local_Stock_Member::where('id',$request->id)->first();
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
        $member = Local_Stock_Member::with('Section.LocalStockColumns','LocalStockMovement.LocalStockDetials')->where('id',$id)->latest()->first();
        $movement = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$id)->latest()->paginate(10);
    
        return view('localstock::sections.show_movements',compact('member','movement'));
    }
}
