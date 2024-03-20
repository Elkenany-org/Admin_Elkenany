<?php

namespace Modules\Magazines\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Magazines\Entities\Mag_Section;
use App\Main;
use Session;
use Image;
use File;
use View;

class MagazinesSectionsController extends Controller
{
    public function index()
    {
        $sections = Mag_Section::latest()->get();
        $sectionss = Main::latest()->get();
    	return view('magazines::sections.sections',compact('sections','sectionss'));
    }

    # add section
    public function Store(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'type'         => 'required',

        ]);

        $section = new Mag_Section;
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

         $section = Mag_Section::findOrFail($request->edit_id);
         $section->name       = $request->edit_name;

    
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

        $sections = Mag_Section::get();
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

        $section = Mag_Section::where('id',$request->id)->first();
        
        MakeReport('بحذف قسم رئيسي '.$section->name);
        $section->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

   

}
 
 