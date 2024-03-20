<?php

namespace Modules\Store\Http\Controllers\api;
use Modules\Store\Entities\Interest;
use Modules\Store\Entities\Interests;
use Modules\Store\Entities\InterestsAnalysis;
use Modules\Us\Entities\Contuct;
use Twilio\Rest\Client;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordByEmail;
use App\Traits\ApiResponse;
use http\Env\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Notification\Entities\Notification_Ads;
use Modules\Store\Entities\Customer;
use Modules\Store\Entities\Notification_Ads_Users;
use Modules\Store\Entities\Store_Ads;
use Modules\Store\Entities\Store_Ads_images;
use Modules\Store\Entities\Store_Ads_Comment;
use Modules\Store\Entities\Store_Section;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Illuminate\Support\Facades\Input;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\Data_Analysis;
use Modules\Store\Entities\Chats;
use Modules\Store\Entities\Chat_Mas;
use Illuminate\Support\Str;
use Modules\SystemAds\Transformers\LogoBannerResource;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;

class ApiStoreController extends Controller
{
    use ApiResponse;

    # Register
    public function Register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'required|unique:customers',
            'phone'        => 'required|unique:customers',
            'password'     => 'required',
            'device_token' => 'required',
        ]);

        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['name']))
            {
                $msg = 'name is required';
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
            }elseif(isset($value['device_token']))
            {
                $msg = 'device_token is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        $customer = new Customer;
        $customer->name        = $request->name;
        $customer->email       = $request->email;
        $customer->phone       = $request->phone;
        $customer->device_token       = $request->device_token;
        $customer->password    = bcrypt($request->password);
        $customer->api_token   = Str::random(60);
        $customer->save();

        $list['name']              = $customer->name;
        $list['email']             = $customer->email;
        $list['phone']             = $customer->phone;
        $list['api_token']         = $customer->api_token;


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    public function Register_email_phone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email' => 'required_without_all:phone|unique:customers',
            'phone' => 'required_without_all:email|unique:customers',
            'password'     => 'required',
            'device_token' => 'required',
        ]);

        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['name']))
            {
                $msg = 'name is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }elseif( (isset($value['email']) && is_null($request->email)) && (isset($value['phone']) && is_null($request->phone)) )
            {
                $msg = 'email or phone is required';
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
            }elseif(isset($value['device_token']))
            {
                $msg = 'device_token is required';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        $customer = new Customer;
        $customer->name        = $request->name;
        $customer->email       = $request->email;
        $customer->phone       = $request->phone;
        $customer->device_token       = $request->device_token;
        $customer->password    = bcrypt($request->password);
        $customer->api_token   = Str::random(60);
        $customer->save();

        $list['name']              = $customer->name;
        $list['email']             = $customer->email;
        $list['phone']             = $customer->phone;
        $list['api_token']         = $customer->api_token;


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

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

            $customer = Customer::where('phone',$request->email)->first();



            if(!$customer){
                $msg = 'رقم العميل غير موجود';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],404);//phone not found
            }else{


                if (Auth::guard('customer')->attempt(['phone' => $request->email, 'password' => $request->password])) {

                    $customer->api_token   = Str::random(60);
                    if($request->hasHeader('android')){
                        $customer->device_token = $request->device_token;
                    }
                    $customer->save();

                    $list['name']              = $customer->name;
                    $list['email']             = $customer->email;
                    $list['phone']             = $customer->phone;
                    $list['api_token']         = $customer->api_token;

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
            $customer = Customer::where('email',$request->email)->first();

            if(!$customer){
                $msg = 'بريد العميل غير موجود';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],404);//email not found
            }else{
                if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {

                    $customer->api_token   = Str::random(60);
                    if($request->hasHeader('android')){
                        $customer->device_token = $request->device_token;
                    }
                    $customer->save();

                    $list['name']              = $customer->name;
                    $list['email']             = $customer->email;
                    $list['phone']             = $customer->phone;
                    $list['api_token']         = $customer->api_token;

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


    #survey
    public function customerSurvey(Request $request){
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'interests' => 'required',
        ]);

        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['type']))
            {
                $msg = 'يرجي ادخال نوع العميل ';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);

            }elseif(isset($value['preferences'])){
                $msg = 'يرجي ادخال اهتمامات العميل ';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }

        $token = str_replace("Bearer ","",$request->header('Authorization'));
        $customer = Customer::where('api_token',$token)->first();
        if(!$customer){
            $msg = 'العميل غير موجود';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],404);
        }

        //save type in customers table
        $customer->type = $request->type;
        $customer->save();

        // save interests in data analysis table
        foreach ($request->interests as $interest){
            $interest_id=Interest::where('id',$interest)->first();
            $interest_analyses= InterestsAnalysis::where('customer_id',$customer->id)->where('interest_id',$interest)->first();
            if($interest_id && !$interest_analyses){
                $analysis = new InterestsAnalysis();
                $analysis->customer_id = $customer->id;
                $analysis->interest_id = $interest;
                $analysis->save();
                $list['interests'][]=$interest;
            }

        }


        $list['type'] = $customer->type;
//        $list['interests'] = $request->interests;
        return response()->json([
            'message'  => "تم الحفظ",
            'error'    => null,
            'data'     => $list
        ],200);
    }

    public function interests(){
        $interests=Interest::get();
        $types = [
            ["id" => 1, "name" => "تاجر"],
            ["id" => 2, "name" => "مصنع/شركة"],
            ["id" => 2, "name" => "مزرعة/مدشة"],
            ["id" => 3, "name" => "مربي"],
            ["id" => 4, "name" => "طالب"]
        ];

        $list['types']=$types;
        $list['interests']=$interests;

        return response()->json([
            'message'  => "",
            'error'    => null,
            'data'     => $list
        ],200);
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'message' => 'required',
        ]);

        foreach ((array)$validator->errors() as $value) {
            if (isset($value['name'])) {
                $msg = 'enter your name ';
                return response()->json([
                    'message' => null,
                    'error' => $msg,
                ], 400);

            } elseif (isset($value['email'])) {
                $msg = 'enter your email ';
                return response()->json([
                    'message' => null,
                    'error' => $msg,
                ], 400);
            } elseif (isset($value['message'])) {
                $msg = 'enter your message ';
                return response()->json([
                    'message' => null,
                    'error' => $msg,
                ], 400);
            }
        }

        $contuct = new Contuct;
        $contuct->name         = $request->name;
        $contuct->email        = $request->email;
        $contuct->company      = '';
        $contuct->phone        = '';
        $contuct->desc         = $request->message;
        $contuct->job          = '';
        $contuct->save();

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $contuct
        ],200);
    }


    //emails
    public function customers(){
        $emails= Customer::select('email')->get();
        foreach ($emails as $k => $value){
            if($value->email){
                $list[]=$value->email;
            }
        }
        return response()->json( $list,200);
    }
    /**
     * @param Request $request device_token
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeFcm(Request $request)
    {
        $token = str_replace("Bearer ","",$request->header('Authorization'));
        $customer = Customer::where('api_token',$token)->first();
        if($request->device_token){
            $customer->device_token = $request->device_token;
            $customer->save();

            $list['name']              = $customer->name;
            $list['email']             = $customer->email;
            $list['phone']             = $customer->phone;
            $list['api_token']         = $customer->api_token;

            return response()->json([
                'message'  => "تم التسجيل",
                'error'    => null,
                'data'     => $list
            ],200);
        }else{
            return response()->json([
                'message'  => null,
                'error'    => 'device token required',
            ],400);
        }
    }

    /**
     * @param Request $request header token only
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $token = str_replace("Bearer ","",$request->header('Authorization'));
        $customer = Customer::where('api_token',$token)->first();
        $customer->api_token  = 'logout'.time();
        $customer->save();

        return response()->json([
            'message'  => 'تم تسجيل الخروج بنجاح',
            'error'    => null,
        ],200);

    }

    # Register social
    public function Registersocial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'required',//|unique:customers
            'device_token' => 'required',
        ],[
            'name.required'=>'name is required',
            'email.required'=>'email is required',
            'device_token.required'=>'device_token is required',
        ]);
        if ($validator->fails()) {
            return $this->ErrorMsg(implode(",",$validator->messages()->all()));
        }

        if(Customer::where('email',$request->email)->exists()){
            $customer = Customer::where('email',$request->email)->first();
        }else{
            $customer = new Customer;
            $customer->email       = $request->email;
        }
        $customer->name        = $request->name;
        $customer->device_token       = $request->device_token;
        $customer->api_token   = Str::random(60);
        $customer->save();

        $list['name']              = $customer->name;
        $list['email']             = $customer->email;
        $list['api_token']         = $customer->api_token;

        return $this->ReturnData($list);
    }



    # login
    public function loginsocial(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email'        => 'required',
        ],[
            'email.required'=>'email is required'
        ]);

        if ($validator->fails()) {
            return $this->ErrorMsg(implode(",",$validator->messages()->all()));
        }

        if(Customer::where('email',$request->email)->exists()){
            $customer = Customer::where('email',$request->email)->first();
        }else{
            $customer = new Customer;
            $customer->email       = $request->email;
            $name = explode("@", $request->email, 2);
            $name_data = !$request->name ? $name[0] : $request->name;
            $customer->name          = $name_data;
        }
        $customer->device_token  = $request->device_token;
        $customer->api_token     = Str::random(60);
        $customer->save();

        $list['name']              = $customer->name;
        $list['email']             = $customer->email;
        $list['api_token']         = $customer->api_token;

        return $this->ReturnAuthData($list);

    }



//    public function REG_log_social(Request $request)
//    {
//        if(Customer::where('email',$request->email)->exists()){
//            $validator = Validator::make($request->all(), [
//                'email'        => 'required',
//                'password'     => 'required',
//            ],[
//                'password.required'=>'password is required',
//                'email.required'=>'email is required',
//
//            ]);
//            if ($validator->fails()) {
//                return $this->ErrorMsg(implode(",",$validator->messages()->all()));
//            }
//            $customer = Customer::where('email',$request->email)->first();
//            if(!$customer){
//                $msg = 'email notfound';
//                return response()->json([
//                    'message'  => null,
//                    'error'    => $msg,
//                ],400);
//            }else{
//                if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {
//
//                    $customer->api_token   = Str::random(60);
//                    $customer->save();
//
//                    $list['name']              = $customer->name;
//                    $list['email']             = $customer->email;
//                    $list['api_token']         = $customer->api_token;
//
//                    return response()->json([
//                        'message'  => "login user",
//                        'error'    => null,
//                        'data'     => $list
//                    ],200);
//
//                }else{
//
//                    $msg = 'incorrect password';
//                    return response()->json([
//                        'message'  => null,
//                        'error'    => $msg,
//                    ],400);
//                }
//            }
//
//
//        }else{
//            $validator = Validator::make($request->all(), [
//                'name'         => 'required',
//                'email'        => 'required',//|unique:customers
//                'device_token' => 'required',
//                'google_id' => 'required',
//
//            ],[
//                'name.required'=>'name is required',
//                'email.required'=>'email is required',
//                'device_token.required'=>'device_token is required',
//                'google_id.required'=>'google_id is required',
//
//            ]);
//            if ($validator->fails()) {
//                return $this->ErrorMsg(implode(",",$validator->messages()->all()));
//            }
//            $customer = new Customer;
//            $customer->email       = $request->email;
//        }
//        $customer->name        = $request->name;
//        $customer->device_token       = $request->device_token;
//        $customer->api_token   = Str::random(60);
//        $customer->google_id        = $request->google_id;
//        $customer->password    = bcrypt($request->google_id);
//
//        $customer->save();
//
//        $list['name']              = $customer->name;
//        $list['email']             = $customer->email;
//        $list['api_token']         = $customer->api_token;
//
//
//        return response()->json([
//            'message'  => "signup done",
//            'error'    => null,
//            'data'     => $list
//        ],200);
//    }

    public function REG_log_social(Request $request)
    {//login
        if(Customer::where('email',$request->email)->exists()){
            $validator = Validator::make($request->all(), [
                'email'        => 'required',
                'google_id' => 'required',

            ],[
                'email.required'=>'email is required',
                'google_id.required'=>'google_id is required',

            ]);
            if ($validator->fails()) {
                return $this->ErrorMsg(implode(",",$validator->messages()->all()));
            }
            $customer = Customer::where('email',$request->email)->first();
            if(!$customer){
                $msg = 'email notfound';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }else if($request->google_id == $customer->google_id){
                $customer->api_token   = Str::random(60);
                $customer->save();

                $list['name']              = $customer->name;
                $list['email']             = $customer->email;
                $list['api_token']         = $customer->api_token;
                $list['phone']         = $customer->phone;

                return response()->json([
                    'message'  => "login user",
                    'error'    => null,
                    'data'     => $list
                ],200);

            }else{
                    $msg = 'you need to register by google';
                    return response()->json([
                        'message'  => null,
                        'error'    => $msg,
                    ],400);
            }


        }else{//register
            $validator = Validator::make($request->all(), [
                'name'         => 'required',
                'email'        => 'required',//|unique:customers
                'device_token' => 'required',
                'google_id' => 'required',

            ],[
                'name.required'=>'name is required',
                'email.required'=>'email is required',
                'device_token.required'=>'device_token is required',
                'google_id.required'=>'google_id is required',

            ]);
            if ($validator->fails()) {
                return $this->ErrorMsg(implode(",",$validator->messages()->all()));
            }
            $customer = new Customer;
            $customer->email       = $request->email;
        }
        $customer->name        = $request->name;
        $customer->device_token       = $request->device_token;
        $customer->api_token   = Str::random(60);
        $customer->google_id        = $request->google_id;
//        $customer->password    = bcrypt($request->google_id);

        $customer->save();

        $list['name']              = $customer->name;
        $list['email']             = $customer->email;
        $list['api_token']         = $customer->api_token;
        $list['phone']         = $customer->phone;


        return response()->json([
            'message'  => "signup done",
            'error'    => null,
            'data'     => $list
        ],200);
    }

    public function Reg_Log_Google(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'required',//|unique:customers
            'device_token' => 'required',
            'google_id' => 'required',
        ],[
            'name.required'=>'name is required',
            'email.required'=>'email is required',
            'device_token.required'=>'device_token is required',
            'google_id.required'=>'google_id is required',
        ]);
        if ($validator->fails()) {
            return $this->ErrorMsg(implode(",",$validator->messages()->all()));
        }
        //        $decodedToken=json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', request()->all()['credential'])[1]))));

        $customer=Customer::where('email',$request->email)->first();

        if(!$customer){
            $customer = new Customer;
            $customer->email       = $request->email;
            $customer->name        = $request->name;
        }
        $customer->google_id        = $request->google_id;
        $customer->device_token       = $request->device_token;
        $customer->api_token   = Str::random(60);
        $customer->save();

        $list['name']              = $customer->name;
        $list['email']             = $customer->email;
        $list['phone']         = $customer->phone;
        $list['api_token']         = $customer->api_token;

        return $this->ReturnData($list);

    }

    public function Reg_Log_Facebook(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'email'        => 'required',//|unique:customers
            'device_token' => 'required',
            'facebook_id' => 'required',
        ],[
            'name.required'=>'name is required',
            'email.required'=>'email is required',
            'device_token.required'=>'device_token is required',
            'facebook_id.required'=>'facebook_id is required',
        ]);
        if ($validator->fails()) {
            return $this->ErrorMsg(implode(",",$validator->messages()->all()));
        }


        $customer=Customer::where('email',$request->email)->first();

        if(!$customer){
            $customer = new Customer;
            $customer->email       = $request->email;
            $customer->name        = $request->name;

        }
        $customer->facebook_id        = $request->facebook_id;
        $customer->device_token       = $request->device_token;
        $customer->api_token   = Str::random(60);
        $customer->save();

        $list['name']              = $customer->name;
        $list['email']             = $customer->email;
        $list['phone']         = $customer->phone;
        $list['api_token']         = $customer->api_token;

        return $this->ReturnData($list);
    }





    # forget
    public function forget(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without_all:phone',
            'phone' => 'required_without_all:email',
        ]);

        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['email']))
            {
                $msg = 'يرجي ادخال الايميل';
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
            }
        }

        if($request->email){
            $customer = Customer::where('email',$request->email)->first();
            if(!$customer){
                $msg = 'الايميل غير موجود';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],404);
            }else{

                $customer->code   = random_int(10000, 99999);
                $customer->save();

            Mail::to($request->email)
                ->send(new ResetPasswordByEmail($customer));

                $list['name']              = $customer->name;
                $list['email']             = $customer->email;


            }
        }elseif($request->phone){
            $customer = Customer::where('phone',$request->phone)->first();

            if(!$customer){
                $msg = ' رقم الهاتف غير موجود';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],404);
            }else{

                $customer->code   = random_int(10000, 99999);
                $customer->save();

                $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
                $message = $twilio->messages->create('+2'.$customer->phone, [
                    'from' => env('TWILIO_NUMBER'),
                    'body' => ' كود التحقق لاعادة تعيين كلمة السر '.$customer->code,
                ]);

                $list['name']              = $customer->name;
                $list['phone']             = $customer->phone;

            }
        }



        return response()->json([
            'message'  => "تم الارسال",
            'error'    => null,
            'data'     => $list
        ],200);



    }



    # code
    public function code(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'        => 'required',
            'code'        => 'required',
            'password'        => 'required',
        ]);

        foreach ((array) $validator->errors() as $value)
        {
            if(isset($value['email']))
            {
                $msg = 'يرجي ادخال الايميل';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);

            }else if(isset($value['code']))
            {
                $msg = 'يرجي ادخال الكود';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);

            }elseif(isset($value['password']))
            {
                $msg = 'يرجي ادخال كلمة السر';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }
        }


        $customer = Customer::where('email',$request->email)->where('code',$request->code)->first();



        if(!$customer){
            $msg = 'الكود ليس صحيح';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);
        }else{






            $customer->api_token   = Str::random(60);
            $customer->password    = bcrypt($request->password);
            $customer->code   = null;
            $customer->save();

            $list['name']              = $customer->name;
            $list['email']             = $customer->email;
            $list['phone']             = $customer->phone;
            $list['api_token']         = $customer->api_token;


            return response()->json([
                'message'  => "تم تغيير كلمة السر بنجاح",
                'error'    => null,
                'data'     => $list
            ],200);


        }




    }



    # show all ads
//    public function ShowAds(Request $request)
//    {
//
//        $type = $request->input("type");
//        $sort       = $request->input("sort");
//        $search     = $request->input("search");
//        $date       = $request->input("date");
//        $section_id = $request->input("section_id");
//
//        # check section exist
//        if(!$section_id)
//        {
//            $section = Store_Section::where('selected','1')->first();
//
//        }else{
//            $section = Store_Section::where('id',$section_id)->first();
//        }
//
//        # check section exist
//        if(!$section)
//        {
//            $msg = 'section not found';
//            return response()->json([
//                'message'  => null,
//                'error'    => $msg,
//            ],400);
//        }
//
//        # check recomndation system
//        if(!is_null($request->header('Authorization')))
//        {
//            $token = $request->header('Authorization');
//            $token = explode(' ',$token);
//            if(count( $token) == 2)
//            {
//
//                $customer = Customer::where('api_token',$token[1])->first();
//                if($customer)
//                {
//
//                    $main_keyword = Data_Analysis_Keywords::where('keyword','store')->where('type',$section->id)->first();
//                    $main_keyword->use_count = $main_keyword->use_count + 1;
//                    $main_keyword->update();
//
//                    $user_data = User_Data_Analysis::where([['keyword_id',$main_keyword->id],['user_id',$customer->id]])->first();
//
//
//                    if($user_data)
//                    {
//                        # increment user analysis count
//                        $user_data->use_count = $user_data->use_count + 1;
//                        $user_data->update();
//                    }else{
//                        # create new record for user with keyword
//                        $user_data = new User_Data_Analysis;
//                        $user_data->user_id    = $customer->id;
//                        $user_data->keyword_id = $main_keyword->id;
//                        $user_data->use_count  = 1;
//                        $user_data->save();
//                    }
//
//
//                    # crate new record for data analysis
//                    $data_analysis = new Data_Analysis;
//                    $data_analysis->user_id    = $customer->id;
//                    $data_analysis->keyword_id = $main_keyword->id;
//                    $data_analysis->save();
//
//
//
//                }
//            }
//        }
//        $custe = Customer::with('StoreAds')->where('memb','1')->pluck('id');
//
//        if(!is_null($sort)){
//            if($sort == 1){
//                if(!is_null($search)){
//                    $adsort=[];
//                    $ads = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $search ."%")->where('approved','1');
//
//                    if($request->hasHeader('android')) {
//                        $ads = $ads->latest()->get();
//
//                    }else{
//                        $ads = $ads->latest()->paginate(10);
//
//                    }
//                }else{
//                    $adsort = Store_Ads::with('StoreAdsimages')->whereIn('user_id',$custe)->where('approved','1')->where('section_id',$section->id)->orderBy('view_count' , 'desc');
//                    $ads = Store_Ads::with('StoreAdsimages')->whereNotIn('user_id',$custe)->orWhere('user_id' , null)->where('approved','1')->where('section_id',$section->id)->orderBy('view_count' , 'desc');
//
//                    if($request->hasHeader('android')) {
//                            $adsort =$adsort->get();
//                            $ads = $ads->get();
//
//                    }else{
//                            $adsort = $adsort->paginate(10);
//                            $ads = $ads->paginate(10);
//
//                    }
//                }
//
//            }
//            elseif($sort == 0){
//                if(!is_null($search)){
//                    $adsort=[];
//                    $ads = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $search ."%")->where('approved','1')->latest();
//
//                    if($request->hasHeader('android')) {
//                        $ads = $ads->get();
//                    }else{
//                        $ads = $ads->paginate(10);
//                    }
//                }else{
//                    $adsort = Store_Ads::with('StoreAdsimages')->whereIn('user_id',$custe)->where('approved','1')->where('section_id',$section->id)->orderBy('title','desc');
//                    $ads = Store_Ads::with('StoreAdsimages')->whereNotIn('user_id',$custe)->orWhere('user_id' , null)->where('approved','1')->where('section_id',$section->id)->orderBy('title','desc');
//
//                    if($request->hasHeader('android')) {
//                          $adsort = $adsort->get();
//                          $ads = $ads->get();
//
//                    }else{
//                            $adsort = $adsort->paginate(10);
//                            $ads = $ads->paginate(10);
//
//                    }
//                }
//
//            }
//            elseif($sort == 3){
//                if(!is_null($search)){
//                    $adsort=[];
//                    $ads = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $search ."%")->where('approved','1')->latest();
//                    if($request->hasHeader('android')) {
//                        $ads = $ads->get();
//                    }else{
//                        $ads = $ads->paginate(10);
//                    }
//                }else{
//                    $adsort = Store_Ads::with('StoreAdsimages')->whereIn('user_id',$custe)->where('approved','1')->where('section_id',$section->id)->where('con_type','الرسائل')->orderBy('title');
//                    $ads = Store_Ads::with('StoreAdsimages')->whereNotIn('user_id',$custe)->orWhere('user_id' , null)->where('approved','1')->where('section_id',$section->id)->where('con_type','الرسائل')->orderBy('title');
//                    if($request->hasHeader('android')) {
//                             $adsort = $adsort->get();
//                             $ads = $ads->get();
//
//                    }else{
//                            $adsort = $adsort->paginate(10);
//                            $ads = $ads->paginate(10);
//
//                    }
//                }
//
//            }
//            elseif($sort == 4){
//
//                if(!is_null($search)){
//                    $adsort=[];
//                    $ads = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $search ."%")->where('approved','1')->latest();
//                    if($request->hasHeader('android')) {
//                        $ads = $ads->get();
//                    }else{
//                        $ads = $ads->paginate(10);
//                    }
//                }else{
//
//                    $adsort = Store_Ads::with('StoreAdsimages')->whereIn('user_id',$custe)->where('approved','1')->where('section_id',$section->id)->where('con_type','الموبايل')->orderBy('title');
//                    $ads = Store_Ads::with('StoreAdsimages')->whereNotIn('user_id',$custe)->orWhere('user_id' , null)->where('approved','1')->where('section_id',$section->id)->where('con_type','الموبايل')->orderBy('title');
//
//                    if($request->hasHeader('android')) {
//                              $adsort = $adsort->get();
//                              $ads = $ads->get();
//
//                    }else{
//                            $adsort = $adsort->paginate(10);
//                            $ads = $ads->paginate(10);
//
//                    }
//                }
//
//            }
//            elseif($sort == 5){
//
//                if(!is_null($search)){
//                    $adsort=[];
//                    $ads = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $search ."%")->where('approved','1');
//
//                    if($request->hasHeader('android')) {
//                        $ads = $ads->latest()->get();
//                    }else{
//                        $ads = $ads->latest()->paginate(10);
//                    }
//                }else{
//                    $adsort = Store_Ads::with('StoreAdsimages')->whereIn('user_id',$custe)->where('approved','1')->where('section_id',$section->id)->where('con_type','كلاهما')->orderBy('title');
//                    $ads = Store_Ads::with('StoreAdsimages')->whereNotIn('user_id',$custe)->orWhere('user_id' , null)->where('approved','1')->where('section_id',$section->id)->where('con_type','كلاهما')->orderBy('title');
//
//                    if($request->hasHeader('android')) {
//                             $adsort = $adsort->get();
//                             $ads = $ads->get();
//
//                    }else{
//                            $adsort = $adsort->paginate(10);
//                            $ads = $ads->paginate(10);
//                    }
//                }
//
//            }
//            }else{
//            if(!is_null($search)){
//                $adsort=[];
//                $ads = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $search ."%")->where('approved','1');
//                if($request->hasHeader('android')) {
//                    $ads = $ads->latest()->get();
//                }else{
//                    $ads = $ads->latest()->paginate(10);
//
//                }
//            }else{
//
//                if($request->hasHeader('android')) {
//                        $adsort = Store_Ads::with('StoreAdsimages')->whereIn('user_id',$custe)->where('approved','1')->where('section_id',$section->id)->latest()->get();
//                        $ads = Store_Ads::with('StoreAdsimages')->whereNotIn('user_id',$custe)->orWhere('user_id' , null)->where('approved','1')->where('section_id',$section->id)->latest()->get();
//                }
//                else{
//                          $adsort = Store_Ads::with('StoreAdsimages')->whereIn('user_id',$custe)->where('approved','1')->where('section_id',$section->id)->latest()->paginate(10);
//                          $ads = Store_Ads::with('StoreAdsimages')->whereNotIn('user_id',$custe)->orWhere('user_id' , null)->where('approved','1')->where('section_id',$section->id)->latest()->paginate(10);
//
//                }
//            }
//
//        }
//        if($date){
//            $adsort = [];
//            $ads = Store_Ads::with('StoreAdsimages')->where('approved','1')->where('section_id',$section->id)->whereDate('created_at',$date);
//                if($request->hasHeader('android')) {
//                    $ads = $ads->get();
//
//                }else{
//                    $ads = $ads->paginate(10);
//                }
//
//
//        }
//
//
////        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','store')->pluck('ads_id')->toArray();
//        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','store')->pluck('ads_id')->toArray();
//
//        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
//        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
//
//        $list = [];
//        $sections = Store_Section::get();
//
//        foreach ($sections as $key => $sec)
//        {
//            $list['sectors'][$key]['id']          = $sec->id;
//            $list['sectors'][$key]['name']        = $sec->name;
//            $list['sectors'][$key]['type']        = $sec->type;
//
//            if($sec->id === $section->id ){
//                $list['sectors'][$key]['selected']        = 1;
//            }else{
//                $list['sectors'][$key]['selected']        = 0;
//            }
//
//        }
//
//        $list['banners'] = count($adss) == 0 ? [] : LogoBannerResource::collection($adss);
//        $list['logos'] = count($logos) == 0 ? [] : LogoBannerResource::collection($logos);
//
//
//        if(count($ads) < 1)
//        {
//            $list['data'] = [];
//            if(! $request->hasHeader('android')) {
//                $list['current_page'] = $ads->toArray()['current_page'];
//                $list['last_page'] = $ads->toArray()['last_page'];
//            }
//        }
//        $sort = [];
//        if(count($adsort) == 0){
//            $sort['sort'] = [];
//        }else{
//
//            foreach ($adsort as $key => $sre)
//            {
//                if($sre->section_id == $section->id){
//                    $sort['sort'][$key]['id'] = $sre->id;
//                    $sort['sort'][$key]['title'] = $sre->title;
//                    $sort['sort'][$key]['salary'] = $sre->salary;
//                    $sort['sort'][$key]['address'] = $sre->address;
//                    if (count($sre->StoreAdsimages) > 0) {
//                        $sort['sort'][$key]['image'] = URL::to('uploads/stores/alboum/' . $sre->StoreAdsimages->first()->image);
//                    }
//
//                    $sort['sort'][$key]['created_at'] = Date::parse($sre->created_at)->format('Y-m-d');
//               }
//
//
//            }
//        }
//        $list['data']=[];
//
//        foreach ($ads as $key => $store)
//        {
//            if($store->section_id == $section->id){
//                $list['data'][$key]['id']          = $store->id;
//                $list['data'][$key]['title']       = $store->title;
//                $list['data'][$key]['salary']      = $store->salary;
//                $list['data'][$key]['address']     = $store->address;
//                if(count($store->StoreAdsimages) > 0){
//                    $list['data'][$key]['image']       = URL::to('uploads/stores/alboum/'.$store->StoreAdsimages->first()->image);
//                }else{
//                    $list['data'][$key]['image']="";
//                }
//
//                $list['data'][$key]['created_at']  = Date::parse($store->created_at)->format('Y-m-d');
//                if(! $request->hasHeader('android')) {
//                    $list['current_page']              = $ads->toArray()['current_page'];
//                    $list['last_page']                 = $ads->toArray()['last_page'];
//                    $list['first_page_url']            = $ads->toArray()['first_page_url'];
//                    $list['next_page_url']             = $ads->toArray()['next_page_url'];
//                    $list['last_page_url']             = $ads->toArray()['last_page_url'];
//                }
//
//            }
//
//
//        }
//
//        $list['data'] = array_merge($sort['sort'],$list['data'] );
//        return response()->json([
//            'message'  => null,
//            'error'    => null,
//            'data'     => $list
//        ],200);
//
//    }
    public function ShowAds(Request $request)
    {

        $type = $request->input("type");
        $sort       = $request->input("sort");
        $search     = $request->input("search");
        $date       = $request->input("date");
        $section_id = $request->input("section_id");


        # check section exist
        if(!$section_id)
        {
            $section = Store_Section::where('selected','1')->first();

        }else{
            $section = Store_Section::where('id',$section_id)->first();
        }

        # check section exist
        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        # check recomndation system
        if(!is_null($request->header('Authorization')))
        {
            $token = $request->header('Authorization');
            $token = explode(' ',$token);
            if(count( $token) == 2)
            {

                $customer = Customer::where('api_token',$token[1])->first();
                if($customer)
                {

                    $main_keyword = Data_Analysis_Keywords::where('keyword','store')->first();
                    $main_keyword->use_count = $main_keyword->use_count + 1;
                    $main_keyword->update();

                    $user_data = User_Data_Analysis::where([['keyword_id',$main_keyword->id],['user_id',$customer->id]])->first();


                    if($user_data)
                    {
                        # increment user analysis count
                        $user_data->use_count = $user_data->use_count + 1;
                        $user_data->update();
                    }else{
                        # create new record for user with keyword
                        $user_data = new User_Data_Analysis;
                        $user_data->user_id    = $customer->id;
                        $user_data->keyword_id = $main_keyword->id;
                        $user_data->use_count  = 1;
                        $user_data->save();
                    }


                    # crate new record for data analysis
                    $data_analysis = new Data_Analysis;
                    $data_analysis->user_id    = $customer->id;
                    $data_analysis->keyword_id = $main_keyword->id;
                    $data_analysis->save();



                }
            }
        }
        $custe = Customer::with('StoreAds')->where('memb','1')->pluck('id');

        if(!is_null($sort)){
            if($sort == 1){
                if(!is_null($search)){
                    $ads = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $search ."%")->where('approved','1');

                    if($request->hasHeader('android')) {
                        $ads = $ads->latest()->get();

                    }else{
                        $ads = $ads->latest()->paginate(10);

                    }
                }else{
                    $ads = Store_Ads::with('StoreAdsimages')->where('approved','1')->where('section_id',$section->id)->orderBy('view_count' , 'desc');

                    if($request->hasHeader('android')) {
                        $ads = $ads->get();

                    }else{
                        $ads = $ads->paginate(10);

                    }
                }

            }
            elseif($sort == 0){
                if(!is_null($search)){
                    $ads = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $search ."%")->where('approved','1')->latest();

                    if($request->hasHeader('android')) {
                        $ads = $ads->get();
                    }else{
                        $ads = $ads->paginate(10);
                    }
                }else{
                    $ads = Store_Ads::with('StoreAdsimages')->where('approved','1')->where('section_id',$section->id)->orderBy('title','desc');
                    if($request->hasHeader('android')) {
                        $ads = $ads->get();

                    }else{
                        $ads = $ads->paginate(10);
                    }
                }

            }
            elseif($sort == 3){
                if(!is_null($search)){
                    $ads = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $search ."%")->where('approved','1')->latest();
                    if($request->hasHeader('android')) {
                        $ads = $ads->get();
                    }else{
                        $ads = $ads->paginate(10);
                    }
                }else{
                    $ads = Store_Ads::with('StoreAdsimages')->where('approved','1')->where('section_id',$section->id)->where('con_type','الرسائل')->orderBy('title');

                    if($request->hasHeader('android')) {
                        $ads = $ads->get();

                    }else{
                        $ads = $ads->paginate(10);

                    }
                }

            }
            elseif($sort == 4){

                if(!is_null($search)){
                    $ads = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $search ."%")->where('approved','1')->latest();
                    if($request->hasHeader('android')) {
                        $ads = $ads->get();
                    }else{
                        $ads = $ads->paginate(10);
                    }

                }else{
                    $ads = Store_Ads::with('StoreAdsimages')->where('approved','1')->where('section_id',$section->id)->where('con_type','الموبايل')->orderBy('title');
                    if($request->hasHeader('android')) {
                        $ads = $ads->get();

                    }else{
                        $ads = $ads->paginate(10);
                    }

                }

            }
            elseif($sort == 5){

                if(!is_null($search)){
                    $ads = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $search ."%")->where('approved','1');
                    if($request->hasHeader('android')) {
                        $ads = $ads->latest()->get();
                    }else{
                        $ads = $ads->latest()->paginate(10);
                    }
                }else{
                     $ads = Store_Ads::with('StoreAdsimages')->where('approved','1')->where('section_id',$section->id)->where('con_type','كلاهما')->orderBy('title');

                    if($request->hasHeader('android')) {
                        $ads = $ads->get();
                    }else{
                        $ads = $ads->paginate(10);
                    }
                }

            }
        }else{
            if(!is_null($search)){
                $ads = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $search ."%")->where('approved','1');
                if($request->hasHeader('android')) {
                    $ads = $ads->latest()->get();
                }else{
                    $ads = $ads->latest()->paginate(10);

                }
            }else{

                if($request->hasHeader('android')) {
                    $ads = Store_Ads::with('StoreAdsimages')->where('approved','1')->where('section_id',$section->id)->latest()->paginate(10);
                }
                else{
                    $ads = Store_Ads::with('StoreAdsimages')->where('approved','1')->where('section_id',$section->id)->latest()->paginate(10);
                }
            }

        }
        if($date){
            $ads = Store_Ads::with('StoreAdsimages')->where('approved','1')->where('section_id',$section->id)->whereDate('created_at',$date);
            if($request->hasHeader('android')) {
                $ads = $ads->get();

            }else{
                $ads = $ads->paginate(10);
            }


        }


        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','store')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $list = [];
        $sections = Store_Section::get();

        foreach ($sections as $key => $sec)
        {
            $list['sectors'][$key]['id']          = $sec->id;
            $list['sectors'][$key]['name']        = $sec->name;
            $list['sectors'][$key]['type']        = $sec->type;

            if($sec->id === $section->id ){
                $list['sectors'][$key]['selected']        = 1;
            }else{
                $list['sectors'][$key]['selected']        = 0;
            }

        }

        $list['banners'] = count($adss) == 0 ? [] : LogoBannerResource::collection($adss);
        $list['logos'] = count($logos) == 0 ? [] : LogoBannerResource::collection($logos);


        if(count($ads) < 1)
        {
            $list['data'] = [];
            if(! $request->hasHeader('android')) {
                $list['current_page'] = $ads->toArray()['current_page'];
                $list['last_page'] = $ads->toArray()['last_page'];
            }
        }

        $list['data']=[];

        foreach ($ads as $key => $store)
        {
            $list['data'][$key]['id']          = $store->id;
            $list['data'][$key]['title']       = $store->title;
            $list['data'][$key]['salary']      = $store->salary;
            $list['data'][$key]['address']     = $store->address;
            if(count($store->StoreAdsimages) > 0){
                $list['data'][$key]['image']       = URL::to('uploads/stores/alboum/'.$store->StoreAdsimages->first()->image);

            }else{
                $list['data'][$key]['image']="";
            }

            $list['data'][$key]['created_at']  = Date::parse($store->created_at)->format('Y-m-d');
            if(! $request->hasHeader('android')) {
                $list['current_page']              = $ads->toArray()['current_page'];
                $list['last_page']                 = $ads->toArray()['last_page'];
                $list['first_page_url']            = $ads->toArray()['first_page_url'];
                $list['next_page_url']             = $ads->toArray()['next_page_url'];
                $list['last_page_url']             = $ads->toArray()['last_page_url'];
            }

        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show filter sections
    public function filterSections(Request $request)
    {

        $type = $request->input("type");
        $section_id = $request->input("section_id");

        # check section exist
        if(!$section_id)
        {
            $section = Store_Section::where('selected','1')->first();
        }else{
            $section = Store_Section::where('id',$section_id)->first();
        }
        # check section exist
        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);
        }
        $sections = Store_Section::get();



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



        $list['sort'] = [
            [
                'id'=>0,
                'name'=>'الأبجدي',
                'value'=>0,
            ],
            [
                'id'=>2,
                'name'=>'الأحدث',
                'value'=>null,
            ],[
                'id'=>1,
                'name'=>'الاكثر تداولا',
                'value'=>1,
            ],[
                'id'=>3,
                'name'=>'الرسايل',
                'value'=>3,
            ],[
                'id'=>4,
                'name'=>'الموبايل',
                'value'=>4,
            ],[
                'id'=>5,
                'name'=>'كلاهما',
                'value'=>5,
            ],
        ] ;



        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show all ads
    public function MyShowAds(Request $request)
    {

        $type = $request->input("type");
        $section_id=$request->input("section_id");
        if(!$section_id)
        {
            $section = Store_Section::where('selected','1')->first();
        }else{
            $section = Store_Section::where('id',$section_id)->first();
        }
        # check section exist
        if(!$section)
        {
            return $this->ErrorMsg('section not found');
        }

        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('section_type',$section->type)->where('type','store')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        if($request->hasHeader('android')){
            $ads = Store_Ads::with('StoreAdsimages')->where('section_id',$section->id)->where('user_id' , session('customer')->id)->latest()->get();

        }else{
            $ads = Store_Ads::with('StoreAdsimages')->where('section_id',$section->id)->where('user_id' , session('customer')->id)->latest()->paginate(10);

        }

        $list = [];



        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);
            }

        }

        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);

            }
        }

        if(count($ads) < 1)
        {
            $list['data'] = [];
            if(! $request->hasHeader('android')){
                $list['current_page']                     = $ads->toArray()['current_page'];
                $list['last_page']                        = $ads->toArray()['last_page'];
            }

        }

        foreach ($ads as $key => $store)
        {
            $list['data'][$key]['id']          = $store->id;
            $list['data'][$key]['title']       = $store->title;
            $list['data'][$key]['salary']      = $store->salary;
            $list['data'][$key]['address']     = $store->address;
            $list['data'][$key]['desc']        = $store->desc;
            if($request->hasHeader('android')) {
                $list['data'][$key]['status'] = $store->approved;
                $list['data'][$key]['message'] = $store->message;
            }
            if(count($store->StoreAdsimages) > 0){
                $list['data'][$key]['image']       = URL::to('uploads/stores/alboum/'.$store->StoreAdsimages->first()->image);
            }else{
                $list['data'][$key]['image']="";
            }

            $list['data'][$key]['created_at']  = Date::parse($store->created_at)->format('Y-m-d');
            if(! $request->hasHeader('android')) {
                $list['current_page']              = $ads->toArray()['current_page'];
                $list['last_page']                 = $ads->toArray()['last_page'];
                $list['first_page_url']            = $ads->toArray()['first_page_url'];
                $list['next_page_url']             = $ads->toArray()['next_page_url'];
                $list['last_page_url']             = $ads->toArray()['last_page_url'];
            }


        }
        return $this->ReturnData($list);
    }

    # show one ads
    public function ShowOneAds(Request $request)
    {
        $id = $request->input("id");
        $ads = Store_Ads::with('Customer','User','StoreAdsimages','StoreAdsComments.Customer')->where('id',$id)->first();

        # check ads exist
        if(!$ads)
        {
            return $this->ErrorMsgWithStatus('ads not found');
        }

        $section = Store_Section::where('id' , $ads->section_id)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('section_type',$section->type)->where('type','store')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        //view count
        $ads->view_count = $ads->view_count + 1;
        $ads->save();

        $list = [];
        $img = [];


        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


            }

        }


        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


            }
        }



        $list['id']                = $ads->id;
        $list['title']             = $ads->title;
        $list['salary']            = $ads->salary;
        $list['phone']             = $ads->phone;
        $list['view_count']        = $ads->view_count;
        $list['address']           = $ads->address;
        $list['paid']              = $ads->paid;
        if($ads->Customer != null){
            $list['user']       = $ads->Customer->name;
            if($request->hasHeader('device')){
                $list['user_created_at']        = $ads->Customer->created_at->diffForHumans();
            }
            $list['type']       = 'user';


        }
        if($ads->User != null){
            $list['user']       = $ads->User->name;
            if($request->hasHeader('device')){

                $list['user_created_at']        = $ads->User->created_at->diffForHumans();
            }
            $list['type']       = 'admin';


        }
        $list['desc']              = $ads->desc;
//        $list['created_at']        = date(' Y-m-d h:m:s ', strtotime($ads->created_at));
        $list['created_at']        = $ads->created_at->diffForHumans();

        // image
        foreach ($ads->StoreAdsimages as $K => $value)
        {
            if ($request->hasHeader('android')){
                $img[$K]['id'] = $value->id;
            }
            $img[$K]['image'] = URL::to('uploads/stores/alboum/'.$value->image);
        }

        $list['images']         = $img;

        return $this->ReturnData($list);
    }


    # show filter sections
    public function addadssec()
    {


        $sections = Store_Section::get();


        $list = [];

        foreach ($sections as $key => $sec)
        {
            $list['sectors'][$key]['id']          = $sec->id;
            $list['sectors'][$key]['name']        = $sec->name;
            $list['sectors'][$key]['type']        = $sec->type;


        }
        return $this->ReturnData($list);
    }


    # store ads
    public function Storestoreads(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'  => 'required',
            'desc'   => 'required',
            'phone'  => 'required',
            'salary' => 'required',
            'section_id' => 'required',
            'con_type' => 'required',
            'address' => 'required',
            'images' => 'required',
        ],[
            'title.required'=>'title is required',
            'desc.required'=>'desc is required',
            'phone.required'=>'phone is required',
            'salary.required'=>'salary is required',
            'section_id.required'=>'section_id is required',
            'con_type.required'=>'con_type is required',
            'address.required'=>'address is required',
            'images.required'=>'images is required',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $k => $v) {
                return $this->ErrorMsgWithStatus($v);
            }
        }

        $customer = Customer::with('StoreAds')->where('id',session('customer')->id)->first();
        if($customer->memb == '0'){
            if(count($customer->StoreAds) >= 5){
//                return $this->ErrorMsgWithStatus("لقد تخطيت الحد الاقصي ولا تملك صلاحية ".PREMIUM_ALERT);
                return response()->json([
                    'error'  => "لقد تخطيت الحد الاقصي ولا تملك صلاحية ".PREMIUM_ALERT,
                    'message'=> null],402);
            }
        }

        try{
            $store = new Store_Ads;
            $store->title     = $request->title;
            $store->desc      = $request->desc;
            $store->phone     = $request->phone;
            $store->address   = $request->address;
            foreach (config('store.con_type') as $k => $v)
            {
                if($request->con_type == $k){
                    $store->con_type  = $v;
                }
            }

            $store->salary    = $request->salary;
            $store->section_id= $request->section_id;
            $store->user_id   = session('customer')->id;
            $store->approved   = '2';
            $store->save();

            if($request->has('images'))
            {

                if($request->hasHeader('device')){

                    $tempData = html_entity_decode($request->images);
                    $data = json_decode($tempData,true);

                }else if($request->hasHeader('android')){
                    $data = json_decode($request->images);
                }
                else{
                    $data = $request->images;
                }

                foreach($data as $image){
                    if($request->hasHeader('device')){
                        $name = $this->StoreImageBase64($image,"stores/alboum");

                    }else if($request->hasHeader('android')){
                        $name = $this->StoreImageBase64($image,"stores/alboum","android");
                    }else{
                        $name = $this->storeImage($image,"stores/alboum");
                    }

                    $img = new Store_Ads_images;
                    $img->ads_id = $store->id;
                    $img->image = $name;
                    $img->save();
                }
            }
        }catch (\Exception $e){
            return $this->ErrorMsg($e);
        }


        $list = [];
        $imm = [];

        $list['ad_detials']['id']          = $store->id;
        $list['ad_detials']['title']       = $store->title;
        $list['ad_detials']['desc']        = $store->desc;
        $list['ad_detials']['phone']       = $store->phone;
        $list['ad_detials']['address']     = $store->address;
        $list['ad_detials']['con_type']    = $store->con_type;
        $list['ad_detials']['salary']      = $store->salary;


        foreach($store->StoreAdsimages as $image){

            $imm['id']          = $image->id;
            $imm['image']       = URL::to('uploads/stores/alboum/'.$image->image);

            $list['ad_detials']['images'][]      = $imm;
        }

        $noty = new Notification_Ads_Users;
        $noty->user_id     = session('customer')->id;
        $noty->notification_ads_id      = 1;
        $noty->ads_id      = $store->id;
        $noty->save();

        if($request->hasHeader('device')){

            $client = new \GuzzleHttp\Client();
            $data= ['notification'=> [
                'title'=> "اضافة اعلان : ".$request->title,
                'body'=> "الاعلان تحت المراجعة "
            ],
                'to' => $request->device_token
            ];

            $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAgKx7U8I:APA91bHTtpXGtPqd-gERWgT5BPY5ggT8eXVP3gjfWAvg6sFb9zkvEz-17SipoBm8EiNxV5dnbt-m-L-qMhO10ZVpE9FdwsTUYM38fsBo6JPq7PDq7vzKP_D4A8MnTfvqbb36SOfBRykz',
                ]
            ]);

            $temp=json_decode($response->getBody(), true);
        }


        return $this->ReturnData($list,'جاري مراجعة الاعلان الان');
    }

    public function notifications(Request $request )
    {
        $notifications = Notification_Ads_Users::with('NotificationAds','storeAds')->where('user_id' , session('customer')->id)->latest()->take(10)->get();
        $list = [];
        foreach ($notifications as $key => $noty)
        {
            $list['data'][$key]['id']          = $noty->id;
            $list['data'][$key]['desc'] = $noty->NotificationAds->message;
            $list['data'][$key]['title'] = "الاعلان : ".$noty->storeAds->title;
            $list['data'][$key]['created_at']  = $noty->created_at;
            $list['data'][$key]['time'] = $noty->created_at->diffForHumans();
            $list['data'][$key]['key_name'] = "market";
            $list['data'][$key]['key_id'] = $noty->storeAds->id;

        }
        return $this->ReturnData($list);
    }
    # show filter sections
    public function editadssec()
    {

        $id = $request->input("id");
        $ads = Store_Ads::with('StoreAdsimages','StoreAdsComments.Customer')
            ->select('id','title','desc','phone','address','con_type','salary')->where('id',$id)->first();

        # check ads exist
        if(!$ads)
        {
            return $this->ErrorMsg('الاعلان غير موجود');
        }

        $sections = Store_Section::select('id','name','type')->get();


        $list = [];
        $imm = [];
        $list['sectors'] = $sections;
//        return $ads;

        $list['ad_detials']['id']          = $ads->id;
        $list['ad_detials']['title']       = $ads->title;
        $list['ad_detials']['desc']        = $ads->desc;
        $list['ad_detials']['phone']       = $ads->phone;
        $list['ad_detials']['address']     = $ads->address;
        $list['ad_detials']['con_type']    = $ads->con_type;
        $list['ad_detials']['salary']      = $ads->salary;


        foreach($ads->StoreAdsimages as $image){

            $imm['id']          = $image->id;
            $imm['image']       = URL::to('uploads/stores/alboum/'.$image->image);

            $list['ad_detials']['images'][]      = $imm;
        }

        return $this->ReturnData($list);
    }

    # update ads
    public function updatestoreads(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'  => 'required',
            'desc'   => 'required',
            'phone'  => 'required',
            'salary' => 'required',
            'con_type' => 'required',
            'address' => 'required',
        ],[
            'title.required'=>'title is required',
            'desc.required'=>'desc is required',
            'phone.required'=>'phone is required',
            'salary.required'=>'salary is required',
            'section_id.required'=>'section_id is required',
            'con_type.required'=>'con_type is required',
            'address.required'=>'address is required',
            'images.required'=>'images is required',
        ]);
        info('******************************');
        info(json_encode($request->all()));

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $k => $v) {
                return $this->ErrorMsgWithStatus($v);
            }
        }


        $store = Store_Ads::find($request->id);
        $store->title     = $request->title;
        $store->desc      = $request->desc;
        $store->phone     = $request->phone;
        $store->address   = $request->address;
        $store->con_type  = $request->con_type;
        $store->salary    = $request->salary;
        $store->approved    = "2";//تحت المراجعة
        $store->save();


        if($request->hasHeader('device')){
            /**
             * old data
             */
            $tempDataOldImage = html_entity_decode($request->oldImages);
            $dataOld = json_decode($tempDataOldImage,true);
            if($dataOld == []){
                Store_Ads_images::where('ads_id' , $request->id)->delete();
            }
            /**
             * new data
             */
            $tempDataNewImage = html_entity_decode($request->NewImages);
            $dataNew = json_decode($tempDataNewImage,true);
            if($dataNew != []){
                foreach($dataNew as $image) {
                    $name = $this->StoreImageBase64($image, "stores/alboum");

                    $img = new Store_Ads_images;
                    $img->ads_id = $store->id;
                    $img->image = $name;
                    $img->save();
                }
            }


//            foreach ($dataOld as $image){
//                $link_image =  $image['fileResult'];
//                if($link_image == ""){
//                    Store_Ads_images::where('ads_id' , $request->id)->delete();
//                }
//
//            }

        }
        else if($request->hasHeader('android')){
            /**
             * old data
             */
            $dataOld = json_decode($request->oldImages,true);
            if($dataOld == []){
                Store_Ads_images::where('ads_id' , $request->id)->delete();
            }
            /**
             * new data
             */
            $dataNew = json_decode($request->NewImages,true);
            if($dataNew != []){
                foreach($dataNew as $image) {
                    $name = $this->StoreImageBase64($image,"stores/alboum","android");

                    $img = new Store_Ads_images;
                    $img->ads_id = $store->id;
                    $img->image = $name;
                    $img->save();
                }
            }
        }else{
            if($request->hasfile('images'))
            {
                Store_Ads_images::where('ads_id' , $request->id)->delete();
                foreach($request->images as $image){

                    $photo=$image;
                    $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
                    Image::make($photo)->save('uploads/stores/alboum/'.$name);

                    $img = new Store_Ads_images;
                    $img->ads_id = $store->id;
                    $img->image = $name;
                    $img->save();
                }
            }
        }


        $list = [];
        $imm = [];

        $list['ad_detials']['id']          = $store->id;
        $list['ad_detials']['title']       = $store->title;
        $list['ad_detials']['desc']        = $store->desc;
        $list['ad_detials']['phone']       = $store->phone;
        $list['ad_detials']['address']     = $store->address;
        $list['ad_detials']['con_type']    = $store->con_type;
        $list['ad_detials']['salary']      = $store->salary;


        foreach($store->StoreAdsimages as $image){

            $imm['id']          = $image->id;
            $imm['image']       = URL::to('uploads/stores/alboum/'.$image->image);

            $list['ad_detials']['images'][]      = $imm;
        }
        $noty = new Notification_Ads_Users;
        $noty->user_id     = session('customer')->id;
        $noty->notification_ads_id      = 1;
        $noty->ads_id      = $store->id;
        $noty->save();

        if($request->hasHeader('device')){

            $client = new \GuzzleHttp\Client();
            $data= ['notification'=> [
                'title'=> "اضافة اعلان : ".$request->title,
                'body'=> "الاعلان تحت المراجعة "
            ],
                'to' => $request->device_token
            ];

            $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAgKx7U8I:APA91bHTtpXGtPqd-gERWgT5BPY5ggT8eXVP3gjfWAvg6sFb9zkvEz-17SipoBm8EiNxV5dnbt-m-L-qMhO10ZVpE9FdwsTUYM38fsBo6JPq7PDq7vzKP_D4A8MnTfvqbb36SOfBRykz',
                ]
            ]);

            $temp=json_decode($response->getBody(), true);
        }

        return $this->ReturnData($list,'تم تعديل اعلان للسوق');
    }

    # delete ads
    public function Deleteads(Request $request)
    {

        $id = $request->input("id");

        $store = Store_Ads::where('id',$id)->first();

        # check ads exist
        if(!$store)
        {
            return $this->ErrorMsgWithStatus('لا يوجد اعلان');
        }

        $store->delete();

        return $this->ReturnMsg('تم حذف اعلان للسوق');
    }


    # start chat
    public function startchat(Request $request)
    {
        $list = [];
        $imm = [];

        $id = $request->input("id");

        $ads = Store_Ads::with('StoreAdsimages','Customer')->where('id',$id)->first();
        $section = Store_Section::where('id' , $ads->section_id)->first();

        $cha = Chats::with('massages')->where('owner_id' , $ads->Customer->id)->where('user_id' , session('customer')->id)->first();
        if(!$cha){
            $chaa = Chats::where('user_id' , $ads->Customer->id)->where('owner_id' , session('customer')->id)->first();
            if(!$chaa){
                $chat = new Chats;
                $chat->owner_id= $ads->Customer->id;
                $chat->user_id  = session('customer')->id;
                $chat->save();

                $list['chat']['id']          = $chat->id;

                $mass = new Chat_Mas;
                $mass->chat_id= $chat->id;
                $mass->resav_id= $chat->owner_id;
                $mass->massage= $ads->title;
                $mass->sender_id  = session('customer')->id;
                $mass->save();

                $massages = Chat_Mas::with('send','resav')->where('chat_id' , $chat->id)->get();


                foreach($massages as $maaa){

                    $imm['id']          = $maaa->id;
                    $imm['image']       = URL::to('uploads/customers/avatar/'.$maaa->send->avatar);
                    $imm['name']        = $maaa->send->name;
                    $imm['created_at']  = $maaa->created_at;
                    $imm['massage']     = $maaa->massage;

                    $list['chat']['massages'][]      = $imm;
                }


            }else{

                $list['chat']['id']          = $chaa->id;

                $mass = new Chat_Mas;
                $mass->chat_id= $chaa->id;
                $mass->resav_id= $chaa->user_id;
                $mass->massage= $ads->title;
                $mass->sender_id  = session('customer')->id;
                $mass->save();

                $massages = Chat_Mas::with('send','resav')->where('chat_id' , $chaa->id)->get();


                foreach($massages as $maaa){

                    $imm['id']          = $maaa->id;
                    $imm['image']       = URL::to('uploads/customers/avatar/'.$maaa->send->avatar);
                    $imm['name']        = $maaa->send->name;
                    $imm['created_at']  = $maaa->created_at;
                    $imm['massage']     = $maaa->massage;

                    $list['chat']['massages'][]      = $imm;
                }


            }
        }else{

            $list['chat']['id']          = $cha->id;

            $mass = new Chat_Mas;
            $mass->chat_id= $cha->id;
            $mass->resav_id= $cha->owner_id;
            $mass->massage= $ads->title;
            $mass->sender_id  = session('customer')->id;
            $mass->save();

            $massages = Chat_Mas::with('send','resav')->where('chat_id' , $cha->id)->get();


            foreach($massages as $maaa){

                $imm['id']          = $maaa->id;
                $imm['image']       = URL::to('uploads/customers/avatar/'.$maaa->send->avatar);
                $imm['name']        = $maaa->send->name;
                $imm['created_at']  = $maaa->created_at;
                $imm['massage']     = $maaa->massage;

                $list['chat']['massages'][]      = $imm;
            }


        }



        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    # my chats
    public function chats(Request $request)
    {

        $list = [];
        // $imm = [];

        // $id = $request->input("id");



        $chats = Chats::with('User','Owner','massages')->where('user_id' , session('customer')->id)->orWhere('owner_id', session('customer')->id)->latest()->get();

        if(count($chats) == 0){
            $list['chat'] = [];
        }else{

            foreach($chats as $maaa){

                if(count($maaa->massages) > 0){
                    $imm['massage']     = $maaa->massages->last()->massage;
                }
                $imm['id']          = $maaa->id;
                $imm['created_at']  = $maaa->created_at;



                if($maaa->user_id == session('customer')->id){
                    $imm['image']       = URL::to('uploads/customers/avatar/'.$maaa->Owner->avatar);
                    $imm['name']        = $maaa->Owner->name;

                }

                if($maaa->owner_id == session('customer')->id){
                    $imm['image']       = URL::to('uploads/customers/avatar/'.$maaa->User->avatar);
                    $imm['name']        = $maaa->User->name;

                }


                $list['chat'][]      = $imm;
            }
        }



        // $chaa = Chats::where('id' , $id)->first();

        // $massages = Chat_Mas::with('send','resav')->where('chat_id' , $chaa->id)->get();


        // foreach($massages as $maaa){

        //     $imm['id']          = $maaa->id;
        //     $imm['image']       = URL::to('uploads/customers/avatar/'.$maaa->send->avatar);
        //     $imm['name']        = $maaa->send->name;
        //     $imm['created_at']  = $maaa->created_at;
        //     $imm['massage']     = $maaa->massage;

        //     $list['chat']['massages'][]      = $imm;
        // }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }

    # my chats
    public function chatsmassage(Request $request)
    {

        $list = [];
        $imm = [];

        $id = $request->input("id");



        $chaa = Chats::where('id' , $id)->first();

        $massages = Chat_Mas::with('send','resav')->where('chat_id' , $chaa->id)->get();


        foreach($massages as $maaa){

            $imm['id']          = $maaa->id;
            $imm['image']       = URL::to('uploads/customers/avatar/'.$maaa->send->avatar);
            $imm['name']        = $maaa->send->name;
            $imm['created_at']  = $maaa->created_at;
            $imm['massage']     = $maaa->massage;

            $list['chat']['massages'][]      = $imm;
        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }




    # write massage
    public function writemassage(Request $request)
    {


        $list = [];
        $imm = [];

        $id = $request->id;

        $chat = Chats::where('id',$id)->first();

        if($chat->owner_id === session('customer')->id){

            $mass = new Chat_Mas;
            $mass->chat_id= $chat->id;
            $mass->resav_id= $chat->user_id;
            $mass->massage= $request->massage;
            $mass->sender_id  = session('customer')->id;
            $mass->save();

        }else{

            $mass = new Chat_Mas;
            $mass->chat_id= $chat->id;
            $mass->resav_id= $chat->owner_id;
            $mass->massage= $request->massage;
            $mass->sender_id  = session('customer')->id;
            $mass->save();
        }

        $massages = Chat_Mas::with('send','resav')->where('chat_id' , $chat->id)->get();


        foreach($massages as $maaa){

            $imm['id']          = $maaa->id;
            $imm['image']       = URL::to('uploads/customers/avatar/'.$maaa->send->avatar);
            $imm['name']        = $maaa->send->name;
            $imm['created_at']  = $maaa->created_at;
            $imm['massage']     = $maaa->massage;

            $list['chat']['massages'][]      = $imm;
        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }



    /////////new for check login
    # check
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_token' => 'required',
        ], [
            'api_token.required' => 'api_token is required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $k => $v) {
                return $this->ErrorMsg($v);
            }
        }

        $user = Customer::where('api_token', $request->api_token)->first();

        if (!$user) {
            return response()->json([
                'message' => null,
                'error' => 'user not login',
                'data' => 0,
            ], 401);
        } else {
            return response()->json([
                'message' => null,
                'error' => 'user login',
                'data' => 1,
            ], 200);

        }

    }


    # delete customer
    public function DeleteCustomer(Request $request)
    {
        $customer = Customer::where('id',session('customer')->id)->first();
        if($customer->avatar != 'default.png')
        {
            File::delete('uploads/customers/avatar/'.$customer->avatar);
        }
        $customer->delete();

        return response()->json([
            'message' => 'تم حذف الحساب',
            'error' => '',
        ], 200);
    }

    public function credit() {
        $type='credit';
        $token = $this->getToken();
        $order = $this->createOrder($token);
        $paymentToken = $this->getPaymentToken($order, $token,$type);
        return response()->json([
            'message' => 'iframe credit payment',
            'error' => null,
            'data' =>['iframe'=>'https://accept.paymobsolutions.com/api/acceptance/iframes/'.env('PAYMOB_IFRAME_ID').'?payment_token='.$paymentToken]
        ], 200);

//    return \Redirect::away('https://portal.weaccept.co/api/acceptance/iframes/'.env('PAYMOB_IFRAME_ID').'?payment_token='.$paymentToken);
    }

    public function wallet(Request $request) {
        $type='wallet';
        $token = $this->getToken();
        $order = $this->createOrder($token);
        $paymentToken = $this->getPaymentToken($order, $token,$type);
        $iframe=$this->walletPayment($paymentToken,$request->phone);
        return response()->json([
            'message' => 'iframe wallet payment',
            'error' => null,
            'data' =>['iframe'=>$iframe]
        ], 200);
    }

    public function getToken() {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://accept.paymob.com/api/auth/tokens', [
            'json' => ['api_key' => env('PAYMOB_API_KEY')]
        ]);
        $body = $response->getBody();
        // Explicitly cast the body to a string
        $stringBody = (string) $body;
        $jsonresponse=json_decode($stringBody);
        return $jsonresponse->token;
    }

    public function createOrder($token) {
        $items = [];

        $data = [
            "auth_token" =>   $token,
            "delivery_needed" =>"false",
            "amount_cents"=> "3000",
            "currency"=> "EGP",
            "items"=> $items,

        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://accept.paymob.com/api/ecommerce/orders', [
            'json' => $data
        ]);
        $body = $response->getBody();
        // Explicitly cast the body to a string
        $stringBody = (string) $body;
        $jsonresponse=json_decode($stringBody);

        return $jsonresponse;
    }

    public function getPaymentToken($order, $token , $type)
    {
        $user= Auth::guard('customer-api')->user();
        $name = trim($user->name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );

        $billingData = [
            "apartment" => "NA",
            "email" => $user->email,
            "floor" => "NA",
            "first_name" => $first_name,
            "street" => "NA",
            "building" => "NA",
            "phone_number" => $user->phone,
            "shipping_method" => "NA",
            "postal_code" => "NA",
            "city" => "NA",
            "country" => "NA",
            "last_name" => $last_name,
            "state" => "NA"
        ];
        if($type=='credit'){
            $data = [
                "auth_token" => $token,
                "amount_cents" => "3000",
                "expiration" => 3600,
                "order_id" => $order->id,
                "billing_data" => $billingData,
                "currency" => "EGP",
                "integration_id" => env('PAYMOB_INTEGRATION_CREDIT_ID'),
            ];
        }
        elseif($type=='wallet'){
            $data = [
                "auth_token" => $token,
                "amount_cents" => "3000",
                "expiration" => 3600,
                "order_id" => $order->id,
                "billing_data" => $billingData,
                "currency" => "EGP",
                "integration_id" => env('PAYMOB_INTEGRATION_WALLET_ID'),
            ];
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://accept.paymob.com/api/acceptance/payment_keys', [
            'json' => $data
        ]);
        $body = $response->getBody();
        // Explicitly cast the body to a string
        $stringBody = (string) $body;
        $jsonresponse=json_decode($stringBody);

        Customer::where('id', $user->id)->update(array('order_id'=>$order->id));

//        ///set memb=1
//      Customer::where('id', $user->id)->update('order_id',$order->id);

        return $jsonresponse->token;
    }

    public function walletPayment($token,$phone)
    {
        $wallet_data=[
            "identifier" => $phone,
            "subtype" => "WALLET"
        ];
        $data = [
            "source" => $wallet_data,
            "payment_token" => $token
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://accept.paymob.com/api/acceptance/payments/pay', [
            'json' => $data
        ]);
        $body = $response->getBody();
        // Explicitly cast the body to a string
        $stringBody = (string) $body;
        $jsonresponse=json_decode($stringBody);
        return $jsonresponse->redirect_url;
    }

    public function callback(Request $request)
    {
//        $user=Auth::guard('customer')->user();

        $data = $request->all();
        ksort($data);
        $hmac = $data['hmac'];

        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',

        ];
        $connectedString = '';
        foreach ($data as $key => $element) {
            if(in_array($key, $array)) {
                $connectedString .= $element;
            }
        }
        $secret = env('PAYMOB_HMAC');
        $hased = hash_hmac('sha512', $connectedString, $secret);
        if ( $hased == $hmac) {
            if($data['success']=="true"){
                ///set memb=1
                Customer::where('order_id', $data['order'])->update(array('memb' => '1'));

                echo "<script>
                alert('تمت عملية الدفع بنجاح انت الان علي الباقة المدفوعة');
                window.location.href='https://admin.elkenany.com/';
                </script>";
                exit;
            }
            else{
                echo "<script>
                alert('خطأ في عملية الدفع برجاء التأكد من وجود رصيد كافي و التأكد من البيانات');
                window.location.href='https://admin.elkenany.com/';
                </script>";
            }
//            echo 'secure';

        }
        else{
            echo 'not secure'; exit;
        }
    }

//    public function callback(Request $request)
//    {
//        $user=Auth::guard('customer-api')->user();
//
//        $data = $request->all();
//        ksort($data);
//        $hmac = $data['hmac'];
//
//        $array = [
//            'amount_cents',
//            'created_at',
//            'currency',
//            'error_occured',
//            'has_parent_transaction',
//            'id',
//            'integration_id',
//            'is_3d_secure',
//            'is_auth',
//            'is_capture',
//            'is_refunded',
//            'is_standalone_payment',
//            'is_voided',
//            'order',
//            'owner',
//            'pending',
//            'source_data_pan',
//            'source_data_sub_type',
//            'source_data_type',
//            'success',
//        ];
//        $connectedString = '';
//        foreach ($data as $key => $element) {
//            if(in_array($key, $array)) {
//                $connectedString .= $element;
//            }
//        }
//        $secret = env('PAYMOB_HMAC');
//        $hased = hash_hmac('sha512', $connectedString, $secret);
//        if ( $hased == $hmac) {
//
//            if($data['success']==true){
//                ///set memb=1
//                Customer::where('id', $user->id)->update(array('memb' => '1'));
//                return response()->json([
//                    'message'  => "payment done successfully",
//                    'error'    => null,
//                ],200);
//            }
//            else{
//                return response()->json([
//                    'message'  => null,
//                    'error'    => "payment not successful ",
//                ],400);
//            }
////            echo 'secure';
//
//        }
//        else{
//            return response()->json([
//                'message'  => null,
//                'error'    => "not secure ",
//            ],400);
//        }
//    }

}
