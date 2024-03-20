<?php

namespace Modules\InternationalStock\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\InternationalStock\Entities\Ports;
use Modules\InternationalStock\Entities\Ships_Product;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Modules\InternationalStock\Entities\Ships;
use Modules\Guide\Entities\Company;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Modules\Store\Entities\Customer;
use Carbon\Carbon;
use App\Social;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;



class ApishipsController extends Controller
{
    use ApiResponse;
    # show ships

    public function ships(Request $request)
    {

        $date = $request->input("date");

        if(is_null($date))
        {
            $ships = Ships::with('Company','ShipsProduct','Ports')->latest()->get();
        }else{
                # check recomndation system
                if(!is_null($request->header('Authorization')))
                {
                    $token = $request->header('Authorization'); 
                    $token = explode(' ',$token);
                    if(count( $token) == 2)
                    {

                        $customer = Customer::where('api_token',$token[1])->first();

                            if($customer->memb == '1')
                            {
                                $ships = Ships::with('Company','ShipsProduct','Ports')->where('date',$date)->latest()->get();
                
                            }else{
                
                                if($date < Carbon::now()->subDays(7)){
                                    $msg = ' يجب التحويل للباقة المدفوعة للحصول علي بيانات اكتر من 7 أيام';
                                    return response()->json([
                                        'message'  => null,
                                        'error'    => $msg,
                                    ],400);	
                                }else{
                                    $ships = Ships::with('Company','ShipsProduct','Ports')->where('date',$date)->latest()->get();
                                }
                              
                
                            }

                    }else{
                        if($date < Carbon::now()->subDays(7)){
                            $msg = ' يجب تسجيل الدخول و التحويل للباقة المدفوعة للحصول علي بيانات اكتر من 7 أيام';
                            return response()->json([
                                'message'  => null,
                                'error'    => $msg,
                            ],400);	
                        }else{
                            $ships = Ships::with('Company','ShipsProduct','Ports')->where('date',$date)->latest()->get();
                        }
                    }
                }else{
                    if($date < Carbon::now()->subDays(7)){
                        $msg = ' يجب تسجيل الدخول و التحويل للباقة المدفوعة للحصول علي بيانات اكتر من 7 أيام ';
                        return response()->json([
                            'message'  => null,
                            'error'    => $msg,
                        ],400);	
                    }else{
                        $ships = Ships::with('Company','ShipsProduct','Ports')->where('date',$date)->latest()->get();
                    }
                }
           
        }

        $page = System_Ads_Pages::with('SystemAds')->where('type','ships')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


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

       

        if(count($ships) == 0){
            $list['ships'] = [];
            return response()->json([
                'message'  => 'لا يوجد بيانات',
                'error'    => null,
                'data'     => $list
            ],200);
        }else{
            foreach ($ships as $key => $ship)
            {
                $list['ships'][$key]['id']             = $ship->id;
                $list['ships'][$key]['name']           = $ship->name;
                $list['ships'][$key]['load']           = $ship->load;
                $list['ships'][$key]['product']        = $ship->ShipsProduct->name;
                $list['ships'][$key]['country']        = $ship->country;
                $list['ships'][$key]['date']           = $ship->date;
                $list['ships'][$key]['company']        = $ship->Company ? $ship->Company->name : null;
                $list['ships'][$key]['Port']           = $ship->Ports->name;
                $list['ships'][$key]['agent']          = $ship->agent;
                $list['ships'][$key]['dir_date']       = $ship->dir_date;
            

            }
        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # filter ships
    public function Filterships()
    {

        $dates = Ships::latest()->get()->unique('date');
        $list = [];
        $ddd = [];


       

        foreach ($dates as $key => $ship)
        {
            $ddd['date']             = $ship->date;

            $list['dates'][]             = $ddd;
 
           

        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    # statistics ship
    # statistics ship
    public function statisticsship(Request $request)
    {

        $type = $request->input("type");
        $from = $request->input("from");
        $to   = $request->input("to");
        $country = $request->input("country");



        $list = [];
        $change = [];

        $countr = [];


        $sum_product_wheat=0;
        $sum_product_corn=0;
        $sum_product_soya=0;
        $sum_product_sugar=0;

        $conts = Ships::latest()->get()->unique('country');
        $products = Ships_Product::latest()->get();


        $cont = Ships::latest();
        if($country){
            $cont->where('country',$country);
        }
        $cont = $cont->first();


        $product = Ships_Product::with('Ships');
        if($type){
            $product->where('id',$type);
        }
        $product = $product->latest()->latest()->first();

        $ships = Ships::with('Company','ShipsProduct.Ships','Ports');
        if($country){
            $ships->where('country',$country);
        }
        if($type){
            $ships->where('product_id',$type);
        }
        if($from && $to){
            $ships->whereBetween( 'date', [$from, $to]);
        }

        $ships = $ships->latest()->get()->unique('country');







////////////////////////////////////////////////////////////////////////////
        $list['ships'] = [];

        if(count($ships) > 0){
            foreach ($ships as $k => $ship)
            {
                $list['ships'][] =
                [
                    'id'=>$ship->id,
                    'product'=>$ship->ShipsProduct->name,
                    'country'=>$ship->country,
                    'load'=>$ship->load,
                ];

                if($ship->ShipsProduct->name == 'قمح'){
                    $sum_product_wheat+=$ship->load;
                }
                else if($ship->ShipsProduct->name == 'ذرة'){
                    $sum_product_corn+=$ship->load;
                }
                else if($ship->ShipsProduct->name == 'صويا'){
                    $sum_product_soya+=$ship->load;
                }                
                else if($ship->ShipsProduct->name == 'سكر'){
                    $sum_product_sugar+=$ship->load;
                }
//                $list['ships'][$k]['id']         = $ship->id;
//                $list['ships'][$k]['product']    = $ship->ShipsProduct->name;
//                $list['ships'][$k]['country']    = $ship->country;
//                $list['ships'][$k]['load']       = $ship->load;
            }
        }
////////////////////////////////////////////////////////////////////////////////



        foreach ($products as $key => $sec)
        {
            if($type && $sec->id != $type) {
                $list['products'][$key]['id'] = $sec->id;
                $list['products'][$key]['name'] = $sec->name;
                // $list['products'][$key]['load'] = $sum_product;
                if($sec->name == 'قمح'){
                    $list['products'][$key]['load'] = $sum_product_wheat;
                }
                else if($sec->name == 'ذرة'){
                    $list['products'][$key]['load'] = $sum_product_corn;
                }
                else if($sec->name == 'صويا'){
                    $list['products'][$key]['load'] = $sum_product_soya;
                }
                else if($sec->name == 'سكر'){
                    $list['products'][$key]['load'] = $sum_product_sugar;
                }

            }elseif(!$type){
                $list['products'][$key]['id'] = $sec->id;
                $list['products'][$key]['name'] = $sec->name;
                // $list['products'][$key]['load'] = $sum_product;
                if($sec->name == 'قمح'){
                    $list['products'][$key]['load'] = $sum_product_wheat;
                }
                else if($sec->name == 'ذرة'){
                    $list['products'][$key]['load'] = $sum_product_corn;
                }
                else if($sec->name == 'صويا'){
                    $list['products'][$key]['load'] = $sum_product_soya;
                }
                else if($sec->name == 'سكر'){
                    $list['products'][$key]['load'] = $sum_product_sugar;
                }

            }
        }

        if($type){
            $res  = [
                'id'=>$product->id,
                'name'=>$product->name,
                'load'=>0
            ];
            array_unshift($list['products'],$res);
        }


        if($country)
        {
            $list['countries'][]       = ['country'=>$cont->country];
        }

        foreach ($conts as $ky => $cont)
        {
            if($country && $cont->country != $country) {
                $list['countries'][]       = ['country'=>$cont->country];
            }else{
               $list['countries'][]       = ['country'=>$cont->country];
           }
        }

//////////////////////////////////////////////////////////
        // $list['ships'] = [];
//
//        $ships->map(function ($ship){
//            $ship['product'] = $ship->ShipsProduct->name;
//            return $ship;
//        });
//        $ships->only(['id', 'product', 'country','load']);
//
//        return $ships;
//         if(count($ships) > 0){
//             foreach ($ships as $k => $ship)
//             {
//                 $list['ships'][] =
//                 [
//                     'id'=>$ship->id,
//                     'product'=>$ship->ShipsProduct->name,
//                     'country'=>$ship->country,
//                     'load'=>$ship->load,
//                 ];

//                 if($ship->ShipsProduct->name == 'قمح'){
//                     $sum_product++;
//                 }
// //                $list['ships'][$k]['id']         = $ship->id;
// //                $list['ships'][$k]['product']    = $ship->ShipsProduct->name;
// //                $list['ships'][$k]['country']    = $ship->country;
// //                $list['ships'][$k]['load']       = $ship->load;
//             }
//         }
//         (array) $list['Ships'];
/////////////////////////////////////////////////////////////////////////////
        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }

//    public function statisticsship()
//    {
//
//        $type = $request->input("type");
//        $from = $request->input("from");
//        $to   = $request->input("to");
//        $country = $request->input("country");
//
//
//
//        $list = [];
//        $change = [];
//
//        $countr = [];
//
//
//        $conts = Ships::latest()->get()->unique('country');
//        $products = Ships_Product::latest()->get();
//
//
//        $cont = Ships::latest();
//        if($country){
//            $cont->where('country',$country);
//        }
//        $cont = $cont->first();
//
//
//        $product = Ships_Product::with('Ships');
//        if($type){
//            $product->where('id',$type);
//        }
//        $product = $product->latest()->latest()->first();
//
//        $ships = Ships::with('Company','ShipsProduct.Ships','Ports');
//        if($country){
//            $ships->where('country',$country);
//        }
//        if($type){
//            $ships->where('product_id',$type);
//        }
//        if($from && $to){
//            $ships->whereBetween( 'date', [$from, $to]);
//        }
//
//        $ships = $ships->latest()->get()->unique('country');
//
//
//        foreach ($products as $key => $sec)
//        {
//            if($type && $sec->id != $type) {
//                $list['products'][$key]['id'] = $sec->id;
//                $list['products'][$key]['name'] = $sec->name;
//                $list['products'][$key]['load'] = 0;
//            }elseif(!$type){
//                $list['products'][$key]['id'] = $sec->id;
//                $list['products'][$key]['name'] = $sec->name;
//                $list['products'][$key]['load'] = 0;
//            }
//        }
//
//        if($type){
//            $res  = [
//                'id'=>$product->id,
//                'name'=>$product->name,
//                'load'=>0
//            ];
//            array_unshift($list['products'],$res);
//        }
//
//
//        if($country)
//        {
//            $list['countries'][]       = ['country'=>$cont->country];
//        }
//
//        foreach ($conts as $ky => $cont)
//        {
//           if($country && $cont->country != $country) {
//               $list['countries'][]       = ['country'=>$cont->country];
//            }else{
//               $list['countries'][]       = ['country'=>$cont->country];
//           }
//        }
//
//
//        $list['ships'] = [];
////
////        $ships->map(function ($ship){
////            $ship['product'] = $ship->ShipsProduct->name;
////            return $ship;
////        });
////        $ships->only(['id', 'product', 'country','load']);
////
////        return $ships;
//        if(count($ships) > 0){
//            foreach ($ships as $k => $ship)
//            {
//                $list['ships'][] =
//                [
//                    'id'=>$ship->id,
//                    'product'=>$ship->ShipsProduct->name,
//                    'country'=>$ship->country,
//                    'load'=>$ship->load,
//                ];
////                $list['ships'][$k]['id']         = $ship->id;
////                $list['ships'][$k]['product']    = $ship->ShipsProduct->name;
////                $list['ships'][$k]['country']    = $ship->country;
////                $list['ships'][$k]['load']       = $ship->load;
//            }
//        }
////         (array) $list['Ships'];
//
//        return response()->json([
//            'message'  => null,
//            'error'    => null,
//            'data'     => $list
//        ],200);
//    }




    function unique_multidim_array($array, $key,$key1,$addedKey) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
        $key1_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array) && !in_array($val[$key1], $key1_array)) {
                $key_array[$i] = $val[$key];
                $key1_array[$i] = $val[$key1];
                $temp_array[$i] = $val;
            }else{
                $pkey = array_search($val[$key],$key_array);
                $pkey1 = array_search($val[$key1],$key1_array);
                if($pkey==$pkey1){
                    $temp_array[$pkey][$addedKey] += $val[$addedKey];
                }else{
                    $key_array[$i] = $val[$key];
                    $key1_array[$i] = $val[$key1];
                    $temp_array[$i] = $val;
                }
                // die;
            }
            $i++;
        }
        return $temp_array;
    }



    public function detials(Request $request)
    {
        $from     = $request->input("from");
        $to       = $request->input("to");
        $country  = $request->input("country");
        $id       = $request->input("id");

        $countries = Ships::latest()->get()->unique('country');

        $ships = Ships::with('Company','ShipsProduct','Ports')->whereHas('Company')->where('product_id',$id);
        if($country){
            $ships->where('country',$country);
        }
        if($from && $to){
            $ships->whereBetween( 'date', [$from, $to]);
        }
        $ships = $ships->latest()->get();


        $companies = Company::with('Ships')->whereHas('Ships',function ($q) use($id,$from,$to,$country){
            $q->where('product_id',$id);
            if($from && $to) {
                $q->whereBetween('date', [$from, $to]);
            }
            if($country){
                $q->where('country',$country);
            }
        })->get();

        $arr=[];
        $list = [];
        $countr = [];
        foreach ($countries as $ky => $cont)
        {
            $countr['country']         = $cont->country;

            $list['countries'][]         = $countr;
        }

        if(count($ships) == 0){
            $list['ships'] = [];
        }else{

            foreach ($companies as $k1 => $company)
            {

                foreach ($ships as $k => $ship)
                {

                    if($company->id == $ship->company->id){
                        array_push($arr,(object)[
                            'product'=>$ship->ShipsProduct->name,
                            'country'=>$ship->country,
                            'load'=>$ship->load,
                            'nums'=>(string) $ship->nums(),]);
                    }

                }
                $list['companies'][$k1]=(object)[
                    'id'=>$company->id,
                    'name'=>$company->name,
                    'data'=>$arr,];

            }

        }

        //prevent repeating of companies name with load and sum all load of spacific company
        $uniqueCompanyobj=array();
        foreach($ships as $ship){
            if(isset($uniqueCompanyobj[$ship->company->name])){
                $uniqueCompanyobj[$ship->company->name]+=$ship->load;
            }
            else{
                $uniqueCompanyobj[$ship->company->name]=$ship->load;
            }
        }

        $charts=array();
        foreach ($uniqueCompanyobj as $k => $ship)
        {
            array_push($charts,(object)[
                'product'=>$k,
                'load'=>$uniqueCompanyobj[$k],]);
        }
        $list['ships_charts']=$charts;

        return $this->ReturnData($list);
    }
//        $from  = $request->input("from");
//        $to    = $request->input("to");
//        $country    = $request->input("country");
//        $id  = $request->input("id");
//
//        if(is_null($country))
//        {
//
//            if(is_null($from) || is_null($to))
//            {
//                $product = Ships_Product::with('Ships')->where('id',$id)->latest()->first();
//                $ships = Ships::with('Company','ShipsProduct','Ports')->where('product_id',$id)->latest()->get();
//                $conts = Ships::latest()->get()->unique('country');
//
//            }else{
//                $product = Ships_Product::with('Ships')->where('id',$id)->latest()->first();
//                $conts = Ships::latest()->get()->unique('country');
//                $ships = Ships::with('Company','ShipsProduct','Ports')->where('product_id',$id)->whereBetween( 'date', [$from, $to])->latest()->get();
//
//            }
//
//        }else{
//
//
//            if(is_null($from) || is_null($to))
//            {
//                $product = Ships_Product::with('Ships')->where('id',$id)->latest()->first();
//                $ships = Ships::with('Company','ShipsProduct','Ports')->where('product_id',$id)->where('country',$country)->latest()->get();
//                $conts = Ships::latest()->get()->unique('country');
//
//            }else{
//                $product = Ships_Product::with('Ships')->where('id',$id)->latest()->first();
//                $conts = Ships::latest()->get()->unique('country');
//                $ships = Ships::with('Company','ShipsProduct','Ports')->where('product_id',$id)->where('country',$country)->whereBetween( 'date', [$from, $to])->latest()->get();
//
//            }
//
//        }
//        $list = [];
//        $countr = [];
//
//        foreach ($conts as $ky => $cont)
//        {
//            $countr['country']         = $cont->country;
//
//            $list['countries'][]         = $countr;
//
//
//
//        }
//
//        if(count($ships) == 0){
//            $list['ships'] = [];
//        }else{
//            foreach ($ships as $k => $ship)
//            {
//                $list['ships'][$k]['product']         = $ship->ShipsProduct->name;
//                $list['ships'][$k]['country']         = $ship->country;
//                $list['ships'][$k]['load']            = $ship->load;
//                $list['ships'][$k]['nums']            = (string) $ship->nums();
//
//
//            }
//        }
//
//
//        foreach ($ships as $k => $ship)
//        {
//            $list['ships_charts'][$k]['product']         = $ship->Company->name;
//            $list['ships_charts'][$k]['load']            = $ship->load;
//
//
//        }
//
//
//
//
//
//
//        return response()->json([
//            'message'  => null,
//            'error'    => null,
//            'data'     => $list
//        ],200);
//    }


    // public function detials()
    // {
    //     $from     = $request->input("from");
    //     $to       = $request->input("to");
    //     $country  = $request->input("country");
    //     $id       = $request->input("id");

    //     $countries = Ships::select('country')->latest()->get()->unique('country');

    //     $ships = Ships::with('Company','ShipsProduct','Ports')->whereHas('Company')->where('product_id',$id);
    //     if($country){
    //         $ships->where('country',$country);
    //     }
    //     if($from && $to){
    //         $ships->whereBetween( 'date', [$from, $to]);
    //     }
    //     $ships = $ships->latest()->get();

    //     $list = [];
    //     $countr = [];
    //     foreach ($countries as $ky => $cont)
    //     {
    //         $countr['country']         = $cont->country;

    //         $list['countries'][]         = $countr;
    //     }

    //     if(count($ships) == 0){
    //         $list['ships'] = [];
    //     }else{
    //         foreach ($ships as $k => $ship)
    //         {
    //             $list['ships'][$k]['product']         = $ship->ShipsProduct->name;
    //             $list['ships'][$k]['country']         = $ship->country;
    //             $list['ships'][$k]['load']            = $ship->load;
    //             $list['ships'][$k]['nums']            = (string) $ship->nums();
    //         }
    //     }

    //     foreach ($ships as $k => $ship)
    //     {
    //             $list['ships_charts'][$k]['product']         = $ship->Company->name;
    //             $list['ships_charts'][$k]['load']            = $ship->load;
    //     }

    //     return $this->ReturnData($list);
    // }




   
}
