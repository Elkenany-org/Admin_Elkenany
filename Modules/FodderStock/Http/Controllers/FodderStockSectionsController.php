<?php

namespace Modules\FodderStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FodderStock\Entities\Stock_Fodder_Section;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use App\Main;

use Session;
use Image;
use File;
use View;

class FodderStockSectionsController extends Controller
{
    # index
    public function index()
    {
        $sections = Stock_Fodder_Section::latest()->get();
        $sectionss = Main::latest()->get();
        return view('fodderstock::sections.sections',compact('sections','sectionss'));
    }

    # add psection
    public function Storepsection(Request $request)
    {
        $request->validate([
            'name'         => 'required',        'type'         => 'required',
        ]);


        $section = new Stock_Fodder_Section;
        $section->name       = $request->name;
        $section->type       = $request->type;
        $section->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة قسم رئيسي '.$section->name);
        return back();
    }

    // # update psection
     public function Updatepsection(Request $request)
     {
         $request->validate([
             'edit_id'          => 'required',
             'edit_name'        => 'required',
         ]);

         $section = Stock_Fodder_Section::findOrFail($request->edit_id);
         $section->name       = $request->edit_name;
//         if(!is_null($request->edit_image))
//         {
//
//             File::delete('uploads/sections/avatar/'.$section->image);
//             $photo=$request->edit_image;
//
//             $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
//             Image::make($photo)->save('uploads/sections/food/'.$name);
//             $section->image =$name;
//         }
         $section->save();

         Session::flash('success','تم التحديث');
         MakeReport('بتحديث قسم رئيسي '.$section->name);
         return back();
     }

    // # select section
    public function selectpsection(Request $request)
    {
        $request->validate([
            'id'          => 'required',
        ]);

        $sections = Stock_Fodder_Section::get();
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
    # delete section
    public function Deletepsection(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $section = Stock_Fodder_Section::where('id',$request->id)->first();
        MakeReport('بحذف قسم رئيسي '.$section->name);
        $section->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    public function sortsub(Request $request)
    {
        $companyso = Stock_Fodder_Sub::where('id',$request->id)->first();

        $comso = Stock_Fodder_Sub::where('sort',$request->sort)->first();


          
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
            $sections = Stock_Fodder_Sub::with('Section')->latest()->get();
        }else{
            $sections = Stock_Fodder_Sub::with('Section')->where('section_id',$id)->latest()->get();
        }
        $main_sections = Stock_Fodder_Section::latest()->get();
        return view('fodderstock::sections.sub_sections',compact('sections','main_sections'));
    }

    # store sub section
    public function StoreSubSection(Request $request)
    {
        $request->validate([
                'name'         => 'required',
                'section_id'   => 'required',
                'image'        => 'required|mimes:jpeg,png,jpg,gif,svg',
            ]);

            $section = new Stock_Fodder_Sub;
            $section->name       = $request->name;
            $section->section_id  = $request->section_id;

            if(!is_null($request->image))
            {
                $photo=$request->image;
                $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
                Image::make($photo)->save('uploads/sections/avatar/'.$name);
                $section->image =$name;
            }
        $section->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة قسم فرعي '.$section->name);
        return back();
    }

    # update subsection
    public function UpdateSubSection(Request $request)
    {
        $request->validate([
                'edit_id'          => 'required',
                'edit_name'        => 'required',
                'edit_section_id'  => 'required',
                'edit_image'        => 'mimes:jpeg,png,jpg,gif,svg',
            ]);

            $section = Stock_Fodder_Sub::findOrFail($request->edit_id);
            $section->name       = $request->edit_name;    
            $section->section_id = $request->edit_section_id;
            if(!is_null($request->edit_image))
            {

                File::delete('uploads/sections/avatar/'.$section->image);
                $photo=$request->edit_image;
            
                $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
                Image::make($photo)->save('uploads/sections/avatar/'.$name);
                $section->image =$name;
            }
        $section->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث قسم فرعي '.$section->name);
        return back();
    }

    # delete sub section 
    public function DeleteSubSection(Request $request)
    {
        $request->validate([
                'id'        => 'required'
            ]);

        $section = Stock_Fodder_Sub::where('id',$request->id)->first();
        
        File::delete('uploads/sections/avatar/'.$section->image);
        MakeReport('بحذف قسم فرعي '.$section->name);
        $section->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

}
