<?php

namespace Modules\Consultants\Http\Controllers;


use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Consultants\Entities\Major;
use Modules\Consultants\Entities\Doctor;
use Modules\Consultants\Entities\Doctor_Majors;
use Modules\Consultants\Entities\Doctor_Services;
use Modules\Consultants\Entities\Doctor_Orders;
use Modules\Consultants\Entities\Sub_Section;
use Modules\Consultants\Entities\Doctor_Link;
use Session;
use Image;
use File;
use View;
class DoctorsController extends Controller
{
    # index
    public function Index()
    {
    	$doctors = Doctor::latest()->get();
    	return view('consultants::doctors.doctors',compact('doctors'));
    }

    # add doctor page
    public function AdddoctorPage()
    {
        $majors = Major::get();
        $sections = Sub_Section::get();
    	return view('consultants::doctors.add_doctors',compact('majors','sections'));
    }

    # store doctor 
    public function Storedoctor(Request $request)
    {
        $request->validate([
            'name'            => 'required',
            'email'           => 'required|unique:doctors',
            'phone'           => 'required|unique:doctors',
            'password'        => 'required',
            'avatar'          => 'nullable|mimes:jpeg,png,jpg,gif,svg',
            'certificates'    => 'required',
            'experiences'     => 'required',
            'call_price'      => 'required',
            'call_duration'   => 'required',
            'online_price'    => 'required',
            'online_duration' => 'required',
            'meeting_price'   => 'required',
            'meeting_duration'=> 'required',
            'address'         => 'required',
        ]);

        $doctor = new Doctor;
        $doctor->name                = $request->name;
        $doctor->adress             = $request->address;
        $doctor->email               = $request->email;
        $doctor->phone               = $request->phone;
        $doctor->password            = bcrypt($request->password);
        $doctor->certificates        = $request->certificates;
        $doctor->experiences         = $request->experiences;
        $doctor->call_price          = $request->call_price;
        $doctor->call_duration       = $request->call_duration;
        $doctor->online_price        = $request->online_price;
        $doctor->online_duration     = $request->online_duration;
        $doctor->meeting_price       = $request->meeting_price;
        $doctor->meeting_duration    = $request->meeting_duration;

        # upload avatar
        if(!is_null($request->avatar))
        {
            $photo=$request->avatar;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/doctors/avatar/'.$name);
            $doctor->avatar=$name;
        }

        $doctor->save();

        $datas  = Sub_Section::with('Major')->whereIn('id',$request->SubSections)->get();

        foreach($datas as $s){
            $section = new Doctor_Majors;
            $section->sub_id = $s->id;
            $section->major_id = $s->major_id;
            $section->doctor_id = $doctor->id;
            $section->save();
        }
        MakeReport('بإضافة استشاري جديد ' .$doctor->name);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # get sub sections ajax
    public function GetSubSectionsm(Request $request)
    {
        $datas = Sub_Section::where('major_id',$request->major_id)->latest()->get();
        return $datas;
    }

    # edit doctor
    public function Editdoctor($id)
    {
        $doctor = Doctor::with('DoctorServices','DoctorLinks')->where('id',$id)->first();
        $majors = Major::get();
        $sections = Sub_Section::get();
        $majs = Doctor_Majors::where('doctor_id' , $id)->pluck('major_id')->toArray();
        $secs = Doctor_Majors::where('doctor_id' , $id)->pluck('sub_id')->toArray();
    	return view('consultants::doctors.edit_doctors',compact('doctor','majors','majs','sections','secs'));
    }

    # update doctor
    public function Updatedoctor(Request $request)
    {
        $request->validate([
            'name'            => 'required',
            'email'           => 'required|unique:doctors,email,'.$request->id,
            'phone'           => 'required|unique:doctors,phone,'.$request->id,
            'avatar'          => 'nullable|mimes:jpeg,png,jpg,gif,svg',
            'certificates'    => 'required',
            'experiences'     => 'required',
            'call_price'      => 'required',
            'call_duration'   => 'required',
            'online_price'    => 'required',
            'online_duration' => 'required',
            'meeting_price'   => 'required',
            'meeting_duration'=> 'required',
            'address'         => 'required',
        ]);

        $doctor = Doctor::where('id',$request->id)->first();
        $doctor->name                = $request->name;
        $doctor->adress             = $request->address;
        $doctor->email               = $request->email;
        $doctor->phone               = $request->phone;
        $doctor->password            = bcrypt($request->password);
        $doctor->certificates        = $request->certificates;
        $doctor->experiences         = $request->experiences;
        $doctor->call_price          = $request->call_price;
        $doctor->call_duration       = $request->call_duration;
        $doctor->online_price        = $request->online_price;
        $doctor->online_duration     = $request->online_duration;
        $doctor->meeting_price       = $request->meeting_price;
        $doctor->meeting_duration    = $request->meeting_duration;

        # password
        if(!is_null($request->password))
        {
            $doctor->password = bcrypt($request->password);
        }

        # upload avatar
        if(!is_null($request->avatar))
        {
        	# delete avatar
	    	if($doctor->avatar != 'default.png')
	    	{
	   			File::delete('uploads/doctors/avatar/'.$doctor->avatar);
	    	}

	    	# upload new avatar
            $photo=$request->avatar;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/doctors/avatar/'.$name);
            $doctor->avatar=$name;
        }


        $doctor->save();
        Doctor_Majors::where('doctor_id',$doctor->id)->delete();

        $datas  = Sub_Section::with('Major')->whereIn('id',$request->SubSections)->get();

        foreach($datas as $s){
            $section = new Doctor_Majors;
            $section->sub_id = $s->id;
            $section->major_id = $s->major_id;
            $section->doctor_id = $doctor->id;
            $section->save();
        }
        MakeReport('بتحديث استشاري ' .$doctor->name);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete doctor
    public function Deletedoctor(Request $request)
    {

    	$doctor = Doctor::where('id',$request->id)->first();
    	if($doctor->avatar != 'default.png')
    	{
   			File::delete('uploads/doctors/avatar/'.$doctor->avatar);
    	}
    	MakeReport('بحذف استشاري '.$doctor->name);
    	$doctor->delete();
    	Session::flash('success','تم الحذف');
    	return back();
    }
    
    # store servies time 
    public function Storeservies(Request $request)
    {
        $request->validate([
            'services_type'    => 'required',
            'time_from'        => 'required',
            'time_to'          => 'required',
            'date'             => 'required',
        ]);

        $servies = new Doctor_Services;
        $servies->services_type       = $request->services_type;
        $servies->time_from           = $request->time_from;
        $servies->time_to             = $request->time_to;
        $servies->date                = $request->date;
        $servies->doctor_id           = $request->doctor_id;

        $servies->save();

        $doctor = Doctor::where('id',$servies->doctor_id)->first();
        MakeReport('بإضافة  وقت ' .$servies->services_type.' استشاري '.$doctor->name);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # update servies time 
    public function updateservies(Request $request)
    {
        $request->validate([
            'edit_services_type'    => 'required',
            'edit_time_from'        => 'required',
            'edit_time_to'          => 'required',
            'edit_date'             => 'required',
        ]);

        $servies = Doctor_Services::where('id',$request->ser_id)->first();
        $servies->services_type       = $request->edit_services_type;
        $servies->time_from           = $request->edit_time_from;
        $servies->time_to             = $request->edit_time_to;
        $servies->date                = $request->edit_date;

        $servies->save();
        $doctor = Doctor::where('id',$servies->doctor_id)->first();
        MakeReport('بتحديث  وقت ' .$servies->services_type.' استشاري '.$doctor->name);
        Session::flash('success','تم التحديث');
        return back();
    }

    # delete servies time 
    public function Deleteservies(Request $request)
    {

        $servies = Doctor_Services::where('id',$request->id)->first();
        $doctor = Doctor::where('id',$servies->doctor_id)->first();
        MakeReport('بحذف وقت '.$servies->services_type.' استشاري '.$doctor->name);
        $servies->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # store links
    public function Storelinks(Request $request)
    {
        $request->validate([
            'title'    => 'required',
            'link'     => 'required',
            'image'    => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $links = new Doctor_Link;
        $links->title       = $request->title;
        $links->link        = $request->link;
        $links->doctor_id   = $request->doctor_id;
        # upload image
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/doctors/image/'.$name);
            $links->image=$name;
        }
        $links->save();
        $doctor = Doctor::where('id',$links->doctor_id)->first();
        MakeReport('بإضافة  لينك ' .$links->title.' استشاري '.$doctor->name);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # update link 
    public function updatelink(Request $request)
    {
        $request->validate([
            'edit_title'    => 'required',
            'edit_link'     => 'required',
            'edit_image'    => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        $links = Doctor_Link::where('id',$request->link_id)->first();
        $links->title       = $request->edit_title;
        $links->link        = $request->edit_link;
        # upload image
        if(!is_null($request->edit_image))
        {
            File::delete('uploads/doctors/image/'.$links->image);
            $photo=$request->edit_image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/doctors/image/'.$name);
            $links->image=$name;
        }
        $links->save();
        $doctor = Doctor::where('id',$links->doctor_id)->first();
        MakeReport('بتحديث  لينك ' .$links->title.' استشاري '.$doctor->name);
        Session::flash('success','تم التحديث');
        return back();
    }

    # delete link
    public function Deletelink(Request $request)
    {

        $links = Doctor_Link::where('id',$request->id)->first();
        File::delete('uploads/doctors/image/'.$links->image);
        $doctor = Doctor::where('id',$links->doctor_id)->first();
        MakeReport('بحذف لينك '.$links->title.' استشاري '.$doctor->name);
        
        $links->delete();
        
        Session::flash('success','تم الحذف');
        return back();
    }

    # orders for doctor
    public function showorder($id)
    {
        $doctor = Doctor::with('DoctorOrders','DoctorOrders.Customer','DoctorOrders.DoctorServices')->where('id',$id)->first();
        return view('consultants::doctors.order',compact('doctor'));
    }

    # orders
    public function showorders()
    {
    	$orders = Doctor_Orders::with('Doctor','Customer','DoctorServices')->latest()->get();
    	return view('consultants::doctors.orders',compact('orders'));
    }

    # show order
    public function ShowOrd($id)
    {
        $order = Doctor_Orders::with('Doctor','Customer','DoctorServices')->where('id',$id)->first();

        return view('consultants::doctors.order_show',compact('order'));
    }

    # delete orders  
    public function Deleteorders(Request $request)
    {

        $orders = Doctor_Orders::where('id',$request->id)->first();
        $doctor = Doctor::where('id',$orders->doctor_id)->first();
        MakeReport('بحذف طلب '.$orders->id.' استشاري '.$doctor->name);
        $orders->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
 

}
