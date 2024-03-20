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
use Modules\MedicineStock\Entities\Medic_member;
use Modules\MedicineStock\Entities\Medic_move;
use App\Main;

use Session;
use Image;
use File;
use View;

class MedicineStockSectionsController extends Controller
{
   # index
   public function index()
   {
       $sections = Medic_Section::latest()->get();
//       $sectionss = Main::latest()->get();
       return view('medicinestock::sections.sections',compact('sections'));
   }

   # add psection
   public function Storepsection(Request $request)
   {
       $request->validate([
           'name'         => 'required',
           'type'         => 'required',

       ]);

       $section = new Medic_Section;
       $section->name       = $request->name;
       $section->type       = $request->type;
     
       $section->save();

       Session::flash('success','تم الحفظ');
       MakeReport('بإضافة قسم رئيسي '.$section->name);
       return back();
   }

    // # update section
    public function Updatepsection(Request $request)
    {
        $request->validate([
            'edit_id'          => 'required',
            'edit_name'        => 'required',
        ]);

        $section = Medic_Section::findOrFail($request->edit_id);
        $section->name       = $request->edit_name;


        $section->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث قسم رئيسي '.$section->name);
        return back();
    }

    public function selectpsection(Request $request)
    {
        $request->validate([
            'id'          => 'required',
        ]);

        $sections = Medic_Section::get();
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

       $section = Medic_Section::where('id',$request->id)->first();
       MakeReport('بحذف قسم رئيسي '.$section->name);
       $section->delete();
       Session::flash('success','تم الحذف');
       return back();
   }

   public function sortsub(Request $request)
   {
       $companyso = Medic_Stock::where('id',$request->id)->first();

       $comso = Medic_Stock::where('sort',$request->sort)->first();


         
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
           MakeReport('بتحديث بورصة ادوية '.$companyso->name);
           return back();
       }
      

       
   }

   # sub sections
   public function SubSections($id = null)
   {
       if(is_null($id))
       {
           $sections = Medic_Stock::with('Section')->latest()->get();
       }else{
           $sections = Medic_Stock::with('Section')->where('section_id',$id)->latest()->get();
       }
       $main_sections = Medic_Section::latest()->get();
       return view('medicinestock::sections.sub_sections',compact('sections','main_sections'));
   }

   # store sub section
   public function StoreSubSection(Request $request)
   {
       $request->validate([
               'name'         => 'required',
               'section_id'   => 'required',
               'image'        => 'required|mimes:jpeg,png,jpg,gif,svg',
           ]);

           $section = new Medic_Stock;
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
       MakeReport('بإضافة بورصة ادوية '.$section->name);
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

           $section = Medic_Stock::findOrFail($request->edit_id);
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
       MakeReport('بتحديث  بورصة ادوية '.$section->name);
       return back();
   }

   # delete sub section 
   public function DeleteSubSection(Request $request)
   {
       $request->validate([
               'id'        => 'required'
           ]);

       $section = Medic_Stock::where('id',$request->id)->first();
       
       File::delete('uploads/sections/avatar/'.$section->image);
       MakeReport('بحذف  بورصة ادوية '.$section->name);
       $section->delete();
       Session::flash('success','تم الحذف');
       return back();
   }

    # active subs
    public function activesubs()
    {
        $subs = Medic_Subs::latest()->get();
        return view('medicinestock::sections.subs',compact('subs'));
    }

    # add active subs
    public function Storeactive(Request $request)
    {
        $request->validate([
            'name'         => 'required',
        ]);
       

        $feed = new Medic_Subs;
        $feed->name       = $request->name;
        $feed->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة مادة فعالة  '.$feed->name);
        return back();
    }

    

    # update active subs
    public function Updateactive(Request $request)
    {
        $request->validate([
            'edit_id'          => 'required',
            'edit_name'        => 'required',
        ]);

     

        $feed = Medic_Subs::findOrFail($request->edit_id);
        $feed->name       = $request->edit_name;
        $feed->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  المادة '.$feed->name);
        return back();
    }

    # delete active subs
    public function Deleteactive(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $feed = Medic_Subs::where('id',$request->id)->first();
        MakeReport('بحذف مادة فعالة '.$feed->name);
        $feed->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # names
    public function names()
    {
        $names = Com_name::with('Company')->latest()->get();
        return view('medicinestock::names.names',compact('names'));
    }

    # names
    public function addnames()
    {
        $companies = Company::latest()->get();
        return view('medicinestock::names.names_add',compact('companies'));
    }

    # add names
    public function Storenames(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'desc'         => 'required',
            'dose'         => 'required',
            'size'         => 'required',
            'price'         => 'required',
        ]);
    

        $names = new Com_name;
        $names->name       = $request->name;
        $names->desc       = $request->desc;
        $names->dose       = $request->dose;
        $names->size       = $request->size;
        $names->price      = $request->price;
        $names->company_id  = $request->company_id;
        $names->save();

        if($request->hasfile('image'))
        {
            foreach($request->image as $image){

            $photo=$image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/sections/'.$name);
            
                $img = new Com_name_images;
                $img->name_id = $names->id;
                $img->image = $name;
                $img->save();
            }
        }

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة إسم تجاري  '.$names->name);
        return back();
    }

    # names
    public function editnames($id)
    {
        $name = Com_name::with('Company','Comnameimages')->where('id',$id)->first();
        return view('medicinestock::names.edit_names',compact('name'));
    }


    # update names
    public function updatenames(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'desc'         => 'required',
            'dose'         => 'required',
            'size'         => 'required',
            'price'         => 'required',
        ]);
    

        $names = Com_name::with('Company','Comnameimages')->where('id',$request->id)->first();
        $names->name       = $request->name;
        $names->desc       = $request->desc;
        $names->dose       = $request->dose;
        $names->size       = $request->size;
        $names->price      = $request->price;
        $names->company_id  = $request->company_id;
        $names->save();


        Session::flash('success','تم الحفظ');
        MakeReport('بتعديل إسم تجاري  '.$names->name);
        return back();
    }

    # images 
    public function storeImagesnames(Request $request)
    {
        if($request->hasfile('image'))
        {
        foreach($request->image as $image){

        $photo=$image;
        $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
        Image::make($photo)->save('uploads/sections/'.$name);
        
            $img = new Com_name_images;
            $img->name_id = $request->id;
            $img->image = $name;
            $img->save();
        }
    }

        Session::flash('success','تم الاضافة');
        MakeReport(' باضافة صور لإسم ');
        return back();
    }

    # delete image
    public function DeleteImagenames(Request $request)
    {

        $image = Com_name_images::where('id',$request->id)->first();
     
        File::delete('uploads/sections/'.$image->image);
   
        MakeReport('بحذف الصورة ');
        $image->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
    

    
    # delete names
    public function Deletenames(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $name = Com_name::where('id',$request->id)->first();
        MakeReport('بحذف  إسم تجاري '.$name->name);
        $name->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
  

}
