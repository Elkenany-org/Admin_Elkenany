<?php

namespace Modules\Recruitment\Http\Controllers\Api;

use App\Main;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Input;
use App\Traits\SearchReg;

use Illuminate\Support\Str;
use Modules\Guide\Entities\Company;
use Modules\Recruitment\Entities\Job_Application;
use Modules\Recruitment\Entities\Job_Categories;
use Modules\Recruitment\Entities\Job_Favorite;
use Modules\Recruitment\Entities\Job_Offer;
use Modules\Recruitment\Entities\Recruiter;
use Modules\Store\Entities\Customer;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;

class ApiRecruitmentController extends Controller
{
    use ApiResponse,SearchReg;

    # Register
    public function Register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname'         => 'required',
            'lname'         => 'required',
            'email'        => 'required|unique:recruiters',
            'phone'        => 'required|unique:recruiters',
            'password'     => 'required',
            'company_id'    =>'required'
        ]);

        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['fname']))
            {
                $msg = 'first name is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
            elseif(isset($value['lname']))
            {
                $msg = 'last name is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['email']) && is_null($request->email))
                {
                    $msg = 'email is required';
                    return response()->json([
                        'message'  => null,
                        'error'    => $msg,
                    ],400);
            }elseif(isset($value['email']) && !is_null($request->email))
            {
                $msg = 'email is already exist';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],406);//not acceptable code status
            }elseif(isset($value['phone']) && is_null($request->phone))
            {
                $msg = 'phone is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['phone']) && !is_null($request->phone))
            {
                $msg = 'phone is already exist';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],406);//not acceptable code status
            }elseif(isset($value['password']))
            {
                $msg = 'password is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['company_id']))
            {
                $msg = 'related company is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        $company_related=  Company::where('id',$request->company_id)->first();
        if(! $company_related){
            $msg = 'company not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],404);
        }else{
            $recruiter = new Recruiter();
            $recruiter->firstname        = $request->fname;
            $recruiter->lastname        = $request->lname;
            $recruiter->email       = $request->email;
            $recruiter->phone       = $request->phone;
            $recruiter->company_id       = $request->company_id;
            $recruiter->password    = bcrypt($request->password);
            $recruiter->api_token   = Str::random(60);
            $recruiter->save();

            $list['name']              = $recruiter->name;
            $list['email']             = $recruiter->email;
            $list['phone']             = $recruiter->phone;
            $list['api_token']         = $recruiter->api_token;


            return response()->json([
                'message'  => null,
                'error'    => null,
                'data'     => $list
            ],200);

        }


    }

    # login
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email'        => 'required',
            'password'     => 'required',
        ]);

        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['email']))
            {
                $msg = 'email or phone is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);

            }elseif(isset($value['password']))
            {
                $msg = 'password is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        if(is_numeric($request->get('email'))){

            $recruiter = Recruiter::where('phone',$request->email)->first();



            if(!$recruiter){
                $msg = 'رقم العميل غير موجود';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],404);//phone not found
            }else{


                if (Auth::guard('recruiter')->attempt(['phone' => $request->email, 'password' => $request->password])) {

                    $recruiter->api_token   = Str::random(60);
                    $recruiter->save();

                    $list['name']              = $recruiter->name;
                    $list['email']             = $recruiter->email;
                    $list['phone']             = $recruiter->phone;
                    $list['api_token']         = $recruiter->api_token;

                    return response()->json([
                        'message'  => 'تم التسجيل',
                        'error'    => null,
                        'data'     => $list
                    ],200);

                }else{

                    $msg = 'كلمة السر خطأ';
                    return response()->json([
                        'message'  => null,
                        'error'    => $msg,
                    ],406); // not acceptable
                }
            }



        }else {
            $recruiter = Recruiter::where('email',$request->email)->first();

            if(!$recruiter){
                $msg = 'بريد العميل غير موجود';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],404);//email not found
            }else{
                if (Auth::guard('recruiter')->attempt(['email' => $request->email, 'password' => $request->password])) {

                    $recruiter->api_token   = Str::random(60);
                    $recruiter->save();

                    $list['name']              = $recruiter->name;
                    $list['email']             = $recruiter->email;
                    $list['phone']             = $recruiter->phone;
                    $list['api_token']         = $recruiter->api_token;

                    return response()->json([
                        'message'  => "تم التسجيل",
                        'error'    => null,
                        'data'     => $list
                    ],200);

                }else{

                    $msg = 'الرقم السري خطأ';
                    return response()->json([
                        'message'  => null,
                        'error'    => $msg,
                    ],406); // not acceptable
                }
            }
        }

    }

    //get all jobs
    public function ShowJobs(Request $request)
    {

        $cate = $request->input("cate");
        $sort       = $request->input("sort");
        $search     = $request->input("search");

        $list=[];

        if($sort){
            if($sort == 1){
                if(!is_null($search)){
                    if($request->hasHeader('android')) {
                        $jobs = Job_Offer::where('title' , 'like' , "%". $search ."%")->where('approved','1')->orderBy('view_count' , 'desc')->get();
                    }else{
                        $jobs = Job_Offer::where('title' , 'like' , "%". $search ."%")->where('approved','1')->orderBy('view_count' , 'desc')->paginate(10);
                    }
                }
                elseif(!is_null($cate)){
                    if($request->hasHeader('android')) {
                        $jobs = Job_Offer::where('category_id',$cate)->where('approved','1')->orderBy('view_count' , 'desc')->get();
                    }else{
                        $jobs = Job_Offer::where('category_id',$cate)->where('approved','1')->orderBy('view_count' , 'desc')->paginate(10);
                    }
                }else{
                    if($request->hasHeader('android')) {
                        $jobs = Job_Offer::where('approved','1')->orderBy('view_count' , 'desc')->get();
                    }else{
                        $jobs = Job_Offer::where('approved','1')->orderBy('view_count' , 'desc')->paginate(10);
                    }
                }

            }
//            elseif($sort == 0){
//                if(!is_null($search)){
//                    if($request->hasHeader('android')) {
//                        $jobs = $jobs->where('title' , 'like' , "%". $search ."%")->where('approved','1')->latest()->get();
//
//                    }else{
//                        $jobs = $jobs->where('title' , 'like' , "%". $search ."%")->where('approved','1')->latest()->paginate(10);
//                    }
//                }else{
//                    if($request->hasHeader('android')) {
//                        $jobs = $jobs->where('approved','1')->orderBy('title','desc')->get();
//
//                    }else{
//                        $jobs = $jobs->where('approved','1')->orderBy('title','desc')->paginate(10);
//
//                    }
//                }
//
//            }
        }
        else{
            if(!is_null($search)){
                if($request->hasHeader('android')) {
                    $jobs = Job_Offer::where('title' , 'like' , "%". $search ."%")->where('approved','1')->latest()->get();

                }else{
                    $jobs = Job_Offer::where('title' , 'like' , "%". $search ."%")->where('approved','1')->latest()->paginate(10);

                }
            }
            elseif(!is_null($cate)){
                if($request->hasHeader('android')) {
                    $jobs = Job_Offer::where('category_id',$cate)->where('approved','1')->latest()->get();
                }else{
                    $jobs = Job_Offer::where('category_id',$cate)->where('approved','1')->latest()->paginate(10);
                }
            }

            else{
                if($request->hasHeader('android')) {
                    $jobs = Job_Offer::where('approved','1')->latest()->get();
                }else{
                    $jobs = Job_Offer::where('approved','1')->latest()->paginate(10);

                }
        }

        }

        for ($i=0;$i<3;$i++){
            $list['banners'][$i]['id'] = $i+1;
            $list['banners'][$i]['link'] = null;
            $list['banners'][$i]['type'] = null;
            $list['banners'][$i]['company_id'] = null;
            $list['banners'][$i]['company_name'] = null;

        }
        $list['banners'][0]['image'] = URL::to('uploads/recruitment/Group2953.png');
        $list['banners'][1]['image'] = URL::to('uploads/recruitment/Group2954.png');
        $list['banners'][2]['image'] = URL::to('uploads/recruitment/Group2955.png');
        $job_favorites=[];
        if($request->hasHeader('android')){
            for ($i=0;$i<4;$i++){
                $list['categories'][$i]['id'] = $i+1;
                $list['categories'][$i]['selected'] = 0;
            }
            $list['categories'][0]['name'] = "الوظائف المفضلة";
            $token = $request->header('Authorization');
            if($token){
                $token = explode(' ',$token);
                if(count( $token) == 2) {
                    $customer = Customer::where('api_token',$token[1])->first();

                    if($customer && $customer->company_id != null ){
                        $list['categories'][1]['name'] = "المتقدمون";
                        $jobs_ids=$jobs->pluck('id');
                        $job_favorites=Job_Favorite::whereIn('job_id',$jobs_ids)->where('customer_id',$customer->id)->pluck('job_id');
//                        dd($job_favorites);
                    }else{
                        $list['categories'][1]['name'] = "";
                    }

                    if($customer ){
                        $jobs_ids=$jobs->pluck('id');
                        $job_favorites=Job_Favorite::whereIn('job_id',$jobs_ids)->where('customer_id',$customer->id)->pluck('job_id');
                    }
                }
            }else{
                $list['categories'][1]['name'] = "";
            }
            $list['categories'][2]['name'] = "وظائفي";
            $list['categories'][3]['name'] = "الوظائف";
            $list['categories'][3]['selected'] = 1;
        }

        if(count($jobs) < 1)
        {
            $list['jobs'] = [];
            if(! $request->hasHeader('android')){
                $list['current_page']                     = $jobs->toArray()['current_page'];
                $list['last_page']                        = $jobs->toArray()['last_page'];
            }

        }

        foreach ($jobs as $key => $job)
        {
                $list['jobs'][$key]['id']          = $job->id;
                $list['jobs'][$key]['title']       = $job->title;
                $list['jobs'][$key]['salary']      = $job->salary;
                $list['jobs'][$key]['address']     = $job->address;
                $list['jobs'][$key]['desc']        = Str::limit($job->desc, 60, '...');
                $list['jobs'][$key]['phone']        = $job->phone;
                $list['jobs'][$key]['email']        = $job->email;
                $list['jobs'][$key]['experience']        = $job->experience;
                $list['jobs'][$key]['work_hours']        = $job->work_hours;


            $list['jobs'][$key]['category']        = $job->Category->name;
            $recruiter = Customer::where('id' , $job->recruiter_id)->first();
            $list['jobs'][$key]['recruiter_name']        = $recruiter->name;
            $list['jobs'][$key]['company_id']        = $recruiter->Company->id;
            $list['jobs'][$key]['company_name']        = $recruiter->Company->name;
            $list['jobs'][$key]['image']        = URL::to('uploads/company/images/'.$recruiter->Company->image);
            $list['jobs'][$key]['created_at']  = Date::parse($job->created_at)->format('Y-m-d');
            $list['jobs'][$key]['type']  = 'job';
            $list['jobs'][$key]['favorite']  = 0;

                foreach ($job_favorites as $favorite){
                    if($favorite == $job->id){
                        $list['jobs'][$key]['favorite']  = 1;
                        break;
                    }
            }

            if(! $request->hasHeader('android')) {
                $list['current_page'] = $jobs->toArray()['current_page'];
                $list['last_page'] = $jobs->toArray()['last_page'];
                $list['first_page_url'] = $jobs->toArray()['first_page_url'];
                $list['next_page_url'] = $jobs->toArray()['next_page_url'];
                $list['last_page_url'] = $jobs->toArray()['last_page_url'];
            }

        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    //get my jobs
    public function MyJobs(Request $request)
    {

        $recruiter = Customer::where('id' , session('customer')->id)->first();

        if($recruiter->company_id != null){
            if($request->hasHeader('android')){
                $jobs = Job_Offer::where('recruiter_id' , session('customer')->id)->latest()->get();
            }
            else{
                $jobs = Job_Offer::where('recruiter_id' , session('customer')->id)->latest()->paginate(10);
            }
        }
        else {

            $applied_jobs = Job_Application::where('applicant_id', session('customer')->id)->pluck('job_id');

            if ($request->hasHeader('android')) {
                $jobs = Job_Offer::whereIn('id', $applied_jobs)->latest()->get();
            } else {
                $jobs = Job_Offer::whereIn('id', $applied_jobs)->latest()->paginate(10);
            }
        }
            $list = [];

            if(count($jobs) < 1)
            {
                $list['jobs'] = [];
                if(! $request->hasHeader('android')){
                    $list['current_page']                     = $jobs->toArray()['current_page'];
                    $list['last_page']                        = $jobs->toArray()['last_page'];
                }

            }

            foreach ($jobs as $key => $job)
            {
                $list['jobs'][$key]['id']          = $job->id;
                $list['jobs'][$key]['title']       = $job->title;
                $list['jobs'][$key]['salary']      = $job->salary;
                $list['jobs'][$key]['address']     = $job->address;
                $list['jobs'][$key]['desc']        = $job->desc;
                $list['jobs'][$key]['category']        = $job->Category->name;
                $list['jobs'][$key]['image']        = URL::to('uploads/company/images/'.$job->Company->image);
                $list['jobs'][$key]['created_at']  = Date::parse($job->created_at)->format('Y-m-d');
                $list['jobs'][$key]['status']        = $job->approved;

                $application_status=Job_Application::where('job_id',$job->id)->where('applicant_id',session('customer')->id)->latest()->first();
                if($application_status){
                    if($application_status->qualified == '0'){
                        $list['jobs'][$key]['application_status']        = "غير مؤهل";

                    }elseif($application_status->qualified == '1'){
                        $list['jobs'][$key]['application_status']        = "مؤهل";

                    }else{
                        $list['jobs'][$key]['application_status']        = "غير محدد";
                    }
                }else{
                    $list['jobs'][$key]['application_status']        = Null;
                }
                if(! $request->hasHeader('android')) {
                    $list['current_page'] = $jobs->toArray()['current_page'];
                    $list['last_page'] = $jobs->toArray()['last_page'];
                    $list['first_page_url'] = $jobs->toArray()['first_page_url'];
                    $list['next_page_url'] = $jobs->toArray()['next_page_url'];
                    $list['last_page_url'] = $jobs->toArray()['last_page_url'];
                }

            }



        return $this->ReturnData($list);
    }

    public function ShowOneJob(Request $request)
    {
        $id = $request->input("id");
        $job = Job_Offer::where('id',$id)->first();

        # check ads exist
        if(!$job)
        {
            return $this->ErrorMsgWithStatus('job not found');
        }

        //view count
        $job->view_count = $job->view_count + 1;
        $job->save();
        $list = [];


        $list['id']                = $job->id;
        $list['title']             = $job->title;
        $list['salary']            = $job->salary;
        $list['phone']             = $job->phone;
        $list['view_count']        = $job->view_count;
        $list['address']           = $job->address;
        $list['desc']        = $job->desc;
        $list['email']        = $job->email;
        $list['experience']        = $job->experience;

        $company=Company::where('id',$job->company_id)->first();
        $list['company_id']        = $company->id;
        $list['company_name']        = $company->name;
        $list['images']     = URL::to('uploads/company/images/'.$company->image);
        $list['category']       = $job->Category->name;
        $list['category_id']       = $job->Category->id;
        $list['work_hours']       = $job->work_hours;
        $list['applicants_count'] = Job_Application::where('job_id',$job->id)->count();
        $list['notqualified_count'] = Job_Application::where('job_id',$job->id)->where('qualified','0')->count();
        $list['qualified_count'] = Job_Application::where('job_id',$job->id)->where('qualified','1')->count();
        $list['skills'] = json_decode($job->skills);
        if($job->Recruiter != null){
            $list['user']       = $job->Recruiter->name;
            $list['user_created_at']        = $job->Recruiter->created_at->diffForHumans();
            $list['type']       = 'recruiter';

        }
        if($job->Admin != null){
            $list['user']       = $job->Admin->name;
            $list['user_created_at']        = $job->Admin->created_at->diffForHumans();
            $list['type']       = 'admin';
        }
        $list['created_at']        = $job->created_at->diffForHumans();


        $token = $request->header('Authorization');
        $token = explode(' ',$token);
        if(count( $token) == 2)
        {
            $customer = Customer::where('api_token',$token[1])->first();
            if($customer != null && $customer->company_id != null){
            $list['user_type']        = 'recruiter';
            }
        }


        return $this->ReturnData($list);
    }

    public function filterCategories(Request $request)
    {

        $categories = Job_Categories::get();
        $list = [];

        $type = $request->input("sector_id");

        foreach ($categories as $key => $cate)
        {
            $list['categories'][$key]['id']          = $cate->id;
            $list['categories'][$key]['name']        = $cate->name;
        }

        $list['sort'] = [
            [
                'id'=>0,
                'name'=>'الاحدث',
                'value'=>0,
            ],[
                'id'=>1,
                'name'=>'الاكثر تداولا',
                'value'=>1,
            ],
        ] ;



        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    public function AddJob(Request $request){
        $list = [];
        $validator = Validator::make($request->all(), [
            'title'        => 'required',
            'desc'        => 'required',
            'salary'        => 'required',
            'address'        => 'required',
            'experience'        => 'required',
            'category_id'        => 'required',
            'company_id'        => 'required',
            'work_hours'        =>'required',
            'skills'            =>'required',
        ]);

        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['title']))
            {
                $msg = 'يرجي ادخال العنوان';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);

            }elseif(isset($value['desc'])){
                $msg = 'يرجي ادخال التفاصيل';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['salary'])){
                $msg = 'يرجي تحديد الراتب';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['address'])){
                $msg = 'يرجي ادخال العنوان';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['work_hours'])){
                $msg = 'يرجي تحديد نوع الدوام';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['experience'])){
                $msg = 'يرجي تحديد الخبرة المطلوبة';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
            if(isset($value['category_id'])){
                $msg = 'يرجي تحديد القسم';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }if(isset($value['skills'])){
                $msg = 'يرجي ادخال المهارات';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }else{
                $category = Job_Categories::where('id' , $request->category_id)->first();
                if(!$category){
                    $msg = 'category not found';
                    return response()->json([
                        'message'  => null,
                        'error'    => $msg,
                    ],404);
                }
            }

            $recruiter = Customer::where('id' , session('customer')->id)->first();
                if($recruiter->company_id == null){
                    if(isset($value['company_id']))
                    {
                            $msg = 'يرجي اختيار الشركة';
                            return response()->json([
                                'message'  => null,
                                'error'    => $msg,
                            ],400);
                    }else{
                        $company = Company::where('id' , $request->company_id)->first();
                        if(! $company){
                            $msg = 'company not found';
                            return response()->json([
                                'message'  => null,
                                'error'    => $msg,
                            ],404);
                        }else{
                            $recruiter->company_id = $request->company_id;
                            $recruiter->save();

                        }
                    }
                }


        }

        $job = new Job_Offer();
        $job->title =   $request->title;
        $job->desc =   $request->desc;
        $job->salary =   $request->salary;
        $job->address =   $request->address;
        $job->experience =   $request->experience;
        $job->category_id =   $request->category_id;
        $job->recruiter_id = session('customer')->id;
        $job->company_id = $request->company_id;
        $job->work_hours = $request->work_hours;

        $job->skills =  $request->skills;
        if($recruiter->verified_company == '1'){
            $job->approved = '1';
        }else{
            $job->approved = '0';
        }
        $job->save();

        $list['job_detials']['id']          = $job->id;
        $list['job_detials']['title']       = $job->title;


        if($recruiter->verified_company == 0){
            return $this->ReturnData($list,'جاري مراجعة الاعلان الان');
        }elseif($recruiter->verified_company == 1){
            return $this->ReturnData($list,'تم اضافة اعلان الوظيفة بنجاح');
        }else{
            return $this->ReturnData($list,'جاري مراجعة الاعلان الان');
        }
    }
    public function updatestorejobs(Request $request){
        $list = [];
        $validator = Validator::make($request->all(), [
            'id'          =>'required',
            'title'        => 'required',
            'desc'        => 'required',
            'salary'        => 'required',
            'phone'        => 'required',
            'email'        => 'required',
            'address'        => 'required',
            'experience'        => 'required',
            'category_id'        => 'required',
            'work_hours'        =>'required'
        ]);

        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['id']))
            {
                $msg = 'يرجي ادخال ال id';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);

            }elseif(isset($value['title']))
            {
                $msg = 'يرجي ادخال العنوان';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);

            }elseif(isset($value['desc'])){
                $msg = 'يرجي ادخال التفاصيل';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['salary'])){
                $msg = 'يرجي تحديد الراتب';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['phone'])){
                $msg = 'يرجي ادخال رقم الهاتف';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['email'])){
                $msg = 'يرجي ادخال البريد الالكتروني';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['address'])){
                $msg = 'يرجي ادخال العنوان';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif(isset($value['experience'])){
                $msg = 'يرجي تحديد الخبرة';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
            elseif(isset($value['work_hours'])){
                $msg = 'يرجي تحديد نوع الدوام';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
            if(isset($value['category_id'])){
                $msg = 'يرجي تحديد القسم';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }else{
                $category = Job_Categories::where('id' , $request->category_id)->first();
                if(!$category){
                    $msg = 'category not found';
                    return response()->json([
                        'message'  => null,
                        'error'    => $msg,
                    ],404);
                }
            }

        }
        $recruiter = Customer::where('id' , session('customer')->id)->first();

        $job = Job_Offer::where('id',$request->id)->first();
        $job->title =   $request->title;
        $job->desc =   $request->desc;
        $job->salary =   $request->salary;
        $job->phone =   $request->phone;
        $job->email =   $request->email;
        $job->address =   $request->address;
        $job->experience =   $request->experience;
        $job->category_id =   $request->category_id;
        $job->work_hours =  $request->work_hours;
        $job->save();

        $list['job_detials']['id']          = $job->id;
        $list['job_detials']['title']       = $job->title;
        $list['job_detials']['desc']        = $job->desc;
        $list['job_detials']['salary']       = $job->salary;

        $list['job_detials']['phone']       = $job->phone;
        $list['job_detials']['email']       = $job->email;

        $list['job_detials']['address']     = $job->address;
        $list['job_detials']['experience']    = $job->experience;
        $list['job_detials']['category_id']      = $job->category_id;
        $list['job_detials']['approved']      = $job->approved;
        $list['job_detials']['work_hours']      = $job->work_hours;
        $list['company_detials']['id']      = $recruiter->Company->id;
        $list['company_detials']['name']      = $recruiter->Company->name;

        if($recruiter->verified_company == 0){
            return $this->ReturnData($list,'جاري مراجعة الاعلان الان');
        }elseif($recruiter->verified_company == 1){
            return $this->ReturnData($list,'تم تعديل اعلان الوظيفة بنجاح');
        }else{
            return $this->ReturnData($list,'جاري مراجعة الاعلان الان');
        }
    }

    public function ApplyJob(Request $request){
        $list = [];
        $validator = Validator::make($request->all(), [
            'full_name'        => 'required',
            'phone'        => 'required',
            'notice_period'        => 'required',
            'education'        => 'required',
            'experience'        => 'required',
            'job_id'        => 'required',
            'expected_salary' => 'required',
            'cv_link'        => 'required|mimes:doc,docx,pdf,txt,csv|max:2048',
        ]);

        foreach ((array) $validator->errors() as $value)
        {

            if(isset($value['full_name']))
            {
                $msg = 'يرجي ادخال الاسم كامل';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);

            }
            elseif(isset($value['phone']))
            {
                $msg = 'يرجي ادخال رقم الهاتف';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);

            }
            elseif(isset($value['notice_period']))
            {
                $msg = 'يرجي ادخال فترة التبليغ';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);

            }
            elseif(isset($value['education']))
            {
                $msg = 'يرجي ادخال المؤهل';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);

            }
            elseif(isset($value['experience']))
                {
                    $msg = 'يرجي ادخال الخبرة';
                    return response()->json([
                        'message'  => null,
                        'error'    => $msg,
                    ],400);

                }
            elseif(isset($value['expected_salary']))
            {
                $msg = 'يرجي ادخال الراتب المتوقع';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
            elseif(isset($value['cv_link']))
            {
                $msg = 'يرجي رفع ال cv';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }

            if(isset($value['job_id'])){
                $msg = 'job_id is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }else{
                $job = Job_Offer::where('id' , $request->job_id)->first();
                if(!$job){
                    $msg = 'job not found';
                    return response()->json([
                        'message'  => null,
                        'error'    => $msg,
                    ],404);
                }
            }

        }

        $application = new Job_Application();
        $application->full_name =   $request->full_name;
        $application->phone =   $request->phone;
        $application->notice_period =   $request->notice_period;
        $application->education =   $request->education;
        $application->experience =   $request->experience;
        $application->expected_salary =   $request->expected_salary;
        $application->job_id =   $request->job_id;
        $application->applicant_id = session('customer')->id;


        if(! isset($value['other_info'])){
            $application->other_info =   $request->other_info;
        }
        $fileName = date('d-m-y').time().rand().'.'.$request->cv_link->extension();
        $request->cv_link->move('uploads/resumes', $fileName);
        $application->cv_link =   $fileName;

        $application->save();




        $list['application']['id']     = $application->id;
        $list['application']['experience']    = $application->experience;
        $list['application']['education']      = $application->education;
        $list['application']['expected_salary']      = $application->expected_salary;
        $list['application']['cv_link']      = URL::to('uploads/resumes/'.$fileName);

        return $this->ReturnData($list,'تم التقديم بنجاح');

    }

    public function Applicants(Request $request)
    {

        $job_id     =$request->input("job_id");
        $select     =$request->input("select");
        $search     =$request->input("search");

        $recruiter = Customer::where('id' , session('customer')->id)->first();
        $job =   Job_Offer::where('id',$job_id)->where('recruiter_id',$recruiter->id)->first();
        if($job){
            if(!is_null($select)){
                if($select == 1){
                    if(!is_null($search)){
                        if($request->hasHeader('android')) {
                            $application=Job_Application::where('job_id',$job->id)->where('full_name' , 'like' , "%". $search ."%")->where('qualified','1')->get();
                        }else{
                            $application=Job_Application::where('job_id',$job->id)->where('full_name' , 'like' , "%". $search ."%")->where('qualified','1')->paginate(10);
                        }
                    }else{
                        if($request->hasHeader('android')) {
                            $application=Job_Application::where('job_id',$job->id)->where('qualified','1')->get();
                        }else{
                            $application=Job_Application::where('job_id',$job->id)->where('qualified','1')->paginate(10);
                        }
                    }

                }elseif($select == 2){
                    if(!is_null($search)){
                        if($request->hasHeader('android')) {
                            $application=Job_Application::where('job_id',$job->id)->where('full_name' , 'like' , "%". $search ."%")->where('qualified','0')->get();
                        }else{
                            $application=Job_Application::where('job_id',$job->id)->where('full_name' , 'like' , "%". $search ."%")->where('qualified','0')->paginate(10);
                        }
                    }else{
                        if($request->hasHeader('android')) {
                            $application=Job_Application::where('job_id',$job->id)->where('qualified','0')->get();
                        }else{
                            $application=Job_Application::where('job_id',$job->id)->where('qualified','0')->paginate(10);
                        }
                    }

                }
            }
            else{
                if(!is_null($search)){
                    if($request->hasHeader('android')) {
                        $application=Job_Application::where('job_id',$job->id)->where('full_name' , 'like' , "%". $search ."%")->get();

                    }else{
                        $application=Job_Application::where('job_id',$job->id)->where('full_name' , 'like' , "%". $search ."%")->paginate(10);

                    }
                }
                else{
                    if($request->hasHeader('android')) {
                        $application=Job_Application::where('job_id',$job->id)->get();
                    }else{
                        $application=Job_Application::where('job_id',$job->id)->paginate(10);

                    }
                }

            }
        }



        $list = [];
        $list['job_title']=$job->title;
        $list['applicants']=[];
        foreach ($application as $key => $app)
        {

            $list['applicants'][$key]['id']          = $app->id;
            $list['applicants'][$key]['name']       = $app->full_name;
            $list['applicants'][$key]['email']      = $app->Applicant->email;
            $list['applicants'][$key]['created_at']     = Date::parse($app->created_at)->format('Y-m-d');
            $list['applicants'][$key]['cv']     = URL::to('uploads/resumes/'.$app->cv_link);
            $list['applicants'][$key]['image']    = URL::to('uploads/customers/avatar/'.$app->Applicant->avatar);

        }


        return $this->ReturnData($list);
    }

    public function applicationDetails(Request $request){
            $id     =$request->input("app_id");
            $list['application']=[];
            $app=Job_Application::where('id',$id)->first();
            if($app){
                $list['application']['id']          = $app->id;
                $list['application']['name']       = $app->full_name;
                $list['application']['email']      = $app->Applicant->email;
                $list['application']['phone']      = $app->phone;
                $list['application']['notice_period']      = $app->notice_period;
                $list['application']['education']      = $app->education;
                $list['application']['experience']      = $app->experience;
                $list['application']['expected_salary']      = $app->expected_salary;
                $list['application']['created_at']     = Date::parse($app->created_at)->format('Y-m-d');
                $list['application']['cv']     = URL::to('uploads/resumes/'.$app->cv_link);
                $list['application']['image']    = URL::to('uploads/customers/avatar/'.$app->Applicant->avatar);
                $list['application']['other_info']      = $app->other_info;
                $list['application']['qualified']      = $app->qualified;

            }


        return $this->ReturnData($list);
    }
    public function JobFavorites(Request $request){

        $favorites= Job_Favorite::where('customer_id',session('customer')->id)->pluck('job_id');
        if($request->hasHeader('android')){
            $jobs =   Job_Offer::whereIn('id',$favorites)->get();
        }else{
            $jobs =   Job_Offer::whereIn('id',$favorites)->paginate();
        }
        $list = [];
        $list['jobs']=[];
        foreach ($jobs as $key => $job)
        {
            $list['jobs'][$key]['id']          = $job->id;
            $list['jobs'][$key]['title']       = $job->title;
            $list['jobs'][$key]['salary']      = $job->salary;
            $list['jobs'][$key]['address']     = $job->address;
            $list['jobs'][$key]['desc']        = Str::limit($job->desc, 60, '...');
            $list['jobs'][$key]['phone']        = $job->phone;
            $list['jobs'][$key]['email']        = $job->email;
            $list['jobs'][$key]['experience']        = $job->experience;

            $list['jobs'][$key]['category']        = $job->Category->name;
            $recruiter = Customer::where('id' , $job->recruiter_id)->first();
            $list['jobs'][$key]['recruiter_name']        = $recruiter->name;
            $list['jobs'][$key]['company_id']        = $recruiter->Company->id;
            $list['jobs'][$key]['company_name']        = $recruiter->Company->name;
            $list['jobs'][$key]['image']        = URL::to('uploads/company/images/'.$recruiter->Company->image);
            $list['jobs'][$key]['created_at']  = Date::parse($job->created_at)->format('Y-m-d');
            $list['jobs'][$key]['type']  = 'job';

            if(! $request->hasHeader('android')) {
                $list['current_page'] = $jobs->toArray()['current_page'];
                $list['last_page'] = $jobs->toArray()['last_page'];
                $list['first_page_url'] = $jobs->toArray()['first_page_url'];
                $list['next_page_url'] = $jobs->toArray()['next_page_url'];
                $list['last_page_url'] = $jobs->toArray()['last_page_url'];
            }

        }



        return $this->ReturnData($list);

    }

    public function AddToJobFavorites(Request $request){
        $list = [];
        $validator = Validator::make($request->all(), [
            'job_id'        => 'required',
        ]);
        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['job_id'])){
                $msg = 'يرجي ادخال job_id';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        $job = Job_Favorite::where('job_id',$request->job_id)->where('customer_id',session('customer')->id)->first();

        # check ads exist
        if($job)
        {
            $job->delete();
            $list['favorite_detials']['id']          = null;
            $list['favorite_detials']['job']       = null;
            $list['favorite_detials']['customer']        = null;
            return response()->json([
                'message'  => 'تم الحذف من المفضلة',
                'error'    => null,
                'data'      => $list,
            ],200);
        }



        $favorite = new Job_Favorite();
        $favorite->job_id = $request->job_id;
        $favorite->customer_id = session('customer')->id;
        $favorite->save();

        $list['favorite_detials']['id']          = $favorite->id;
        $list['favorite_detials']['job']       = $favorite->JobOffer->title;
        $list['favorite_detials']['customer']        = $favorite->Customer->name;


        return $this->ReturnData($list);

    }

    # delete favorite
    public function deletefavorite(Request $request)
    {

        $id = $request->input("id");

        $job = Job_Favorite::where('job_id',$id)->where('customer_id',session('customer')->id)->first();

        # check ads exist
        if(!$job)
        {
            return $this->ErrorMsgWithStatus('لا يوجد اعلان');
        }

        $job->delete();

        return $this->ReturnMsg('تم الحذف من المفضلة');
    }
    # delete ads
    public function deletejob(Request $request)
    {

        $id = $request->input("id");

        $job = Job_Offer::where('id',$id)->where('recruiter_id',session('customer')->id)->first();

        # check ads exist
        if(!$job)
        {
            return $this->ErrorMsgWithStatus('لا يوجد اعلان');
        }

        $job->delete();

        return $this->ReturnMsg('تم حذف الوظيفة');
    }

    public function confirmApplicant(Request $request){
        $list['qualified'] = [
            [
                'id'=>0,
                'name'=>'الكل',
                'value'=>0,
            ],
            [
                'id'=>1,
                'name'=>'مؤهلين',
                'value'=>1,
            ],[
                'id'=>2,
                'name'=>'غير مؤهلين',
                'value'=>2,
            ],
        ] ;
        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }

    public function AddqualifiedApplicant(Request $request){
        $list = [];
        $validator = Validator::make($request->all(), [
            'qualified_value'        => 'required',
            'app_id' => 'required',
        ]);

        foreach ((array) $validator->errors() as $value) {
            if (isset($value['qualified_value'])) {
                $msg = 'يرجي ادخال qualified_value';
                return response()->json([
                    'message' => null,
                    'error' => $msg,
                ], 400);
            }
            else if (isset($value['app_id'])) {
                $msg = 'يرجي ادخال app_id';
                return response()->json([
                    'message' => null,
                    'error' => $msg,
                ], 400);
            }
        }

        $app=Job_Application::where('id',$request->app_id)->first();
        $job=Job_Offer::where('id',$app->job_id)->first();

        if(session('customer')->id == $job->recruiter_id){
            $app->qualified = $request->qualified_value;
            $app->save();
            return response()->json([
                'message'  =>  "تم التحديد بنجاح",
                'error'    => null,
            ],200);
        }
//
        return response()->json([
            'message'  => null,
            'error'    => "فشل التحديد",
        ],400);

    }

    public function Companies(Request $request)
    {

        $search = $request->input("search");

        $keyword = $this->searchQuery($search);
        $list['result'] = [];

        if(session('customer')-> company_id == null){
            $companies = Company::where('name' , 'REGEXP' , $keyword)->get();
        }else{
            $companies = Company::where('id' , session('customer')-> company_id)->get();

        }
        foreach ($companies as $key => $not)
        {
            $list['result'][$key]['id']         = $not->id;
            $list['result'][$key]['name']       = $not->name;
        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

}
