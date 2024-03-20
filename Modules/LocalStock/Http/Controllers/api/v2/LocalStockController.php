<?php

namespace Modules\LocalStock\Http\Controllers\api\v2;

use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Modules\LocalStock\Entities\Local_Stock_Sub;
use Modules\LocalStock\Http\Services\GuideService;
use Modules\LocalStock\Http\Services\LocalService;
use Modules\SystemAds\Entities\System_Ads;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Transformers\LogoBannerResource;

class LocalStockController extends Controller
{
    use ApiResponse;

    public function local_tables(Request $request,LocalService $service)
    {
        $id = $request->input("id");
        $date = $request->input("date");
        $status = "new";
        $message = "";

        if(!$date){
            $date = date('Y-m-d');
        }
        if(Auth::guard('customer-api')->user() && Auth::guard('customer-api')->user()->memb != 1){
            if($date && $date < Carbon::now()->subDays(7)) {
                return $this->ErrorMsg(PREMIUM_ALERT);
            }
        }else{
            if($date && $date < Carbon::now()->subDays(7) && !Auth::guard('customer-api')->user()){
                return $this->ErrorMsg(LOGIN_ALERT);
            }
        }


        $sub = Local_Stock_Sub::with('LocalStockColumns')->where('id',$id)->first();

        # check sub exist
        if(!$sub) { return $this->ErrorMsgWithStatus('sub not found');}

        $sub->view_count = $sub->view_count + 1;

        $page = System_Ads_Pages::where('sub_id',$id)->where('type','localstock')->pluck('ads_id')->toArray();

        $banners = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        // columns
        $columns[0]   = "الأسم";
        foreach ($sub->LocalStockColumns as $k=> $col)
        {
            if($col->type == 'price' ){
                $columns[2]   = $col->name;
            }

            if($col->type == 'change' ){
                $columns[6]   = $col->name;
            }

            if($col->type == null ){

                    if($col->name=="نظام الشحن والتعبئة"){
                        $columns[3]   = $col->name;
                    }
                    elseif($col->name=="إسم الصنف"){
                        $columns[4]   = $col->name;

                    }
                    elseif($col->name=="الوزن"){
                        $columns[5]   = $col->name;
                    }

                    elseif($col->name=="حالة السعر"){
                        $columns[7]   = $col->name;
                    }
                    elseif($col->name=="العمر"){
                        $columns[9]   = $col->name;
                    }

                    elseif($col->name=="النوع"){
                        $columns[10]   = $col->name;
                    }
                    elseif($col->name=="نوع الكتكوت"){
                        $columns[11]   = $col->name;
                    }
                    elseif($col->name=="وزن العبوة"){
                        $columns[12]   = $col->name;
                    }
            }
        }
        $columns[8]   = 'إتجاه السعر';

        $members = $service->TableLikeweb($request,$ads,$date);

        while(count($members) == 0){
            $date = date("Y-m-d", strtotime($date ."-1 day"));
            $day = date('l', strtotime($date));

            if($day == 'Friday'){
                $date = date( 'Y-m-d', strtotime( $date . ' -1 day' ));
            }
            $members = $service->TableLikeweb($request,$ads,$date);
            $status = 'old';
            $message = 'تنوية: تم اخر تحديث في يوم '.$date;
        }



        return $this->ReturnData([
            'message'=>$message,
            'status'=>$status,
            'columns' =>$columns,
            'banners' =>LogoBannerResource::collection($banners),
            'logos'   =>LogoBannerResource::collection($logos),
            'members' =>$members,
        ]);
    }
    public function new_local_tables(Request $request,LocalService $service)
    {
        $id = $request->input("id");
        $date = $request->input("date");
        $status = "new";
        $message = "";

        if(!$date){
            $date = date('Y-m-d');
        }
        if(Auth::guard('customer-api')->user() && Auth::guard('customer-api')->user()->memb != 1){
            if($date && $date < Carbon::now()->subDays(7)) {
                return $this->ErrorMsg(PREMIUM_ALERT);
            }
        }else{
            if($date && $date < Carbon::now()->subDays(7) && !Auth::guard('customer-api')->user()){
                return $this->ErrorMsg(LOGIN_ALERT);
            }
        }

        // columns initialize
        $columns["name"]   = "";
        $columns["price"]   = "";
        $columns["change"]   = "";
        $columns["charging_system"]   = "";
        $columns["categorize_name"]   = "";
        $columns["weight"]   = "";
        $columns["price_status"]   = "";
        $columns["age"]   = "";
        $columns["product_type"]   = "";
        $columns["chick_type"]   = "";
        $columns["weight_container"]   = "";
        $columns["statistics"]   = "";
        $columns["comp_id"]   = "";
        $columns["mem_id"]   = "";
        $columns["kind"]   = "";
        $columns["change_date"]   = "";
        $columns["type"]   = "";



        $sub = Local_Stock_Sub::with('LocalStockColumns')->where('id',$id)->first();

        # check sub exist
        if(!$sub) { return $this->ErrorMsgWithStatus('sub not found');}

        $sub->view_count = $sub->view_count + 1;

        $page = System_Ads_Pages::where('sub_id',$id)->where('type','localstock')->pluck('ads_id')->toArray();

        $banners = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        // columns
        $columns["name"]   = "الأسم";
        foreach ($sub->LocalStockColumns as $k=> $col)
        {
//            dump($col->name);
            if($col->type == 'price' ){
                $columns["price"]   = $col->name;
            }

            if($col->type == 'change' ){
                $columns["change"]   = $col->name;
                $columns["change_date"]   = "";

            }

            if($col->type == null ){

                if($col->name=="نظام الشحن والتعبئة"){
                    $columns["charging_system"]   = $col->name;
                }
                elseif($col->name=="إسم الصنف"){
                    $columns["categorize_name"]   = $col->name;

                }
                elseif($col->name=="الوزن"){
                    $columns["weight"]   = $col->name;
                }

                elseif($col->name=="حالة السعر"){
                    $columns["price_status"]   = $col->name;
                }
                elseif($col->name=="العمر"){
                    $columns["age"]   = $col->name;
                }

                elseif($col->name=="النوع"){
                    $columns["product_type"]   = $col->name;
                }
                elseif($col->name=="نوع الكتكوت"){
                    $columns["chick_type"]   = $col->name;
                }
                elseif($col->name=="وزن العبوة"){
                    $columns["weight_container"]   = $col->name;
                }
            }
        }
        $columns["statistics"]   = 'إتجاه السعر';

        $members = $service->TableLikeweb($request,$ads,$date);

        while(count($members) == 0){
            $date = date("Y-m-d", strtotime($date ."-1 day"));
            $day = date('l', strtotime($date));

            if($day == 'Friday'){
                $date = date( 'Y-m-d', strtotime( $date . ' -1 day' ));
            }
            $members = $service->TableLikeweb($request,$ads,$date);
            $status = 'old';
            $message = 'تنوية: تم اخر تحديث في يوم '.$date;
        }



        return $this->ReturnData([
            'message'=>$message,
            'status'=>$status,
            'columns' =>$columns,
            'banners' =>LogoBannerResource::collection($banners),
            'logos'   =>LogoBannerResource::collection($logos),
            'members' =>$members,
        ]);
    }

}
