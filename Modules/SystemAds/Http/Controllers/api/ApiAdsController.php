<?php

namespace Modules\SystemAds\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Modules\Guide\Entities\Company_Social_media;
use Modules\Magazines\Entities\Magazine_Sec;
use Modules\News\Entities\News_Section;
use Modules\Shows\Entities\Show_Section;
use Modules\Store\Entities\Store_Section;
use Modules\SystemAds\Entities\Ads_User;
use Modules\SystemAds\Entities\Membership;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Modules\SystemAds\Entities\Ads_Company;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\LocalStock\Entities\Local_Stock_Sub;
use Modules\Guide\Entities\Guide_Section;
use Modules\Guide\Entities\Guide_Sub_Section;
use Modules\Guide\Entities\Company;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Modules\FodderStock\Entities\Stock_Fodder_Section;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use Modules\LocalStock\Entities\Sec_All;

use App\Main;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Modules\SystemAds\Http\Services\AdService;

use Modules\Tenders\Entities\Tender;
use Modules\Tenders\Entities\Tender_Section;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;

class ApiAdsController extends Controller
{
    use ApiResponse;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
        ], [
            'name.required' => 'name is required',
            'password.required' => 'password is required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $k => $v) {
                return $this->ErrorMsg($v);
            }
        }

        if (Auth::guard('ads_user')->attempt(['name' => $request->name, 'password' => $request->password])) {
            $user = Ads_User::select('id','name', 'email', 'phone', 'api_token')->where('name', $request->name)->first();

            $user->api_token = Str::random(60);
            $user->save();
            $user->makeHidden(['updated_at','id']);
            return $this->ReturnData($user);
        }

        return $this->ErrorMsg('username or password is invalid');
    }


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

        $user = Ads_User::where('api_token', $request->api_token)->first();

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

    # ADD 
    public function creatAdscompany(Request $request)
    {

        $user = Ads_User::where('id', session('user')->id)->first();

        $majs = Ads_Company::where('ads_user_id', $user->id)->pluck('company_id')->toArray();

        $companies = Company::select('id', 'name')->whereIn('id', $majs)->latest()->get();

        $type_place = Config('constants.type_place');
        $type_ads = Config('constants.type_ads');

        return $this->ReturnData([
            'companies' => $companies,
            'type_place' => $type_place,
            'type_ads' => $type_ads,
        ]);

    }

    # ADD 
    public function getSections(Request $request)
    {

        $type_place = Input::get("type_place");
        $mains = Input::get("main");
        $sub = Input::get("sub");

        if (!$type_place) {
            return $this->ErrorMsg('you should send type of place');
        }

        $user = Ads_User::where('id', session('user')->id)->first();
        $sections=[];
        if($type_place == 'guide'){
            $sections = Guide_Section::select('id', 'name', 'type')->latest()->get();
        }elseif($type_place == 'localstock'){
            $sections = Local_Stock_Sections::select('id', 'name', 'type')->latest()->get();
        }elseif($type_place == 'fodderstock'){
            $sections = Stock_Fodder_Section::select('id', 'name', 'type')->latest()->get();
        }elseif($type_place == 'store'){
            $sections = Store_Section::select('id', 'name', 'type')->latest()->get();
        }elseif($type_place == 'news'){
            $sections = News_Section::select('id', 'name', 'type')->latest()->get();
        }elseif($type_place == 'shows'){
            $sections = Show_Section::select('id', 'name', 'type')->latest()->get();
        }elseif($type_place == 'magazines'){
            $sections = Magazine_Sec::select('id', 'name', 'type')->latest()->get();
        }elseif($type_place == 'tenders'){
            $sections = Tender_Section::select('id', 'name', 'type')->latest()->get();
        }
//       $sections = Main::select('id', 'name', 'type')->latest()->get();

        $list = [];

        if ($type_place !== 'ships') {

            $list['sections'] = $sections;
        }

        $list['main'] = $mains;
        $list['sub'] = $sub;

        return $this->ReturnData($list);
    }


    # ADD 
    public function getsubSections(Request $request)
    {

        $type_place = Input::get("type_place");
//        $section_type = Input::get("section_type");
        $mains = Input::get("main");
        $sub = Input::get("sub");
        $section_id=Input::get('section_id');
        if (!$type_place) {
            return $this->ErrorMsg('you should send type of place');
        }


        $user = Ads_User::where('id', session('user')->id)->first();

        $list = [];

        if ($mains == "0") {

            if ($type_place === 'guide') {

//                $section = Guide_Section::where('type', $section_type)->first();

                # check section exist
//                if (!$section) {
//                    return $this->ErrorMsg('section type not correct');
//                }

                $subs = Guide_Sub_Section::select('id', 'name')->where('section_id', $section_id)->get();
                $list['sub_sections'] = $subs;

            }
            elseif ($type_place === 'localstock') {

//                $section = Local_Stock_Sections::where('type', $section_type)->first();
//
//                # check section exist
//                if (!$section) {
//                    return $this->ErrorMsg('section type not correct');
//                }

                $majs = Sec_All::where('section_id', $section_id)->pluck('sub_id')->toArray();

                $subs = Local_Stock_Sub::select('id', 'name')->where('section_id', $section_id)->orderby('name');

                if (count($majs) > 0) {
                    $subs->orWhereIn('id', $majs);
                }

                $subs = $subs->get();

                $list['sub_sections'] = $subs;

            }
            elseif ($type_place === 'fodderstock') {

//                $section = Stock_Fodder_Section::where('type', $section_type)->first();
//
//                # check section exist
//                if (!$section) {
//                    return $this->ErrorMsg('section type not correct');
//                }

                $subs = Stock_Fodder_Sub::select('id', 'name')->where('section_id', $section_id)->get();
                $list['sub_sections'] = $subs;

            }
            else {
                $list['chack'][] = "1";
                $list['chack'][] = "0";
            }
        }


        $list['main'] = $mains;

        $list['sub'] = $sub;

        $list['type_place'] = $type_place;

//        $list['section_type'] = $section_type;

        return $this->ReturnData($list);

    }

    # store systemads 
    public function Storesystemads(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'company_id' => 'required',
            'end_date' => 'required|after_or_equal:today',
            'title' => 'required',
        ], [
            'type.required' => 'type is required',
            'company_id.required' => 'company_id is required',
            'end_date.required' => 'end_date is required',
            'end_date.after' => 'end_date should be after today or today',
            'title.required' => 'title is required',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $k => $v) {
                return $this->ErrorMsg($v);
            }
        }

        $user = Ads_User::with('Memberships')->where('id', session('user')->id)->first();

        $mem = Membership::where('ads_user_id', $user->id)->where('company_id', $request->company_id)->where('type', $request->type)->latest()->first();


        $ads = System_Ads::where('ads_user_id', $user->id)->where('company_id', $request->company_id)->where('type', $request->type)->where('status', '1')->get();


        $adsmain = System_Ads::where('ads_user_id', $user->id)->where('company_id', $request->company_id)->where('main', '1')->where('type', $request->type)->where('status', '1')->get();

        $adssub = System_Ads::where('ads_user_id', $user->id)->where('company_id', $request->company_id)->where('sub', '1')->where('type', $request->type)->where('status', '1')->get();
        if (!$mem) {
            return $this->ErrorMsg('membership not found', 404);
        }
        $today = Carbon::now();
        if($request->end_date < $mem->start_date){
            return $this->ErrorMsg('this date before the start date');
        }
        if (count($ads) >= $mem->ads_count) {
            return $this->ErrorMsg('You have exceeded the specified number of ad type');

        } elseif ($mem->end_date <= $today->format('Y-m-d')) {
            return $this->ErrorMsg('You have passed the due date');

        } elseif ($mem->end_date < $request->end_date) {
            return $this->ErrorMsg('This date must not exceed the expiry date');

        } else {
            $xyz = new System_Ads;
            $xyz->title = $request->title;
            $xyz->ads_user_id = session('user')->id;
            $xyz->type = $request->type;
            $xyz->company_id = $request->company_id;
            $xyz->end_date = $request->end_date;
            if (!is_null($request->main)) {
                if ($request->main == 1) {
                    if (count($adsmain) >= $mem->main) {
                        return $this->ErrorMsg('You have exceeded the specified number of ad type main');
                    }
                }
                $xyz->main = $request->main;
            }
            if (!is_null($request->sub)) {
                if ($request->sub == 1) {
                    if (count($adssub) >= $mem->sub) {
                        return $this->ErrorMsg('You have exceeded the specified number of ad type sub');
                    }
                }
                $xyz->sub = $request->sub;
            }

            if (!is_null($request->link)) {
                $xyz->link = $request->link;
            }

            if (!is_null($request->desc)) {
                $xyz->desc = $request->desc;
            }
            if (!is_null($request->time)) {
                $xyz->not_time = date("H:i", strtotime($request->time));
            }

            if (!is_null($request->app)) {
                $xyz->app = $request->app;
            }

            if (!is_null($request->image)) {
                if ($request->type == "popup") {

                    if ($request->file_type == "image") {
                        $xyz->image = $this->storeImage($request->image, 'full_images');
                    } else {
                        $xyz->link = $this->storeImage($request->image, 'full_images');
                    }

                } else {

                    if ($request->type == "banner") {
                        $validator = Validator::make($request->all(), [
                            'image' => 'dimensions:width=1250px,height=250px',

                        ]);

                        foreach ((array)$validator->errors() as $value) {
                            if (isset($value['image']) && !is_null($request->image)) {
                                return $this->ErrorMsg('image shoud be 1250px width and 250px height');
                            }
                        }
                    } elseif ($request->type == "logo") {
                        $validator = Validator::make($request->all(), [
                            'image' => 'dimensions:width=125px,height=150px',

                        ]);

                        foreach ((array)$validator->errors() as $value) {
                            if (isset($value['image']) && !is_null($request->image)) {
                                return $this->ErrorMsg('image shoud be 125px width and 150px height');
                            }
                        }
                    }
                    $xyz->image = $this->storeImage($request->image, 'full_images');
                }
            }

            $xyz->save();

            if (!is_null($request->type_place)) {
                $Pages = new System_Ads_Pages;
                $Pages->type = $request->type_place;
                if (!is_null($request->section_type)) {
                    $Pages->section_type = $request->section_type;
                }
                if (!is_null($request->sub_id)) {
                    $Pages->sub_id = $request->sub_id;
                }
                if (!is_null($request->chack)) {
                    $Pages->status = $request->chack;
                }

                $Pages->ads_id = $xyz->id;
                $Pages->save();
            }

            return $this->ReturnData($xyz);
        }


    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function Adscompany(Request $request)
    {

        $company_id = Input::get("company_id");

        $user = Ads_User::where('id', session('user')->id)->first();

        $majs = Ads_Company::where('ads_user_id', $user->id)->pluck('company_id')->toArray();

        $companies = Company::select('id', 'name')->whereIn('id', $majs)->latest()->get();

        $ads = System_Ads::select('id', 'title', 'status', 'end_date', 'type')->where('ads_user_id', $user->id)->where('company_id', $company_id)->get();
        $today = Carbon::now();
        # check ads exist

        $ads->map(function ($ad) use ($today) {
            $ad['status'] = intval($ad->status);
            $page = System_Ads_Pages::where('ads_id', $ad->id)->latest()->first();
            $ad['count'] = $today->diffInDays($ad->end_date, false);
            if (count($ad->SystemAdsPages) !== 0) {

                if (config('systemads.type_place_ads')[$page->type]) {
                    $ad['place'] = config('systemads.type_place_ads')[$page->type]['name'];
                }
            }
            return $ad;
        });
        $ads->makeHidden(['SystemAdsPages']);

        return $this->ReturnData(['companies' => $companies, 'xyz' => $ads]);
    }

    /**
     * @param Request $request id,status
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateads(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required',
        ], [
            'id.required' => 'id is required',
            'status.required' => 'status is required',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $k => $v) {
                return $this->ErrorMsg($v);
            }
        }

        $user = Ads_User::where('id', session('user')->id)->first();

        $ads = System_Ads::select('id', 'title', 'status', 'end_date', 'type', 'ads_user_id', 'company_id')->where('ads_user_id', $user->id)->where('id', $request->id)->first();

        if (!$ads) {
            return $this->ErrorMsg('ads not found', 404);
        }

        $mem = Membership::where('ads_user_id', $ads->ads_user_id)->where('type', $ads->type)->where('company_id', $ads->company_id)->latest()->first();

        $adss = System_Ads::where('ads_user_id', $ads->ads_user_id)->where('type', $ads->type)->where('company_id', $ads->company_id)->where('status', '1')->get();
        $today = Carbon::now();

        if (count($adss) >= $mem->ads_count && $request->status == "1") {
            return $this->ErrorMsg('You have exceeded the specified number of ad type');
        } elseif ($ads->end_date <= $today->format('Y-m-d')) {
            return $this->ErrorMsg('You in end date');
        } elseif ($mem->end_date <= $today->format('Y-m-d')) {
            return $this->ErrorMsg('You have passed the due date');
        } else {
            $ads->status = $request->status;
            $ads->save();
        }

        $page = System_Ads_Pages::where('ads_id', $ads->id)->latest()->first();

        $ads['count'] = $today->diffInDays($ads->end_date, false);
        if (count($ads->SystemAdsPages) !== 0) {
            if (config('systemads.type_place_ads')[$page->type]) {
                $ads['place'] = config('systemads.type_place_ads')[$page->type]['name'];
            }
        }

        $ads->makeHidden(['SystemAdsPages', 'ads_user_id', 'company_id']);

        return $this->ReturnData(['xyz' => $ads]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {

//        ,'AdsCompanys.Company'
        $user = Ads_User::select('id', 'name', 'email')->with('Memberships.Company:id,name')->where('id', session('user')->id)->first();

        $majs = Ads_Company::where('ads_user_id', $user->id)->pluck('company_id')->toArray();

        $companies = Company::select('id', 'name')->whereIn('id', $majs)->latest()->get();

        $list = [];
        $mem = [];

        foreach ($user->Memberships as $ky => $memb) {
            $mem['Company'] = $memb->Company->name;
            $mem['Company_id'] = $memb->Company->id;
            $mem['id'] = $memb->id;
            $mem['type'] = $memb->type;
            $mem['end_date'] = $memb->end_date;
            $mem['ads_count'] = $memb->ads_count;
            $mem['main'] = $memb->main;
            $mem['sub'] = $memb->sub;

            $list['Memberships'][] = $mem;
        }
        $user->makeHidden(['Memberships']);
        return $this->ReturnData(['user' => $user, 'companies' => $companies, 'Memberships' => $list['Memberships']]);

    }

    /**
     * @param Request $request password only
     * @return \Illuminate\Http\JsonResponse
     */
    public function editprofile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ], [
            'password.required' => 'password is required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $k => $v) {
                return $this->ErrorMsg($v);
            }
        }

        $user = Ads_User::select('id', 'name', 'email')->where('id', session('user')->id)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        return $this->ReturnData($user);
    }


    /**
     * @param Request $request param id
     * @return \Illuminate\Http\JsonResponse
     */
    public function editads(Request $request)
    {
        $id = Input::get("id");

        $ads = System_Ads::select('id', 'title', 'link', 'type', 'image')->where('id', $id)->first();

        if (!$ads) {
            return $this->ErrorMsgWithStatus('ads not found');
        }
        $ads['image'] = URL::to('uploads/full_images/' . $ads->image);
        if ($ads->type == 'notification') {
            $ads['desc'] = $ads->desc;
        }
        if ($ads->type == 'sort') {
            $ads->makeHidden(['link', 'type', 'image']);
        }
        return $this->ReturnData(['details' => $ads]);
    }


    # updads 
    public function updads(Request $request)
    {

        $list = [];

        $ads = System_Ads::with('SystemAdsPages')->where('id', $request->id)->first();

        if (!$ads) {
            return $this->ErrorMsgWithStatus('ads not found');
        }

        if ($ads->type == 'banner') {

            if (!is_null($request->title)) {
                $ads->title = $request->title;
            }

            if (!is_null($request->link)) {
                $ads->link = $request->link;
            }


            if (!is_null($request->image)) {

                $validator = Validator::make($request->all(), [
                    'image' => 'dimensions:width=1250px,height=250px',

                ]);

                foreach ((array)$validator->errors() as $value) {
                    if (isset($value['image']) && !is_null($request->image)) {
                        $msg = 'image shoud be 1250px width and 250px height';
                        return response()->json([
                            'message' => null,
                            'error' => $msg,
                        ], 400);

                    }
                }


                $photo = $request->image;
                $name = date('d-m-y') . time() . rand() . '.' . $photo->getClientOriginalExtension();
                $destinationPath = 'uploads/full_images/';
                $photo->move($destinationPath, $name);
                $ads->image = $name;
            }

            $ads->status = 0;

            $ads->update();

        } elseif ($ads->type == 'logo') {

            if (!is_null($request->title)) {
                $ads->title = $request->title;
            }

            if (!is_null($request->link)) {
                $ads->link = $request->link;
            }


            if (!is_null($request->image)) {

                $validator = Validator::make($request->all(), [
                    'image' => 'dimensions:width=125px,height=150px',

                ]);

                foreach ((array)$validator->errors() as $value) {
                    if (isset($value['image']) && !is_null($request->image)) {
                        $msg = 'image shoud be 125px width and 150px height';
                        return response()->json([
                            'message' => null,
                            'error' => $msg,
                        ], 400);

                    }
                }


                $photo = $request->image;
                $name = date('d-m-y') . time() . rand() . '.' . $photo->getClientOriginalExtension();
                $destinationPath = 'uploads/full_images/';
                $photo->move($destinationPath, $name);
                $ads->image = $name;
            }
            $ads->status = 0;
            $ads->update();

        } elseif ($ads->type == 'popup') {


            if (!is_null($request->title)) {
                $ads->title = $request->title;
            }

            if (!is_null($request->link)) {
                $ads->link = $request->link;
            }


            if (!is_null($request->image)) {


                $photo = $request->image;
                $name = date('d-m-y') . time() . rand() . '.' . $photo->getClientOriginalExtension();
                $destinationPath = 'uploads/full_images/';
                $photo->move($destinationPath, $name);
                $ads->image = $name;
            }
            $ads->status = 0;
            $ads->update();

        } elseif ($ads->type == 'notification') {


            if (!is_null($request->title)) {
                $ads->title = $request->title;
            }

            if (!is_null($request->link)) {
                $ads->link = $request->link;
            }

            if (!is_null($request->desc)) {
                $ads->desc = $request->desc;
            }
            $ads->status = 0;
            $ads->update();

        } elseif ($ads->type == 'sort') {
            if (!is_null($request->title)) {
                $ads->title = $request->title;
            }
            $ads->status = 0;
            $ads->update();

        }


        return response()->json([
            'message' => 'تم التعديل',
            'error' => null,

        ], 200);

    }


    public function company_links()
    {
        $user = Ads_User::with('AdsCompanys')->where('id', session('user')->id)->first();
        $company_id = $user['AdsCompanys'] ? $user['AdsCompanys'][0]['company_id'] : '';
        $companies = Company_Social_media::select('social_id', 'social_link')->with('Social:id,social_name')->where('company_id', $company_id)->get();
        $list = [];
        $list[0]['social_link'] = route('front_company', $company_id);
        $list[0]['social_name'] = 'الدليل';
        foreach ($companies as $key => $value) {

            $list[$key + 1]['social_link'] = $value->social_link;
            $list[$key + 1]['social_name'] = $value->social->social_name;
        }

        return $this->ReturnData($list);
    }

    public function notificationAds(Request $request)
    {

//        $validator = Validator::make($request->all(), [
//            'type' => 'required',
//            'company_id' => 'required',
//            'end_date' => 'required|after_or_equal:today',
//            'title' => 'required',
//        ], [
//            'type.required' => 'type is required',
//            'company_id.required' => 'company_id is required',
//            'end_date.required' => 'end_date is required',
//            'end_date.after' => 'end_date should be after today or today',
//            'title.required' => 'title is required',
//        ]);
//
//        if ($validator->fails()) {
//            foreach ($validator->messages()->all() as $k => $v) {
//                return $this->ErrorMsg($v);
//            }
//        }

        $user = Ads_User::with('AdsCompanys')->where('id', session('user')->id)->first();
        $company_id = count($user->AdsCompanys) > 0 ? $user->AdsCompanys[0]->company_id : 0;
        $company_id = $request->company_id ? $request->company_id : $company_id;
        $mem = Membership::where('ads_user_id', $user->id)->where('company_id', $company_id)->where('type', 'notification')->latest()->first();


        $ads = System_Ads::where('ads_user_id', $user->id)->where('company_id', $company_id)->where('type', 'notification')->where('status', '1')->get();


//        $adsmain = System_Ads::where('ads_user_id', $user->id)->where('company_id', $request->company_id)->where('main', '1')->where('type', $request->type)->where('status', '1')->get();
//
//        $adssub = System_Ads::where('ads_user_id', $user->id)->where('company_id', $request->company_id)->where('sub', '1')->where('type', $request->type)->where('status', '1')->get();
        if (!$mem) {
            return $this->ErrorMsg('membership not found', 404);
        }

        $today = Carbon::now();

        if (count($ads) >= $mem->ads_count) {
            return $this->ErrorMsg('You have exceeded the specified number of ad type');

        } elseif ($mem->end_date <= $today->format('Y-m-d')) {
            return $this->ErrorMsg('You have passed the due date');

        } elseif ($mem->end_date < $request->end_date) {
            return $this->ErrorMsg('This date must not exceed the expiry date');

        } else {


            $tempData = html_entity_decode($request->data);
            $data = json_decode($tempData,true);


//            if(isset($request->data) && $request->data != null && is_array($request->data)){
                foreach ($data as $key => $value){
                    if($request->type != 'now'){
                        $validator = Validator::make($value, [
                            'date' => 'required|after_or_equal:today',
                            'title' => 'required',
                            'desc' => 'required',
                            'image'=>'mimes:jpeg,jpg,png'
                        ], [
                            'date.required' => 'date is required',
                            'date.after' => 'date should be after today or today',
                            'title.required' => 'title is required',
                            'desc.required' => 'description is required',
                        ]);

                        if ($validator->fails()) {
                            foreach ($validator->messages()->all() as $k => $v) {
                                return $this->ErrorMsg($v);
                            }
                        }
                    }



                        $xyz = new System_Ads;
                        $xyz->title = $value['title'];
                        $xyz->desc = $value['desc'];
                        $xyz->ads_user_id = session('user')->id;
                        $xyz->type = 'notification';
                        $xyz->company_id = $company_id;
                        $xyz->end_date = isset($value['date']) ? date("Y-m-d", strtotime($value['date'])) : date('Y-m-d');
                        $xyz->not_time = isset($value['time']) ? date("H:i", strtotime($value['time'])) : date('H:i');
                        if (isset($value['file']) && $value['file'] != "") {
                            $folderPath = "uploads/full_images/";

                            $image_parts = explode(";base64,", $value['file']);
                            $image_type_aux = explode("image/", $image_parts[0]);
                            $image_type = $image_type_aux[1];
                            $image_base64 = base64_decode($image_parts[1]);

                            $image_name = date('d-m-y') . time() . rand() . '.'.$image_type;
                            $file = $folderPath . $image_name;

                            file_put_contents($file, $image_base64);
                            $xyz->image =$image_name;
//                            $xyz->image = $this->storeImage($value['file'], 'full_images');
                        }
                        if ($request->app) {
                            $xyz->app = $request->app;
                        }
                        $xyz->save();



                }
//            }else{
//                return $this->ErrorMsg('you should fill the data');
//            }

            return $this->ReturnData($xyz);
        }

    }
}
