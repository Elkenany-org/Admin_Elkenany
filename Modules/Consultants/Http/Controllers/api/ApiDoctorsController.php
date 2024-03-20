<?php

namespace Modules\Consultants\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Consultants\Entities\Major;
use Modules\Consultants\Entities\Doctor;
use Modules\Consultants\Entities\Doctor_Majors;
use Modules\Consultants\Entities\Sub_Section;
use Modules\Consultants\Entities\Doctor_Services;
use Modules\Consultants\Entities\Doctor_Orders;
use Modules\Consultants\Entities\Doctor_Rating;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\Data_Analysis;
use Modules\SystemAds\Entities\System_Ads;
use Modules\Store\Entities\Customer;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Main;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;

class ApiDoctorsController extends Controller
{

    # show all doctors
    public function index()
    {
        $list = [];
        $doctors = Doctor::paginate(10);

        if(count($doctors) < 1)
        {
            $list['data'] = [];
            $list['current_page']                     = $doctors->toArray()['current_page'];
            $list['last_page']                        = $doctors->toArray()['last_page'];
        }

        foreach ($doctors as $key => $doctor)
        {
            $list['data'][$key]['id']         = $doctor->id;
            $list['data'][$key]['name']       = $doctor->name;
            $list['data'][$key]['certificates']      = $doctor->certificates;
            $list['data'][$key]['adress']      = $doctor->adress;
            $list['data'][$key]['image']      = URL::to('uploads/doctors/avatar/'.$doctor->avatar);


            if(count($doctor->DoctorServices) == count($doctor->DoctorOrders)){
                $list['data'][$key]['type']      = 'لا يوجد مواعيد متاحة';
            }


            if(count($doctor->DoctorServices) > count($doctor->DoctorOrders)){
                $list['data'][$key]['type']      = ' يوجد مواعيد متاحة';
            }

            $list['current_page']             = $doctors->toArray()['current_page'];
            $list['last_page']                = $doctors->toArray()['last_page'];
            $list['first_page_url']           = $doctors->toArray()['first_page_url'];
            $list['next_page_url']            = $doctors->toArray()['next_page_url'];
            $list['last_page_url']            = $doctors->toArray()['last_page_url'];


        }

        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show all doctors
    public function showdoctors()
    {

        $sub_id     = Input::get("sub_id");
        $sort  = Input::get("sort");

        $section = Sub_Section::where('id',$sub_id)->first();
        
        # check section exist
        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $list = [];
        $maj = [];

      
        if($sort == '')
        {

            $majs = Doctor_Majors::where('sub_id' , $section->id)->pluck('doctor_id')->toArray();
            $doctors = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$majs)->paginate(10);
     
        }elseif($sort == '2')
        {
  
            $majs = Doctor_Majors::where('sub_id' , $section->id)->pluck('doctor_id')->toArray();
            $doctors = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$majs)->orderBy('rate' , 'desc')->paginate(10);
        
        }elseif($sort == '6')
        {
  
            $majs = Doctor_Majors::where('sub_id' , $section->id)->pluck('doctor_id')->toArray();
            $doctors = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$majs)->orderBy('meeting_price' , 'desc')->paginate(10);
         
        }elseif($sort == '7')
        {
   
            $majs = Doctor_Majors::where('sub_id' , $section->id)->pluck('doctor_id')->toArray();
            $doctors = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$majs)->orderBy('meeting_price' , 'asc')->paginate(10);
        
        }elseif($sort == '8')
        {
   
            $majs = Doctor_Majors::where('sub_id' , $section->id)->pluck('doctor_id')->toArray();
            $docs = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$majs)->get();
            $array =[];
            foreach($docs as $doctor){
                if(count($doctor->DoctorServices) > count($doctor->DoctorOrders)){
                    $array[] = $doctor->id;
                }
            }
            $doctors = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$array)->paginate(10);

        }elseif($sort == '9')
        {
 
            $majs = Doctor_Majors::where('sub_id' , $section->id)->pluck('doctor_id')->toArray();
            $docs = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$majs)->get();
            $array =[];
            foreach($docs as $doctor){
                if(count($doctor->DoctorServices) == count($doctor->DoctorOrders)){
                    $array[] = $doctor->id;
                }
            }
            $doctors = Doctor::with('DoctorServices','DoctorOrders')->whereIn('id',$array)->paginate(10);
  
        }
        $section->view_count = $section->view_count + 1;
        $section->save();

     
        if(count($doctors) < 1)
        {
            $list['data'] = [];
            $list['current_page']                     = $doctors->toArray()['current_page'];
            $list['last_page']                        = $doctors->toArray()['last_page'];
        }

        foreach ($doctors as $key => $doctor)
        {
            $list['data'][$key]['id']         = $doctor->id;
            $list['data'][$key]['name']       = $doctor->name;
            $list['data'][$key]['certificates']      = $doctor->certificates;
            $list['data'][$key]['adress']      = $doctor->adress;
            $list['data'][$key]['image']      = URL::to('uploads/doctors/avatar/'.$doctor->avatar);


            if(count($doctor->DoctorServices) == count($doctor->DoctorOrders)){
                $list['data'][$key]['type']      = 'لا يوجد مواعيد متاحة';
            }
          

            if(count($doctor->DoctorServices) > count($doctor->DoctorOrders)){
                $list['data'][$key]['type']      = ' يوجد مواعيد متاحة';
            }
              
            $list['current_page']             = $doctors->toArray()['current_page'];
            $list['last_page']                = $doctors->toArray()['last_page'];
            $list['first_page_url']           = $doctors->toArray()['first_page_url'];
            $list['next_page_url']            = $doctors->toArray()['next_page_url'];
            $list['last_page_url']            = $doctors->toArray()['last_page_url'];


        }

        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    # show filter sections
    public function filterSection()
    {

        $id = Input::get("id");

        $section = Sub_Section::where('id',$id)->first();
            

        # check section exist
        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $sections = Major::get();
        
        $sect = Major::with('SubSections')->where('id',$section->major_id)->first();
      

        $list = [];

        foreach ($sections as $key => $sec)
        {
            $list['sectors'][$key]['id']          = $sec->id;
            $list['sectors'][$key]['name']        = $sec->name;
            $list['sectors'][$key]['type']        = $sec->type;
            if($sec->id == $section->id){
                $list['sectors'][$key]['selected']        = 1;
            }else{
                $list['sectors'][$key]['selected']        = 0;
            }
    


        }

        foreach ($sect->SubSections as $key => $sen)
        {
            $list['sub_sections'][$key]['id']          = $sen->id;
            $list['sub_sections'][$key]['name']        = $sen->name;
            if($sen->id == $section->id){
                $list['sub_sections'][$key]['selected']        = 1;
            }else{
                $list['sub_sections'][$key]['selected']        = 0;
            }

        }

        $arr = [];
        $arrr = [];
        $arrrr = [];
        $arrrrr = [];
        $arrrrrr = [];
        $arrrrrrr = [];

        $arr['id']        = 1;
        $arr['name']       = ' الاعلي تقيما';
        $arr['value ']        = 2;

        $list['sort'][]       = $arr;

        
        $arrr['id']        = 2;
        $arrr['name']       = 'الاقل سعرا';
        $arrr['value']        = 6;

        $list['sort'][]       = $arrr;

        $arrrr['id']        = 3;
        $arrrr['name']       = 'الاعلي سعرا';
        $arrrr['value']        = 7;

        $list['sort'][]       = $arrrr;

        $arrrrr['id']        = 2;
        $arrrrr['name']       = 'متاح';
        $arrrrr['value']        = 8;

        $list['sort'][]       = $arrrrr;

        $arrrrrr['id']        = 2;
        $arrrrrr['name']       = 'غير متاح';
        $arrrrrr['value']        = 9;

        $list['sort'][]       = $arrrrrr;

    
        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    # filter companies
    public function FilterSubs(Request $request)
    {

        $id = Input::get("id");

        $section = Major::where('id',$id)->first();
            
        # check section exist
        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $subs = Sub_Section::with('Doctor')->where('major_id',$id)->orderby('name')->get();

        $list = [];

        foreach ($subs as $key => $sub)
        {
            $list[$key]['id']          = $sub->id;
            $list[$key]['name']        = $sub->name;


        }
    

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    # doctors
    public function res()
    {

        $orders = Doctor_Orders::with('Doctor','DoctorServices')->where('user_id' , session('customer')->id)->get();

        $list = [];

        if(count($orders) < 1)
        {
            $list['data'] = [];

        }

        foreach ($orders as $key => $value)
        {
            $list['data'][$key]['id']          = $value->id;
            $list['data'][$key]['name']        = $value->Doctor->name;
            $list['data'][$key]['date']        = $value->DoctorServices->date;
            $list['data'][$key]['from']        = Date::parse($value->DoctorServices->time_from)->format('h:i A');
            $list['data'][$key]['to']          = Date::parse($value->DoctorServices->time_to)->format('h:i A');
            $list['data'][$key]['address']     = $value->Doctor->adress;


        }


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }
 
 

    # show doctor
    public function showdoctor()
    {
        $id = Input::get("id");

        $doctor = Doctor::with('Majors')->where('id',$id)->first();

        # check doctor exist
        if(!$doctor)
        {
            $msg = 'doctor not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $list = [];
        $maj = [];

        $list['id']                 = $doctor->id;
        $list['name']               = $doctor->name;
        $list['email']              = $doctor->email;
        $list['phone']              = $doctor->phone;
        $list['address']            = $doctor->adress;
        $list['certificates']       = $doctor->certificates;
        $list['experiences']        = $doctor->experiences;
        $list['call_price']         = $doctor->call_price;
        $list['online_price']       = $doctor->online_price;
        $list['meeting_price']      = $doctor->meeting_price;
        $list['rate']               = $doctor->rate;
        $list['image']              = URL::to('uploads/doctors/avatar/'.$doctor->avatar);
     

        $majs = Doctor_Majors::where('doctor_id' , $doctor->id)->pluck('sub_id')->toArray();
        $subs = Sub_Section::with('Doctor')->whereIn('id',$majs)->get();

        // majors
        foreach ($subs as $key => $major)
        {
            $maj[$key]['id']         = $major->id;
            $maj[$key]['name']       = $major->name;
        }

        $list['majors']         = $maj;

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
  
    }

    # add rating
    public function updaterating(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'doctor_id'      => 'required',
            'reat'            => 'required',
        ]);
        
        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['doctor_id']))
            {
                $msg = 'doctor_id is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['reat']))
            {
                $msg = 'reat is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        $company = Doctor::findOrFail($request->doctor_id);

        # check company exist
        if(!$company)
        {
            $msg = 'Doctor not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }


        $rating = Doctor_Rating::with('Doctor')->where('user_id',session('customer')->id)->where('doctor_id',$request->doctor_id)->first();
        if(!$rating)
        {
            $rating = new Doctor_Rating;
            $rating->rate       = $request->reat;
            $rating->doctor_id       = $request->doctor_id;
            $rating->user_id       = session('customer')->id;
            $rating->save();
    

            $company->rate =  Doctor_Rating::where('doctor_id' , $request->doctor_id)->avg('rate');
            $company->save();
    
            return response()->json([
                'message'  => null,
                'error'    => null,
                'data'     => $rating
            ],200);
        }

        $rating->rate       = $request->reat;
        $rating->save();

        $company->rate =  Doctor_Rating::where('doctor_id' , $request->doctor_id)->avg('rate');
        $company->save();

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $rating
        ],200);
    }


    # show doctor services
    public function showservices()
    {

        $id = Input::get("id");

        $type = Input::get("type");

        $date = Input::get("date");

         # check doctor exist
         if(!$type)
         {
             $msg = 'type not found';
             return response()->json([
                 'status'   => '0',
                 'message'  => null,
                 'error'    => $msg,
             ],400);	
         }

        $doctor = Doctor::with('DoctorServices')->where('id',$id)->first();

        # check doctor exist
        if(!$doctor)
        {
            $msg = 'doctor not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $list = [];

        if($type == 'call'){
            // services

            if($date === null){
                $datas = Doctor_Services::with('DoctorOrders')->where('doctor_id',$id)->where('services_type','call')->get();
            }else{
                $datas = Doctor_Services::with('DoctorOrders')->where('doctor_id',$id)->where('services_type','call')->whereDate('date',$date)->get();
            }
           
            
            if(count($datas) < 1)
            {
                $list['times'] = [];
    
            }

            foreach ($datas as $key => $servic)
            {
                $list['times'][$key]['id']           = $servic->id;
                $list['times'][$key]['time_from']    = $servic->time_from;
                $list['times'][$key]['time_to']      = $servic->time_to;
                $list['times'][$key]['date']         = $servic->date;
                $list['times'][$key]['services_type']= $servic->services_type;
                $list['times'][$key]['doctor']       = $doctor->name;
            }
        }elseif($type == 'online'){
            // services

            if($date === null){
                $datas = Doctor_Services::with('DoctorOrders')->where('doctor_id',$id)->where('services_type','online')->get();
            }else{
                $datas = Doctor_Services::with('DoctorOrders')->where('doctor_id',$id)->where('services_type','online')->whereDate('date',$date)->get();
            }
        
           
            if(count($datas) < 1)
            {
                $list['times'] = [];
    
            }
           
            foreach ($datas as $key => $servic)
            {
                $list['times'][$key]['id']           = $servic->id;
                $list['times'][$key]['time_from']    = $servic->time_from;
                $list['times'][$key]['time_to']      = $servic->time_to;
                $list['times'][$key]['date']         = $servic->date;
                $list['times'][$key]['services_type']= $servic->services_type;
                $list['times'][$key]['doctor']       = $doctor->name;
            }
        }elseif($type == 'meeting'){
            // services

            if($date === null){
                $datas = Doctor_Services::with('DoctorOrders')->where('doctor_id',$id)->where('services_type','meeting')->get();
            }else{
                $datas = Doctor_Services::with('DoctorOrders')->where('doctor_id',$id)->where('services_type','meeting')->whereDate('date',$date)->get();
            }

            if(count($datas) < 1)
            {
                $list['times'] = [];
    
            }
            
            foreach ($datas as $key => $servic)
            {
                $list['times'][$key]['id']           = $servic->id;
                $list['times'][$key]['time_from']    = $servic->time_from;
                $list['times'][$key]['time_to']      = $servic->time_to;
                $list['times'][$key]['date']         = $servic->date;
                $list['times'][$key]['services_type']= $servic->services_type;
                $list['times'][$key]['doctor']       = $doctor->name;
            }
        }

       

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # store order
    public function storeorder(Request $request)
    {
        $id = Input::get("id");

        $servies = Doctor_Services::where('id',$id)->first();

        # check doctor exist
        if(!$servies)
        {
            $msg = 'servies not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }
        

        $order = new Doctor_Orders;
        $order->user_id       = session('customer')->id;
        $order->doctor_id     = $servies->doctor_id;
        $order->service_id    = $id;
        $order->save();

        $servies->status = 1;
        $servies->save();

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $order
        ],200);
    }

 
  
}
