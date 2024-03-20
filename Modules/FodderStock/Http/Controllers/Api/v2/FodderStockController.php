<?php

namespace Modules\FodderStock\Http\Controllers\Api\v2;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Modules\FodderStock\Entities\Fodder_Stock;
use Modules\FodderStock\Entities\Fodder_Stock_Move;
use Modules\FodderStock\Entities\Stock_Feeds;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use Modules\FodderStock\Http\Services\FodderService;
use Modules\FodderStock\Transformers\TablesFooderResource;
use Modules\Guide\Entities\Company;
use Modules\Store\Entities\Customer;
use Modules\SystemAds\Entities\System_Ads;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Transformers\LogoBannerResource;

class FodderStockController extends Controller
{
    use ApiResponse;

    /**
     * @param Request $request
     * @param FodderService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function fodder_tables(Request $request,FodderService $service)
    {
        $id = $request->input("id");
        $date = $request->input("date");
        $fod_id = $request->input("fod_id");
        $comp_id = $request->input("comp_id");

        if(Auth::guard('customer-api')->user() && Auth::guard('customer-api')->user()->memb != 1){
            if($date && $date < Carbon::now()->subDays(7)) {
                return $this->ErrorMsg(PREMIUM_ALERT);
            }
        }else{
            if($date && $date < Carbon::now()->subDays(7) && !Auth::guard('customer-api')->user()){
                return $this->ErrorMsg(LOGIN_ALERT);
            }
        }

        $feed_name = "الكل";
        $company_name = "الكل";
        $status = "new";
        $message = "";
        $columns[0]="الأسم";
        $columns[1]="الصنف";
        $columns[2]="السعر";
        $columns[6]="مقدار التغير";
        $columns[8]="إتجاه السعر";

        if(!$date){
            $date = date('Y-m-d');
        }

        $sub = Stock_Fodder_Sub::where('id',$id)->first();

        if(!$sub) { return $this->ErrorMsgWithStatus('sub not found'); }
        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$id)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $banners = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id');
        $fod = '';
        if($fod_id){
            $fod = Stock_Feeds::where('id',$fod_id)->where('section_id',$id)->first();
            $feed_name = $fod->name;
        }else{
            if(!$comp_id){
                $fod = Stock_Feeds::where('section_id',$id)->where('fixed','1')->first();
                $feed_name = $fod->name;
            }
        }
        $data = $service->FodderTableProccess($request,$ads,$date,$fod);
        while(count($data) == 0 ){
            $date = date("Y-m-d", strtotime($date ."-1 day"));
            $day = date('l', strtotime($date));

            if($day == 'Friday'){
                $date = date( 'Y-m-d', strtotime( $date . ' -1 day' ) );
            }
            $data = $service->FodderTableProccess($request,$ads,$date,$fod);
            $status = 'old';
            $message = 'تنوية: تم اخر تحديث في يوم '.$date;
        }


        return $this->ReturnData([
            'message'=>$message,
            'status'=>$status,
            'company_name'=>$company_name,
            'feed_name'=>$feed_name,
            'columns'=>$columns,
            'banners'=>LogoBannerResource::collection($banners),
            'logos'=>LogoBannerResource::collection($logos),
            'members'=>$data,
        ]);
    }

    public function new_fodder_tables(Request $request,FodderService $service)
    {
        $id = $request->input("id");
        $date = $request->input("date");
        $fod_id = $request->input("fod_id");
        $comp_id = $request->input("comp_id");

        if(Auth::guard('customer-api')->user() && Auth::guard('customer-api')->user()->memb != 1){
            if($date && $date < Carbon::now()->subDays(7)) {
                return $this->ErrorMsg(PREMIUM_ALERT);
            }
        }else{
            if($date && $date < Carbon::now()->subDays(7) && !Auth::guard('customer-api')->user()){
                return $this->ErrorMsg(LOGIN_ALERT);
            }
        }

        $feed_name = "الكل";
        $company_name = "الكل";
        $status = "new";
        $message = "";
        $columns["comp_id"]="";
        $columns["mem_id"]="";
        $columns["type"]="";
        $columns["name"]="الأسم";
        $columns["feed"]="الصنف";
        $columns["price"]="السعر";
        $columns["change"]="مقدار التغير";
        $columns["change_date"]="";

        $columns["statistics"]="إتجاه السعر";

        if(!$date){
            $date = date('Y-m-d');
        }

        $sub = Stock_Fodder_Sub::where('id',$id)->first();

        if(!$sub) { return $this->ErrorMsgWithStatus('sub not found'); }
        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$id)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $banners = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id');
        $fod = '';
        if($fod_id){
            $fod = Stock_Feeds::where('id',$fod_id)->where('section_id',$id)->first();
            $feed_name = $fod->name;
        }else{
            if(!$comp_id){
                $fod = Stock_Feeds::where('section_id',$id)->where('fixed','1')->first();
                $feed_name = $fod->name;
            }
        }
        $data = $service->FodderTableProccess($request,$ads,$date,$fod);
        while(count($data) == 0 ){
            $date = date("Y-m-d", strtotime($date ."-1 day"));
            $day = date('l', strtotime($date));

            if($day == 'Friday'){
                $date = date( 'Y-m-d', strtotime( $date . ' -1 day' ) );
            }
            $data = $service->FodderTableProccess($request,$ads,$date,$fod);
            $status = 'old';
            $message = 'تنوية: تم اخر تحديث في يوم '.$date;
        }


        return $this->ReturnData([
            'message'=>$message,
            'status'=>$status,
            'company_name'=>$company_name,
            'feed_name'=>$feed_name,
            'columns'=>$columns,
            'banners'=>LogoBannerResource::collection($banners),
            'logos'=>LogoBannerResource::collection($logos),
            'members'=>$data,
        ]);
    }

    # statistics members
    public function statisticsFoddermembers(Request $request,FodderService $service)
    {


        $id   = $request->input("id");
        $type = $request->input("type");
        $from = $request->input("from");
        $to   = $request->input("to");

        $mem_id    = $request->input("mem_id");
        $com_id    = $request->input("com_id");

        $arr1=array();
        $arr2=array();
        $list = [];
        $list['list_members']=[];
        if(is_null($mem_id) && is_null($com_id))
        {
            if(is_null($from) || is_null($to))
            {
                $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
                $feeds = Fodder_Stock::where('sub_id',$id)->latest()->get();
            }else{

                $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
                $mov = Fodder_Stock_Move::select('stock_id')->whereBetween( 'created_at', [$from, $to]);
                $feeds = Fodder_Stock::where('sub_id',$id)->whereIn('id',$mov)->get();
            }

            $i=0;
            foreach ($feeds as $key => $sec) {
                $list['changes_members'][$key]['id'] = $sec->id;
                $list['changes_members'][$key]['name'] = $sec->Company->name;
                $list['changes_members'][$key]['categorize'] = $sec->stockFeed->name;
                $list['changes_members'][$key]['compId']         = $sec->Company->id;

                $list['list_members'][$i]['id']         = $sec->Company->id;
                $list['list_members'][$i]['name']   = $feeds[$key]->Company->name;
                if(isset($feeds[$key-1]) && $sec->Company->id != $feeds[$key-1]->Company->id){
                    $list['list_members'][$i]['id']         = $feeds[$key-1]->Company->id;
                    $list['list_members'][$i]['name']   = $feeds[$key-1]->Company->name;
                    $i++;
                }

                if (is_null($from) || is_null($to)) {

                    $list['changes_members'][$key]['changes']=$service->changes($sec->fodderStockMoves,$key);
                }
                else{
                    $moves = Fodder_Stock_Move::where('stock_id',$sec->id)->whereBetween( 'created_at', [$from, $to])->get();

                    $list['changes_members'][$key]['changes']=$service->changes($moves,$key);
                }

                $list['changes_members'][$key]['changes']=$service->unique_price_date_for_statistics($list['changes_members'][$key]['changes']);
                $list['changes_members'][$key]['change']     = $service->change_rate($list['changes_members'][$key]['changes']);
                $list['changes_members'][$key]['counts'] = $service->counts($list['changes_members'][$key]['changes'],$key);
            }

        }
        //////////////////////////////////////////////////////////////
        else if($com_id != null){

            $companies= Fodder_Stock::where('sub_id',$id)->orderBy('company_id', 'DESC')->get();
            $i=0;
            foreach ($companies as $key => $sec) {
                $list['list_members'][$i]['id'] = $sec->Company->id;
                $list['list_members'][$i]['name'] = $companies[$key]->Company->name;
                if (isset($companies[$key - 1]) && $sec->Company->id != $companies[$key - 1]->Company->id) {
                    $list['list_members'][$i]['id'] = $companies[$key - 1]->Company->id;
                    $list['list_members'][$i]['name'] = $companies[$key - 1]->Company->name;
                    $i++;
                }
            }
            $list['changes_members']=[];
            $feeds=Fodder_Stock::where('sub_id',$id)->where('company_id',$com_id)->latest()->get();
            foreach ($feeds as $key => $sec) {

                $list['changes_members'][$key]['id'] = $sec->id;
                $list['changes_members'][$key]['name'] = $sec->Company->name;
                $list['changes_members'][$key]['categorize'] = $sec->stockFeed->name;
                $list['changes_members'][$key]['compId']         = $sec->Company->id;

                if (is_null($from) || is_null($to)) {

                    $list['changes_members'][$key]['changes']=$service->changes($sec->fodderStockMoves,$key);
                }
                else{
                    $moves = Fodder_Stock_Move::where('stock_id',$sec->id)->whereBetween( 'created_at', [$from, $to])->get();
                    $list['changes_members'][$key]['changes']=$service->changes($moves,$key);
                }
                $list['changes_members'][$key]['changes']=$service->unique_price_date_for_statistics($list['changes_members'][$key]['changes']);
                $list['changes_members'][$key]['change']     = $service->change_rate($list['changes_members'][$key]['changes']);
                $list['changes_members'][$key]['counts'] = $service->counts($list['changes_members'][$key]['changes'],$key);


            }
            return response()->json([
                'message'  => null,
                'error'    => null,
                'data'     => $list
            ],200);
        }
        else{

            if(is_null($from) || is_null($to))
            {

                $feeo = Fodder_Stock::where('id',$mem_id)->first();
                $moves = Fodder_Stock_Move::where('stock_id',$mem_id)->get();

            }else{

                $mov = Fodder_Stock_Move::select('stock_id')->whereBetween( 'created_at', [$from, $to]);
                $feeo = Fodder_Stock::where('id',$mem_id)->whereIn('id',$mov)->first();
                $moves = Fodder_Stock_Move::where('stock_id',$mem_id)->whereBetween( 'created_at', [$from, $to])->get();
            }



            # check section exist
            if(!$feeo)
            {
                $msg = 'member not found';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }


            if($feeo){
                $list['changes_members']['id']         = $feeo->id;
                $list['changes_members']['name']   = $feeo->Company->name;
                $list['changes_members']['compId']         = $feeo->Company->id;

//                    $list['changes_members']['change']     =(string) $feeo->oldprice();
                $list['changes_members']['categorize']   = $feeo->stockFeed->name;
                if($feeo->Company != null){
                    $list['list_members']['id']         = $feeo->Company->id;
                    $list['list_members']['name']   = $feeo->Company->name;
                }

                $list['changes_members']['changes']=$service->changes($moves,0);

                $list['changes_members']['changes']=$service->unique_price_date_for_statistics($list['changes_members']['changes']);

                $list['changes_members']['change']     = $service->change_rate($list['changes_members']['changes']);
                if($list['changes_members']['changes'] == []){
                    $list['changes_members']['counts']     = count($list['changes_members']['changes']);
                }else{
                    $list['changes_members']['counts']     = count($list['changes_members']['changes'])-1;
                }

            }
            $temp=$list['changes_members'];
            $list['changes_members']=[$temp];

        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }
    public function statisticsFodderlist(Request $request){
        $id   = $request->input("id");
        $i=0;
        $companies= Fodder_Stock::where('sub_id',$id)->orderBy('company_id', 'DESC')->get();

        foreach ($companies as $key => $sec) {
            $list['list_members'][$i]['id'] = $sec->Company->id;
            $list['list_members'][$i]['name'] = $companies[$key]->Company->name;
            if (isset($companies[$key - 1]) && $sec->Company->id != $companies[$key - 1]->Company->id) {
                $list['list_members'][$i]['id'] = $companies[$key - 1]->Company->id;
                $list['list_members'][$i]['name'] = $companies[$key - 1]->Company->name;
                $i++;
            }
        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }

    public function statisticsFoddermembers_android(Request $request,FodderService $service)
    {


        $id   = $request->input("id");
        $from = $request->input("from");
        $to   = $request->input("to");

        $mem_id    = $request->input("mem_id");
        $com_id    = $request->input("com_id");
        $list = [];
        $list['list_members']=[];
        $list['changes_members']=[];
        if(is_null($mem_id) && is_null($com_id))
        {

            if(is_null($from) || is_null($to))
            {
//                $feeds = Fodder_Stock::where('sub_id',$id)->where( 'created_at', '>', Carbon::now()->subDays(30))->latest()->get();
                $mov = Fodder_Stock_Move::select('stock_id')->where( 'created_at', '>', Carbon::now()->subDays(30));
                $feeds = Fodder_Stock::where('sub_id',$id)->whereIn('id',$mov)->get();
            }else{

                $mov = Fodder_Stock_Move::select('stock_id')->whereBetween( 'created_at', [$from, $to]);
                $feeds = Fodder_Stock::where('sub_id',$id)->whereIn('id',$mov)->get();
            }

            $i=0;
            foreach ($feeds as $key => $sec) {
                $list['changes_members'][$key]['id'] = $sec->id;
                $list['changes_members'][$key]['name'] = $sec->Company->name;
                $list['changes_members'][$key]['categorize'] = $sec->stockFeed->name;
                $list['changes_members'][$key]['compId']         = $sec->Company->id;

                $list['list_members'][$i]['id']         = $sec->Company->id;
                $list['list_members'][$i]['name']   = $feeds[$key]->Company->name;
                if(isset($feeds[$key-1]) && $sec->Company->id != $feeds[$key-1]->Company->id){
                    $list['list_members'][$i]['id']         = $feeds[$key-1]->Company->id;
                    $list['list_members'][$i]['name']   = $feeds[$key-1]->Company->name;
                    $i++;
                }

                if (is_null($from) || is_null($to)) {

                    $moves = Fodder_Stock_Move::where('stock_id',$sec->id)->where( 'created_at', '>', Carbon::now()->subDays(30))->get();
                }
                else{
                    $moves = Fodder_Stock_Move::where('stock_id',$sec->id)->whereBetween( 'created_at', [$from, $to])->get();

                }
                $list['changes_members'][$key]['changes']=$service->changes($moves,$key);

                $list['changes_members'][$key]['changes']=$service->unique_price_date_for_statistics($list['changes_members'][$key]['changes']);
                $list['changes_members'][$key]['change']     = $service->change_rate($list['changes_members'][$key]['changes']);
                $list['changes_members'][$key]['counts'] = $service->counts($list['changes_members'][$key]['changes'],$key);

            }

        }
        else if($com_id != null){

            $companies= Fodder_Stock::where('sub_id',$id)->orderBy('company_id', 'DESC')->get();
            $i=0;
            foreach ($companies as $key => $sec) {
                $list['list_members'][$i]['id'] = $sec->Company->id;
                $list['list_members'][$i]['name'] = $companies[$key]->Company->name;
                if (isset($companies[$key - 1]) && $sec->Company->id != $companies[$key - 1]->Company->id) {
                    $list['list_members'][$i]['id'] = $companies[$key - 1]->Company->id;
                    $list['list_members'][$i]['name'] = $companies[$key - 1]->Company->name;
                    $i++;
                }
            }
            $list['changes_members']=[];
            $feeds=Fodder_Stock::where('sub_id',$id)->where('company_id',$com_id)->latest()->get();


            foreach ($feeds as $key => $sec) {

                $list['changes_members'][$key]['id'] = $sec->id;
                $list['changes_members'][$key]['name'] = $sec->Company->name;
                $list['changes_members'][$key]['categorize'] = $sec->stockFeed->name;
                $list['changes_members'][$key]['compId']         = $sec->Company->id;


                if (is_null($from) || is_null($to)) {

                    $moves = Fodder_Stock_Move::where('stock_id',$sec->id)->where( 'created_at', '>', Carbon::now()->subDays(30))->get();
                }
                else{
                    $moves = Fodder_Stock_Move::where('stock_id',$sec->id)->whereBetween( 'created_at', [$from, $to])->get();
                }
                $list['changes_members'][$key]['changes']=$service->changes($moves,$key);

                $list['changes_members'][$key]['changes']=$service->unique_price_date_for_statistics($list['changes_members'][$key]['changes']);
                $list['changes_members'][$key]['change']     = $service->change_rate($list['changes_members'][$key]['changes']);
                $list['changes_members'][$key]['counts'] = $service->counts($list['changes_members'][$key]['changes'],$key);

            }
            return response()->json([
                'message'  => null,
                'error'    => null,
                'data'     => $list
            ],200);
        }
        else{

            if(is_null($from) || is_null($to))
            {

                $feeo = Fodder_Stock::where('id',$mem_id)->first();
                $moves = Fodder_Stock_Move::where('stock_id',$mem_id)->where( 'created_at', '>', Carbon::now()->subDays(30))->get();

            }else{

                $mov = Fodder_Stock_Move::select('stock_id')->whereBetween( 'created_at', [$from, $to]);
                $feeo = Fodder_Stock::where('id',$mem_id)->whereIn('id',$mov)->first();
                $moves = Fodder_Stock_Move::where('stock_id',$mem_id)->whereBetween( 'created_at', [$from, $to])->get();
            }



            # check section exist
            if(!$feeo)
            {
                $msg = 'member not found';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);
            }


            if($feeo){
                $list['changes_members']['id']         = $feeo->id;
                $list['changes_members']['name']   = $feeo->Company->name;
                $list['changes_members']['compId']         = $feeo->Company->id;

//                    $list['changes_members']['change']     =(string) $feeo->oldprice();
                $list['changes_members']['categorize']   = $feeo->stockFeed->name;
                if($feeo->Company != null){
                    $list['list_members']['id']         = $feeo->Company->id;
                    $list['list_members']['name']   = $feeo->Company->name;
                }

                $list['changes_members']['changes']=$service->changes($moves,0);
                $list['changes_members']['changes']=$service->unique_price_date_for_statistics($list['changes_members']['changes']);
                $list['changes_members']['change']     = $service->change_rate($list['changes_members']['changes']);
                if($list['changes_members']['changes'] == []){
                    $list['changes_members']['counts']     = count($list['changes_members']['changes']);
                }else{
                    $list['changes_members']['counts']     = count($list['changes_members']['changes'])-1;
                }

            }
            $temp=$list['changes_members'];
            $list['changes_members']=[$temp];

        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }


}
