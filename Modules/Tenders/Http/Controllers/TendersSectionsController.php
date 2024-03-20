<?php

namespace Modules\Tenders\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Tenders\Entities\Tender_Section;
use App\Main;
use Session;
use Image;
use File;
use View;

class TendersSectionsController extends Controller
{
    public function index()
    {
        $sections = Tender_Section::latest()->get();
//        $sectionss = Main::latest()->get();
    	return view('tenders::sections.sections',compact('sections'));
    }



//    # add section
//    public function Store(Request $request)
//    {
//        $request->validate([
//            'id'         => 'required',
//        ]);
//        $main = Main::where('id',$request->id)->latest()->first();
//
//        $section = new Tender_Section;
//        $section->name       = $main->name;
//        $section->type       = $main->type;
//        $section->save();
//
//        Session::flash('success','تم الحفظ');
//        MakeReport('بإضافة قسم رئيسي '.$section->name);
//        return back();
//    }
#add main sector
    public function Store (Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'image'         => 'required',
        ]);

        $section = new Tender_Section;
        $section->name       = $request->name;
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/tenders/home/'.$name);
            $section->image =$name;
        }
        $section->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة قسم مناقصات '.$section->name);
        return back();
    }

     # update section
     public function Update(Request $request)
     {
         $request->validate([
             'edit_id'          => 'required',
             'edit_name'        => 'required',
//             'edit_image'        => 'required',

         ]);

         $section = Tender_Section::findOrFail($request->edit_id);
         $section->name       = $request->edit_name;
         if(!is_null($request->edit_image))
         {
             File::delete('uploads/tenders/home/'.$section->image);
             $photo=$request->edit_image;

             $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
             Image::make($photo)->save('uploads/tenders/home/'.$name);
             $section->image =$name;
         }
    
         $section->save();

         Session::flash('success','تم التحديث');
         MakeReport('بتحديث قسم رئيسي '.$section->name);
         return back();
     }

    // # select section
    public function select(Request $request)
    {
        $request->validate([
            'id'          => 'required',
        ]);

        $sections = Tender_Section::get();
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

        $section = Tender_Section::where('id',$request->id)->first();
        
        MakeReport('بحذف قسم رئيسي '.$section->name);
        $section->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    
}
 
 