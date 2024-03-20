<?php

namespace App\Http\Controllers;
use App\MainImages;
use Illuminate\Http\Request;
use App\Main;
use Modules\Guide\Entities\Services;
use Session;
use View;
use Image;
use File;

class MainSectionController extends Controller
{
    # index
    public function index()
    {
        $sections = Main::latest()->get();
        return view('sections.sections',compact('sections'));
    }

    # add psection
    public function Storepsection(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'type'         => 'required',
        ]);

        $section = new Main;
        $section->name       = $request->name;
        $section->type       = $request->type;
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/main/'.$name);
            $section->image =$name;
        }
        $section->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة قسم رئيسي '.$section->name);
        return back();
    }
    #add main sector
    public function Store (Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'type'         => 'required',
        ]);

        $section = new Main;
        $section->name       = $request->name;
        $section->type       = $request->type;
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/main/'.$name);
            $section->image =$name;
        }
        $section->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة قسم رئيسي '.$section->name);
        return back();
    }

    # update psection
    public function Updatepsection(Request $request)
    {
        $request->validate([
            'edit_id'          => 'required',
        ]);

        $section = Main::findOrFail($request->edit_id);
        if(!is_null($request->edit_image))
        {

            File::delete('uploads/main/'.$section->image);
            $photo=$request->edit_image;
        
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/main/'.$name);
            $section->image =$name;
        }
        $section->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث قسم رئيسي '.$section->name);
        return back();
    }



    public function MainImages(){
        $images=MainImages::with('Service','Visited','Newest')->get();
        $services=Services::all();
        $types=['cta','claim','questions','app','howtouse'];
        return view('mainpage.index',compact('images','services','types'));

    }

    public function StoreImage(Request $request){
        $request->validate([
            'image'         => 'required',
            'desc'          => 'required',
            'type'          => 'required_without_all:visited,newest,services',
            'services'      => 'required_without_all:type,visited,newest',
            'visited'       =>'required_without_all:type,newest,services',
            'newest'        =>'required_without_all:type,visited,services',
            'link'          => 'required',
        ]);

        $mainImage = new MainImages();
        $mainImage->description       = $request->desc;
        $mainImage->link       = $request->link;

        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/main/home/'.$name);
            $mainImage->image =$name;
        }
        if(!is_null($request->type))
        {
            $mainImage->type       = $request->type;

        }
        if(!is_null($request->services))
        {
            $mainImage->services       = $request->services;

        }
        if(!is_null($request->visited))
        {
            $mainImage->most_visited       = $request->visited;

        }
        if(!is_null($request->newest))
        {
            $mainImage->newest       = $request->newest;

        }
        $mainImage->save();

        Session::flash('success','تم الحفظ');
        MakeReport(' بإضافة صورة رئيسية ');
        return back();
    }

    public function UpdateImage(Request $request){
        $request->validate([
            'edit_id'          => 'required',
        ]);

        $image = MainImages::findOrFail($request->edit_id);
        if(!is_null($request->edit_image))
        {
            File::delete('uploads/main/home/'.$image->image);
            $photo=$request->edit_image;

            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/main/home/'.$name);
            $image->image =$name;
        }
        if(!is_null($request->edit_desc))
        {
            $image->description       = $request->edit_desc;

        }
        if(!is_null($request->edit_type))
        {
            $image->type       = $request->edit_type;

        }
        if(!is_null($request->edit_services))
        {
            $image->services       = $request->edit_services;

        }
        if(!is_null($request->edit_visited))
        {
            $image->most_visited       = $request->edit_visited;

        }
        if(!is_null($request->edit_newest))
        {
            $image->newest       = $request->edit_newest;

        }
        if(!is_null($request->edit_link))
        {
            $image->link       = $request->edit_link;
        }

        $image->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث صورة رئيسية ');
        return back();

    }

    public function DeleteImage(Request $request){
        $request->validate([
            'id'         => 'required',
        ]);

        $image = MainImages::findOrFail($request->id);
        File::delete('uploads/main/home/'.$image->image);
        $image->delete();
        Session::flash('danger','تم الحذف');
        MakeReport(' بحذف صورة رئيسية ');
        return back();
    }
}
