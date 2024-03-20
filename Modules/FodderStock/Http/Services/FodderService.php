<?php

namespace Modules\FodderStock\Http\Services;

use App\Configuration;
use App\Noty;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Modules\FodderStock\Entities\Fodder_Stock_Move;
use Modules\FodderStock\Entities\Stock_Feeds;
use Modules\FodderStock\Transformers\TablesFooderResource;
use Modules\Guide\Entities\Company;


class FodderService
{
    use ApiResponse;

    /**
     * @param $ads
     * @param $date
     * @return array
     */
    public function FodderTableProccess($request,$ads,$date,$fod = null)
    {
        $id = $request->input("id");
        $fod_id = $request->input("fod_id");
        $comp_id = $request->input("comp_id");




        $memberssort = Fodder_Stock_Move::with('Section','StockFeed','Company','FodderStock')->whereIn('company_id',$ads)->where('sub_id',$id);
        $members = Fodder_Stock_Move::with('Section','StockFeed','Company','FodderStock')->whereNotIn('company_id',$ads)->where('sub_id',$id);

        if(isset($fod) && $fod != ""){
            $memberssort->where('fodder_id',$fod->id);
            $members->where('fodder_id',$fod->id);
        }


        if($comp_id){
            $memberssort->where('company_id',$comp_id);
            $members->where('company_id',$comp_id);

        }
        if($date){
            $memberssort->whereDate('created_at',$date);
            $members->whereDate('created_at',$date);
        }
        $memberssort = $memberssort->latest()->get()->unique('stock_id');
        $members = $members->latest()->get()->unique('stock_id');

        $mems = TablesFooderResource::collection($memberssort);
        $mem = TablesFooderResource::collection($members);

        $list = [];
        foreach ($mems as $m){
            array_push($list,$m);
        }
        foreach ($mem as $m){
            array_push($list,$m);
        }
        return $list;
    }

    public function RankingMembers($request,$id,$ads,$date,$fod = null)
    {

        $company  = $request->input("company_id");


        $movessort = Fodder_Stock_Move::with('StockFeed','Company','FodderStock')->where('sub_id',$id)->whereIn('company_id',$ads);
        if(isset($fod) && $fod != ""){
            $movessort->where('fodder_id',$fod->id);
        }
        if($date){
            $movessort->whereDate('created_at',$date);
        }
        if($company){
            $movessort->where('company_id',$company);
        }
        $movessort = $movessort->latest()->distinct('stock_id','company_id')->get();

        return $movessort;
    }

    public function Members($request,$id,$ads,$date,$fod = null)
    {

        $company  = $request->input("company_id");

        $moves = Fodder_Stock_Move::with('StockFeed','Company','FodderStock')->where('sub_id',$id)->whereNotIn('company_id',$ads);
        if(isset($fod) && $fod != ""){
            $moves->where('fodder_id',$fod->id);
        }
        if($date){
            $moves->whereDate('created_at',$date);
        }
        if($company){
            $moves->where('company_id',$company);
        }
        $moves = $moves->latest()->distinct('stock_id','company_id')->paginate(); // ->unique('stock_id')

        return $moves;
    }

    public function unique_price_date_for_statistics($arr){
        $arr1=array();
        $arr2=array();
        $temp1=$arr;
        if(isset($arr[0])){
            array_push($arr1,(object)['date'=>$temp1[0]['date'],'price'=>$temp1[0]['price']]);
            foreach ($arr as $k =>$value){
                if(isset($arr[$k+1])) {
                    if ($temp1[$k]['date'] == $temp1[$k + 1]['date'])
                    {
                        array_pop($arr1);
                        array_push($arr1,(object)['date'=>$temp1[$k + 1]['date'],'price'=>$temp1[$k + 1]['price']]);
                    }
                    else{
                        array_push($arr1,(object)['date'=>$temp1[$k+1]['date'],'price'=>$temp1[$k+1]['price']]);
                    }
                }
            }

            array_push($arr2, (object)['date' => $arr1[0]->date, 'price' => $arr1[0]->price]);
            foreach ($arr1 as $k =>$value) {
                if(isset($arr1[$k + 1])){
                    if ($value->price != $arr1[$k + 1]->price && $arr1[$k + 1]->price !=0) {
                        array_push($arr2, (object)['date' => $arr1[$k + 1]->date, 'price' => $arr1[$k + 1]->price]);
                    }
                }
                else{
                    array_pop($arr2);
                    array_push($arr2, (object)['date' => $arr1[$k ]->date, 'price' => $arr1[$k ]->price]);
                }
            }
        }
        if($arr2[0]->price == 0){
            array_shift($arr2);
        }
        return $arr2;
    }
    public function change_rate($arr2){

        if($arr2 != [] && $arr2[array_key_last($arr2)]->price != 0){
            $changeRate=(( $arr2[array_key_last($arr2)]->price- $arr2[0]->price)/$arr2[array_key_last($arr2)]->price)*100;
        }
        else{
            $changeRate=0;
        }
        return number_format($changeRate,2);
    }

    public function changes($arr,$key){
        foreach ($arr as $ke => $cha) {
                $list['changes_members'][$key]['changes'][$ke]['date'] = date('Y-m-d', strtotime($cha->created_at));
                $list['changes_members'][$key]['changes'][$ke]['price'] = (int)$cha->price;
        }
        return  $list['changes_members'][$key]['changes'];
    }
    public function counts($arr,$key){
        if($arr == []){
            $list['changes_members'][$key]['counts']     = count($arr);
        }else{
            $list['changes_members'][$key]['counts']     = count($arr)-1;
        }
        return $list['changes_members'][$key]['counts'];
    }
}