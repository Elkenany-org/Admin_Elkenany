<?php

namespace Modules\Consultants\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Consultants\Entities\Major;
use Modules\Consultants\Entities\Doctor;
use Modules\Consultants\Entities\Doctor_Majors;
use Modules\Consultants\Entities\Sub_Section;
use Modules\Consultants\Entities\Doctor_Services;
use Modules\Consultants\Entities\Doctor_Orders;
use Modules\Consultants\Entities\Doctor_Rating;
use Illuminate\Support\Facades\Input;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Carbon\Carbon;
use Session;
use Image;
use File;
use View;
use Auth;
class frontConsultantsController extends Controller
{
    # majors
    public function Index()
    {
    	$majors = Major::latest()->get();
    	return view('consultants::fronts.majors',compact('majors'));
    }

    # doctors
    public function subsections($name)
    {
        $sort  = Input::get("sort");
        if(is_null($sort))
        {
            $major = Major::with('SubSections')->where('type' , $name)->first();
            $sections = Sub_Section::with('Doctor')->where('major_id',$major->id)->orderby('name')->get();
            $secs = Major::get();
            $sort= '0';
        }else{
            $major = Major::with('SubSections')->where('type',$name)->first();
            $sections = Sub_Section::with('Doctor')->where('major_id',$major->id)->orderBy('view_count' , 'desc')->get();
            $secs = Major::get();
            $sort= '1';
        }

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$major->type)->where('type','consultants')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('main','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::where('main','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
        
        return view('consultants::fronts.sections',compact('major','sections','secs','sort','adss','logos'));
    }

    # get sections ajax
    public function GetSubSections(Request $request)
    {
        $datas = Sub_Section::with('Major','Doctor')->where('major_id',$request->section_id)->latest()->get();

        $seco = Major::where('id',$request->section_id)->first();

        $subss = Sub_Section::with('Major','Doctor')->where('major_id',$request->section_id)->pluck('id')->toArray();

        $page = System_Ads_Pages::with('SystemAds')->whereIn('sub_id',$subss)->where('type','consultants')->pluck('ads_id')->toArray();

        $ads = System_Ads::with('SystemAdsPages')->where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        return response()->json(['datas' => $datas , 'seco' => $seco, 'ads' => $ads,], 200);
    }

    # get sub sections ajax
    public function GetSubSectionsserchname(Request $request)
    {
        $seco = Major::where('id',$request->section_id)->first();
        $datas = Sub_Section::with('Major','Doctor')->where('major_id',$request->section_id)->where('name' , 'like' , "%". $request->search ."%")->take(50)->latest()->get();
        $subss = Sub_Section::with('Major','Doctor')->where('major_id',$request->section_id)->pluck('id')->toArray();

        $page = System_Ads_Pages::with('SystemAds')->whereIn('sub_id',$subss)->where('type','consultants')->pluck('ads_id')->toArray();

        $ads = System_Ads::with('SystemAdsPages')->where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        return response()->json(['datas' => $datas , 'seco' => $seco, 'ads' => $ads,], 200);
    }

    # doctors
    public function doctors($id)
    {
        $sort  = Input::get("sort");
        if($sort == '')
        {
            $sections = Sub_Section::with('Doctor.DoctorServices.DoctorOrders')->where('id' , $id)->first();
            $majs = Doctor_Majors::where('sub_id' , $sections->id)->pluck('doctor_id')->toArray();
            $doctors = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$majs)->paginate(10);
            $sort= '';
        }elseif($sort == '1')
        {
            $sections = Sub_Section::with('Doctor.DoctorServices.DoctorOrders')->where('id' , $id)->first();
            $majs = Doctor_Majors::where('sub_id' , $sections->id)->pluck('doctor_id')->toArray();
            $doctors = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$majs)->orderBy('rate' , 'desc')->paginate(10);
            $sort= '1';
        }elseif($sort == '2')
        {
            $sections = Sub_Section::with('Doctor.DoctorServices.DoctorOrders')->where('id' , $id)->first();
            $majs = Doctor_Majors::where('sub_id' , $sections->id)->pluck('doctor_id')->toArray();
            $doctors = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$majs)->orderBy('meeting_price' , 'desc')->paginate(10);
            $sort= '2';
        }elseif($sort == '3')
        {
            $sections = Sub_Section::with('Doctor.DoctorServices.DoctorOrders')->where('id' , $id)->first();
            $majs = Doctor_Majors::where('sub_id' , $sections->id)->pluck('doctor_id')->toArray();
            $doctors = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$majs)->orderBy('meeting_price' , 'asc')->paginate(10);
            $sort= '3';
        }elseif($sort == '4')
        {
            $sections = Sub_Section::with('Doctor.DoctorServices.DoctorOrders')->where('id' , $id)->first();
            $majs = Doctor_Majors::where('sub_id' , $sections->id)->pluck('doctor_id')->toArray();
            $docs = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$majs)->get();
            $array =[];
            foreach($docs as $doctor){
                if(count($doctor->DoctorServices) > count($doctor->DoctorOrders)){
                    $array[] = $doctor->id;
                }
            }
            $doctors = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$array)->paginate(10);
            $sort= '4';
        }elseif($sort == '5')
        {
            $sections = Sub_Section::with('Doctor.DoctorServices.DoctorOrders')->where('id' , $id)->first();
            $majs = Doctor_Majors::where('sub_id' , $sections->id)->pluck('doctor_id')->toArray();
            $docs = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$majs)->get();
            $array =[];
            foreach($docs as $doctor){
                if(count($doctor->DoctorServices) == count($doctor->DoctorOrders)){
                    $array[] = $doctor->id;
                }
            }
            $doctors = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$array)->paginate(10);
            $sort= '5';
        }
        $sections->view_count = $sections->view_count + 1;
        $sections->save();

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sections->id)->where('type','consultants')->pluck('ads_id')->toArray();

            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

     
       
        return view('consultants::fronts.doctors',compact('sections','doctors','sort','adss','logos'));
    }

    # doctors
    public function res($id)
    {
        $sections = Sub_Section::where('id' , $id)->first();
        if(Auth::guard('customer')->user())
        {
            $orders = Doctor_Orders::with('Doctor','DoctorServices')->where('user_id' , Auth::guard('customer')->user()->id)->get();

            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sections->id)->where('type','consultants')->pluck('ads_id')->toArray();

            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

     
            return view('consultants::fronts.my_res',compact('sections','orders','adss','logos'));
        }


    
        
        return view('store::fronts.customer_login');
    }

    # doctors
    public function sectionss($id)
    {
        $major = Major::with('SubSections')->where('id' , $id)->first();
      
       
        return view('consultants::fronts.sections',compact('major'));
    }


    # doctor
    public function doctor($id)
    {
        $doctor = Doctor::with('DoctorServices','DoctorLinks')->where('id' , $id)->first();
        if(Auth::guard('customer')->user())
        {
            $rating = Doctor_Rating::with('Doctor')->where('user_id',Auth::guard('customer')->user()->id)->where('doctor_id',$doctor->id)->first();
            return view('consultants::fronts.doctor',compact('doctor','rating'));
        }
        return view('consultants::fronts.doctor',compact('doctor'));
    }

    # get DoctorServices  ajax
    public function Getcall(Request $request)
    {
        $datas = Doctor_Services::with('DoctorOrders')->where('doctor_id',$request->id)->where('services_type','call')->whereDate('date',$request->date)->get();
        return $datas;
    }

    # get DoctorServices  ajax
    public function Getonline(Request $request)
    {
        $datas = Doctor_Services::with('DoctorOrders')->where('doctor_id',$request->id)->where('services_type','online')->whereDate('date',$request->date)->get();
        return $datas;
    }

    # get DoctorServices  ajax
    public function Getmetting(Request $request)
    {
        $datas = Doctor_Services::with('DoctorOrders')->where('doctor_id',$request->id)->where('services_type','meeting')->whereDate('date',$request->date)->get();
        return $datas;
    }

    # call
    public function ordercall($id)
    {
        $doctor = Doctor::with('DoctorServices')->where('id' , $id)->first();

        return view('consultants::fronts.reservation1',compact('doctor'));
    }

    # online
    public function orderonline($id)
    {
        $doctor = Doctor::with('DoctorServices')->where('id' , $id)->first();

        return view('consultants::fronts.reservation2',compact('doctor'));
    }

    # meeting
    public function ordermeeting($id)
    {
        $doctor = Doctor::with('DoctorServices')->where('id' , $id)->first();

        return view('consultants::fronts.reservation3',compact('doctor'));
    }


    # store order
    public function storeorder(Request $request)
    {
        $request->validate([
            'id'    => 'required',

        ]);

        $servies = Doctor_Services::where('id',$request->id)->first();
        

        $order = new Doctor_Orders;
        $order->user_id       = Auth::guard('customer')->user()->id;
        $order->doctor_id     = $servies->doctor_id;
        $order->service_id    = $request->id;
        $order->save();

        $servies->status = 1;
        $servies->save();

        return back();
    }

    # add rating
    public function rating(Request $request)
    {

        $rating = new Doctor_Rating;
        $rating->rate       = $request->reat;
        $rating->doctor_id       = $request->doctor_id;
        $rating->user_id       = Auth::guard('customer')->user()->id;
        $rating->save();

        $company = Doctor::findOrFail($request->doctor_id);
        $company->rate =  Doctor_Rating::where('doctor_id' , $request->doctor_id)->avg('rate');
        $company->save();

        return $rating;
    }

    # add rating
    public function updaterating(Request $request)
    {

        $rating = Doctor_Rating::with('Doctor')->where('user_id',Auth::guard('customer')->user()->id)->where('doctor_id',$request->doctor_id)->first();
        $rating->rate       = $request->reat;
        $rating->save();

        $company = Doctor::findOrFail($request->doctor_id);
        $company->rate =  Doctor_Rating::where('doctor_id' , $request->doctor_id)->avg('rate');
        $company->save();

        return $rating;
    }

}
