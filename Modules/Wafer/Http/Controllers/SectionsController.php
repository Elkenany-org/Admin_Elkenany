<?php

namespace Modules\Wafer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wafer\Entities\Wafer_Section;
use Session;
use Image;
use File;
use View;
class SectionsController extends Controller
{

    # sections
    public function Index()
    {
    	$sections = Wafer_Section::latest()->get();
    	return view('wafer::sections.sections',compact('sections'));
    }

    # add section
    public function Storesection(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'image'        => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $section = new Wafer_Section;
        $section->name       = $request->name;
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/sections/avatar/'.$name);
            $section->image =$name;
        }


        $section->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة سكشن '.$section->name);
        return back();
    }

    # update section
    public function Updatesection(Request $request)
    {
        $request->validate([
            'edit_name'         => 'required',
            'edit_image'        => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        $section = Wafer_Section::findOrFail($request->edit_id);
        $section->name       = $request->edit_name;
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
        MakeReport('بتحديث  سكشن '.$section->name);
        return back();
    }

    # delete section
    public function Deletesection(Request $request)
    {
    
        $section = Wafer_Section::where('id',$request->id)->first();

        File::delete('uploads/sections/avatar/'.$section->image);
        MakeReport('بحذف  سكشن '.$section->name);
        $section->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

   
}
