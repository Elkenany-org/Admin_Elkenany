<?php

namespace Modules\Shows\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shows\Entities\Show_Section;
use Modules\Shows\Entities\Organ;
use App\Main;
use Session;
use Image;
use File;
use View;

class showSectionsController extends Controller
{
    public function index()
    {
        $sections = Show_Section::latest()->get();
        $sectionss = Main::latest()->get();
    	return view('shows::sections.sections',compact('sections','sectionss'));
    }

    # add section
    public function Store(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'type'         => 'required',

        ]);

        $section = new Show_Section;
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

         $section = Show_Section::findOrFail($request->edit_id);
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

        $sections = Show_Section::get();
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

        $section = Show_Section::where('id',$request->id)->first();
        
        MakeReport('بحذف قسم رئيسي '.$section->name);
        $section->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    public function indexorgan()
    {
        $organisers = Organ::latest()->get();
    	return view('shows::organisers.organisers',compact('organisers'));
    }

    # add organisers
    public function Storeorgan(Request $request)
    {
        $request->validate([
            'name'         => 'required',
        ]);

        $organisers = new Organ;
        $organisers->name       = $request->name;
        $organisers->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة جهة منظمة '.$organisers->name);
        return back();
    }

    # update organisers
    public function Updateorgan(Request $request)
    {
        $request->validate([
            'edit_id'          => 'required',
            'edit_name'        => 'required',
        ]);

        $organisers = Organ::findOrFail($request->edit_id);
        $organisers->name       = $request->edit_name;

    
        $organisers->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  جهة منظمة '.$organisers->name);
        return back();
    }
 
    # delete organisers
    public function Deleteorgan(Request $request)
    {
        $request->validate([
                'id'        => 'required'
            ]);

        $organisers = Organ::where('id',$request->id)->first();
        
        MakeReport('بحذف  جهة منظمة '.$organisers->name);
        $organisers->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

   
}
 
 