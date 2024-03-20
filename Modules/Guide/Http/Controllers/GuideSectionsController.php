<?php

namespace Modules\Guide\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Guide\Entities\Guide_Section;
use Modules\Guide\Entities\Guide_Sub_Section;
use Modules\Guide\Entities\Companies_Sec;
use Modules\Guide\Entities\Company;
use App\Main;
use Session;
use Image;
use File;
use View;

class GuideSectionsController extends Controller
{
    public function index()
    {
        $sections = Guide_Section::with('SubSections')->latest()->get();
        $sectionss = Main::latest()->get();
    	return view('guide::sections.sections',compact('sections','sectionss'));
    }

    public function comps($id)
    {
        $majs = Companies_Sec::where('sub_section_id' , $id)->pluck('company_id')->toArray();
        $section = Guide_Sub_Section::where('id',$id)->first();
        $companies = Company::whereIn('id',$majs)->orderby('sort')->paginate(100);
        return view('guide::sections.sectioncomp',compact('companies','section'));
    }

    # company result
    public function companyajaxsec(Request $request)
    {
        $majs = Companies_Sec::where('sub_section_id' , $request->id)->pluck('company_id')->toArray();
        $datas = Company::where('name' , 'like' , "%". $request->search ."%")->whereIn('id',$majs)->with('sections','SubSections')->orderby('sort')->get();
        return $datas;
    }

    public function sortsub(Request $request)
    {
        $companyso = Guide_Sub_Section::where('id',$request->id)->first();

        $comso = Guide_Sub_Section::where('sort',$request->sort)->first();

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


    # add section
    public function Store(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'type'         => 'required',

        ]);


        $section = new Guide_Section;
        $section->name       = $request->name;
        $section->type       = $request->type;

        $section->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة قسم رئيسي '.$section->name);
        return back();
    }

    // # update section
     public function Update(Request $request)
     {
         $request->validate([
             'edit_id'          => 'required',
             'edit_name'        => 'required',
         ]);

         $section = Guide_Section::findOrFail($request->edit_id);
         $section->name       = $request->edit_name;

    
         $section->save();

         Session::flash('success','تم التحديث');
         MakeReport('بتحديث قسم رئيسي '.$section->name);
         return back();
     }

    // # update section
    public function select(Request $request)
    {
        $request->validate([
            'id'          => 'required',
        ]);

        $sections = Guide_Section::get();
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

    # delete section and his sub sections
    public function Delete(Request $request)
    {
        $request->validate([
                'id'        => 'required'
            ]);

        $section = Guide_Section::where('id',$request->id)->first();
        
        MakeReport('بحذف قسم رئيسي '.$section->name);
        $section->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # sub sections
    public function SubSections($id = null)
    {
        if(is_null($id))
        {
            $sections = Guide_Sub_Section::with('Section')->latest()->get();
        }else{
            $sections = Guide_Sub_Section::with('Section')->where('section_id',$id)->latest()->get();
        }
        $main_sections = Guide_Section::latest()->get();
        return view('guide::sections.sub_sections',compact('sections','main_sections'));
    }

    # store sub section
    public function StoreSubSection(Request $request)
    {
        $request->validate([
                'name'         => 'required',
                'type'         => 'required',
                'section_id'   => 'required',
                'image'          => 'required',
            ]);

            $section = new Guide_Sub_Section;
            $section->name       = $request->name;
            $section->type       = $request->type;
            $section->section_id = $request->section_id;

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
                'edit_type'        => 'required',
                'edit_section_id'  => 'required',
            ]);

            $section = Guide_Sub_Section::findOrFail($request->edit_id);
            $section->name       = $request->edit_name; 
            $section->type       = $request->edit_type;   
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

        $section = Guide_Sub_Section::where('id',$request->id)->first();
        
        File::delete('uploads/sections/avatar/'.$section->image);
        MakeReport('بحذف قسم فرعي '.$section->name);
        $section->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
}
 
 