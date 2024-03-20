<?php

namespace Modules\Consultants\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Consultants\Entities\Major;
use Modules\Consultants\Entities\Sub_Section;
use Session;
use App\Main;
use Image;
use File;
use View;
class MajorsController extends Controller
{
    # majors
    public function Index()
    {
        $majors = Major::latest()->get();
        $sectionss = Main::latest()->get();
    	return view('consultants::majors.majors',compact('majors','sectionss'));
    }

    # add major
    public function Storemajor(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'image'        => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $main = Main::where('id',$request->id)->latest()->first();

        $major = new Major;
        $section->name       = $main->name;
        $section->type       = $main->type;
     


        $major->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة تخصص '.$major->name);
        return back();
    }

    # update major
    public function Updatemajor(Request $request)
    {
        $request->validate([
            'edit_name'         => 'required',
            'edit_image'        => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        $major = Major::findOrFail($request->edit_id);
        $major->name       = $request->edit_name;
       

        $major->save();
        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  تخصص '.$major->name);
        return back();
    }

    # delete major
    public function Deletemajor(Request $request)
    {
      
        $major = Major::where('id',$request->id)->first();

        File::delete('uploads/majors/avatar/'.$major->image);
        MakeReport('بحذف  تخصص '.$major->name);
        $major->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # Sub Section
    public function IndexSubSection()
    {
        $sections = Sub_Section::with('Major')->latest()->get();
        $majors = Major::latest()->get();
        return view('consultants::majors.sections',compact('sections','majors'));
    }

    # add Sub Section
    public function Storsections(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'image'        => 'required|mimes:jpeg,png,jpg,gif,svg',
            'major_id'   => 'required',
        ]);

        $section = new Sub_Section;
        $section->name      = $request->name;
        $section->major_id  = $request->major_id;
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/majors/avatar/'.$name);
            $section->image =$name;
        }


        $section->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة قسم فرعي '.$section->name);
        return back();
    }

    # update Sub Section
    public function Updatesection(Request $request)
    {
        $request->validate([
            'edit_name'         => 'required',
            'edit_image'        => 'mimes:jpeg,png,jpg,gif,svg',
            'edit_major_id'  => 'required',
        ]);

        $section = Sub_Section::findOrFail($request->edit_id);
        $section->name       = $request->edit_name;
        $section->major_id  = $request->edit_major_id;
        if(!is_null($request->edit_image))
        {

            File::delete('uploads/majors/avatar/'.$section->image);
            $photo=$request->edit_image;
        
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/majors/avatar/'.$name);
            $section->image =$name;
        }

        $section->save();
        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  قسم فرعي '.$section->name);
        return back();
    }

    # delete Sub Section
    public function Deletesection(Request $request)
    {
    
        $section = Sub_Section::where('id',$request->id)->first();

        File::delete('uploads/majors/avatar/'.$section->image);
        MakeReport('بحذف  قسم فرعي '.$section->name);
        $section->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

}
