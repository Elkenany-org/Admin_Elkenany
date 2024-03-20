<?php

namespace Modules\LocalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Guide\Entities\Company;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\LocalStock\Entities\Local_Stock_Sub;
use Modules\LocalStock\Entities\Local_Stock_Detials;
use Modules\LocalStock\Entities\Local_Stock_Member;
use Modules\LocalStock\Entities\Local_Stock_Movement;
use Modules\LocalStock\Entities\Local_Stock_Columns;
use Modules\LocalStock\Entities\Sec_All;
use App\Main;
use Session;
use Image;
use File;
use View;

class LocalStockSectionController extends Controller
{
    # index
    public function index()
    {
        $sections = Local_Stock_Sections::with('LocalStockSub')->latest()->get();
        $sectionss = Main::latest()->get();
        return view('localstock::sections.psections',compact('sections','sectionss'));
    }

    # add psection
    public function Storepsection(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'type'         => 'required',
        ]);

        $section = new Local_Stock_Sections;
        $section->name       = $request->name;
        $section->type       = $request->type;

        $section->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة قسم رئيسي '.$section->name);
        return back();
    }

    // # select section
    public function selectpsection(Request $request)
    {
        $request->validate([
            'id'          => 'required',
        ]);

        $sections = Local_Stock_Sections::get();
        foreach ($sections as $sec){
            if($request->id == $sec->id){
                $sec->selected       = '1';
            }else{
                $sec->selected       = '0';
            }
            $sec->save();
        }



        Session::flash('success','تم التحديد');
        MakeReport('بتحديد قسم رئيسي ');
        return back();
    }

    // # update psection
     public function Updatepsection(Request $request)
     {
         $request->validate([
             'edit_id'          => 'required',
             'edit_name'        => 'required',
         ]);

         $section = Local_Stock_Sections::findOrFail($request->edit_id);
         $section->name       = $request->edit_name;
         $section->save();

         Session::flash('success','تم التحديث');
         MakeReport('بتحديث قسم رئيسي '.$section->name);
         return back();
     }

    # delete section and his sub sections
    public function Deletepsection(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $section = Local_Stock_Sections::where('id',$request->id)->first();
        MakeReport('بحذف قسم رئيسي '.$section->name);
        $section->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    public function sortsub(Request $request)
    {
        $companyso = Local_Stock_Sub::where('id',$request->id)->first();

        $comso = Local_Stock_Sub::where('sort',$request->sort)->first();

        if($comso){
            $comso->sort = $companyso->sort;

            $comso->save();

            $companyso->sort = $request->sort;

            $companyso->save();
    
            Session::flash('success','تم حفظ التعديلات');
            MakeReport('بتحديث قسم فرعي '.$companyso->name);
            return back();
        }else{
            $companyso->sort = $request->sort;

            $companyso->save();
    
            Session::flash('success','تم حفظ التعديلات');
            MakeReport('بتحديث قسم فرعي '.$companyso->name);
            return back();
        }

        
    }


  
    # sub sections
    public function SubSections($id = null)
    {
        if(is_null($id))
        {
            $sections = Local_Stock_Sub::with('Section')->latest()->get();
        }else{
            $majs = Sec_All::where('section_id' , $id)->pluck('sub_id')->toArray();
            if(count($majs) == 0)
            { 
                $sections = Local_Stock_Sub::with('Section')->where('section_id',$id)->latest()->get();
            }else{
                $sections = Local_Stock_Sub::with('Section')->where('section_id',$id)->orWhereIn('id',$majs)->latest()->get();
            }
          
        }
        $main_sections = Local_Stock_Sections::latest()->get();
        return view('localstock::sections.sections',compact('sections','main_sections'));
    }

    # add section
    public function addsection()
    {
        $main_sections = Local_Stock_Sections::latest()->get();
        return view('localstock::sections.add_section',compact('main_sections'));
    }

    # add section
    public function addsections()
    {
        $main_sections = Local_Stock_Sections::latest()->get();
        return view('localstock::sections.add_sections',compact('main_sections'));
    }


    # add section
    public function Storesections(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'image'         => 'required'
        ]);

        $section             = new Local_Stock_Sub;
        $section->name       = $request->name;
        $section->note       = $request->note;
        $section->section_id = $request->section_id;
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/sections/sub/'.$name);
            $section->image=$name;
        }
        $section->save();

        $columns             = new Local_Stock_Columns;
        $columns->name       = 'السعر';
        $columns->section_id = $section->id;
        $columns->type = 'price';
        $columns->save();

        $columns             = new Local_Stock_Columns;
        $columns->name       = 'مقدار التغير';
        $columns->section_id = $section->id;
        $columns->type = 'change';
        $columns->save();

        $columns             = new Local_Stock_Columns;
        $columns->name       = 'حالة التغير';
        $columns->section_id = $section->id;
        $columns->type = 'state';
        $columns->save();

        if(count($request->column_name) > 0)
        {

            foreach($request->column_name as $column_name)
            {
                if($column_name != null)
                {
                $columns             = new Local_Stock_Columns;
                $columns->name       = $column_name;
                $columns->section_id = $section->id;
                $columns->save();

                }
            }
        }

        $datass  = Local_Stock_Sections::whereIn('id',$request->sections)->get();
        if($datass)
        {
            foreach($datass as $s){
                $secs = new Sec_All;
                $secs->sub_id = $section->id;
                $secs->section_id = $s->id;
                $secs->save();
            }
        }

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة قسم رئيسي '.$section->name);
        return back();
    }

    # add section
    public function Storesection(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'image'         => 'required'
        ]);

        $section             = new Local_Stock_Sub;
        $section->name       = $request->name;
        $section->note       = $request->note;
        $section->section_id = $request->section_id;
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/sections/sub/'.$name);
            $section->image=$name;
        }
        $section->save();

        $columns             = new Local_Stock_Columns;
        $columns->name       = 'السعر';
        $columns->section_id = $section->id;
        $columns->type = 'price';
        $columns->save();

        $columns             = new Local_Stock_Columns;
        $columns->name       = 'مقدار التغير';
        $columns->section_id = $section->id;
        $columns->type = 'change';
        $columns->save();

        $columns             = new Local_Stock_Columns;
        $columns->name       = 'حالة التغير';
        $columns->section_id = $section->id;
        $columns->type = 'state';
        $columns->save();

        if(count($request->column_name) > 0)
        {

            foreach($request->column_name as $column_name)
            {
                if($column_name != null)
                {
                $columns             = new Local_Stock_Columns;
                $columns->name       = $column_name;
                $columns->section_id = $section->id;
                $columns->save();
  
                }
            }
        }

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة قسم رئيسي '.$section->name);
        return back();
    }

    # edit section
    public function Editsection($id)
    {
        $section = Local_Stock_Sub::with('LocalStockColumns')->where('id',$id)->first();
        $main_sections = Local_Stock_Sections::latest()->get();
        $secs = Sec_All::where('sub_id' , $id)->pluck('section_id')->toArray();
        if(!$secs){
            return view('localstock::sections.edit_section',compact('section','main_sections'));
        }else{
            return view('localstock::sections.edit_sections',compact('section','main_sections','secs'));
        }
        
    }

     # update section
     public function Updatesections(Request $request)
     {
         $request->validate([
             'edit_id'          => 'required',
             'edit_name'        => 'required',
         ]);
 
         $section = Local_Stock_Sub::findOrFail($request->edit_id);
         $section->name       = $request->edit_name;
         $section->note       = $request->edit_note;
         $section->section_id = $request->section_id;
         if(!is_null($request->image))
         {
             $photo=$request->image;
             $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
             Image::make($photo)->save('uploads/sections/sub/'.$name);
             $section->image=$name;
         }
         $section->save();

         Sec_All::where('sub_id',$section->id)->delete();
         $datass  = Local_Stock_Sections::whereIn('id',$request->sections)->get();
         if($datass)
         {
             foreach($datass as $s){
                 $secs = new Sec_All;
                 $secs->sub_id = $section->id;
                 $secs->section_id = $s->id;
                 $secs->save();
             }
         }
         Session::flash('success','تم التحديث');
         MakeReport('بتحديث قسم رئيسي '.$section->name);
         return back();
     }
 

    # update section
    public function Updatesection(Request $request)
    {
        $request->validate([
            'edit_id'          => 'required',
            'edit_name'        => 'required',
        ]);

        $section = Local_Stock_Sub::findOrFail($request->edit_id);
        $section->name       = $request->edit_name;
        $section->note       = $request->edit_note;
        $section->section_id = $request->section_id;
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/sections/sub/'.$name);
            $section->image=$name;
        }
        $section->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث قسم رئيسي '.$section->name);
        return back();
    }

    # delete section
    public function Deletesection(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $section = Local_Stock_Sub::where('id',$request->id)->first();
        
        MakeReport('بحذف قسم رئيسي '.$section->name);
        $section->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # add columns
    public function columnStore(Request $request)
    {
        $request->validate([
        'name'     => 'required'
        ]);

        $section = Local_Stock_Sub::with('LocalStockColumns')->where('id',$request->id)->first();
        $columns_count = count($section->LocalStockColumns);

        $columns             = new Local_Stock_Columns;
        $columns->name       = $request->name;
        $columns->section_id = $request->id;
        $columns->save();

        # movements
        $movements = Local_Stock_Movement::where('section_id',$request->id)->with('LocalStockMember','LocalStockDetials')->get();
        foreach($movements as $movement)
        {
            if(count($movement->LocalStockDetials) ==  $columns_count)
            {
                $det = new Local_Stock_Detials() ;
                $det->movement_id =  $movement->id;
                $det->member_id   =  $movement->LocalStockMember->id;
                $det->section_id  =  $request->id;
                $det->column_id   =  $columns->id;
                $det->value       =  null;
                $det->save();
            }
        }

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة عمود   '.$columns->name .'  لقسم رئيسي '.$section->name);
        return back();
    }

    # update column 
    public function updatecolumn(Request $request)
    {
        $request->validate([
            'edit_columns_name'  => 'required|max:500',
        ]);

        $columns       = Local_Stock_Columns::findOrFail($request->edit_columns_id);
        $columns->name = $request->edit_columns_name;
        $columns->save();
        $section = Local_Stock_Sub::with('LocalStockColumns')->where('id',$columns->section_id)->first();
        Session::flash('success','تم التحديث');
        MakeReport('بتحديث عمود '.$columns->name .'  لقسم رئيسي '.$section->name);
        return back();
    }

    # delete columns
    public function Deletecolumn(Request $request)
    {
        $columns = Local_Stock_Columns::where('id',$request->id)->first();
        $section = Local_Stock_Sub::with('LocalStockColumns')->where('id',$columns->section_id)->first();
        MakeReport('بحذف عمود '.$columns->name .'  لقسم رئيسي '.$section->name);
        $columns->delete();
        Session::flash('success','تم الحذف');
        return back();
    }	

}
