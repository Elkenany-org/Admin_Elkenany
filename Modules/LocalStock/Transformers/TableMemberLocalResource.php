<?php

namespace Modules\LocalStock\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Date;

class TableMemberLocalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public function toArray($request)
    {
//       return $this->LocalStockDetials;

        $arrayData = [];

        if($request->hasHeader('android')){
            // columns initialize
            $arrayData["name"]   = "";
            $arrayData["price"]   = "";
            $arrayData["change"]   = "";
            $arrayData["change_date"]   = "";
            $arrayData["charging_system"]   = "";
            $arrayData["categorize_name"]   = "";
            $arrayData["weight"]   = "";
            $arrayData["price_status"]   = "";
            $arrayData["age"]   = "";
            $arrayData["product_type"]   = "";
            $arrayData["chick_type"]   = "";
            $arrayData["weight_container"]   = "";
            $arrayData["statistics"]   = "";
            $arrayData["comp_id"]   = "";
            $arrayData["mem_id"]   = "";
            $arrayData["kind"]   = "";
            $arrayData["type"]   = "";}

        if($request->hasHeader('device')){
            $arrayData['image'] = '';
        }
        if($this->LocalStockMember->Company != null){
            $arrayData['name']               = $this->LocalStockMember->Company->name;
            $arrayData['comp_id']               = $this->LocalStockMember->Company->id;
            $arrayData["mem_id"]   = "";
            $arrayData['kind']               = 'company';

            if($request->hasHeader('device')){
                $arrayData['image'] = $this->LocalStockMember->Company->image_url;
            }
        }
        // if product
        if($this->LocalStockMember->LocalStockproducts != null){
            $arrayData['name']              = $this->LocalStockMember->LocalStockproducts->name;
            $arrayData['mem_id']               = $this->LocalStockMember->LocalStockproducts->id;
            $arrayData["comp_id"]   = "";
            $arrayData['kind']               = 'product';
        }

        // last movement
        foreach ($this->LocalStockDetials as $koo => $value)
        {
//            dump($value);
            // price
            if($value->LocalStockColumns->type == 'price' ){
                $arrayData["price"]         = $value->value;
            }
            // change
            if($value->LocalStockColumns->type == 'change' ){
                $arrayData["change"]         = (string) round($value->value, 2);
                if($request->hasHeader('android')){
                    $arrayData["change_date"]         = Date::parse($this->created_at)->format('Y-m-d');
                }else{
                    $arrayData["change_date"]         = Date::parse($this->created_at)->format('H:i / Y-m-d');
                }
            }
            // if  null
            if($value->LocalStockColumns->type == null && $value->LocalStockColumns->name == "الوزن"){
                $arrayData["weight"]                 = $value->value;
            }
            elseif($value->LocalStockColumns->type == null && $value->LocalStockColumns->name == "إسم الصنف"){
                $arrayData["categorize_name"]                 = $value->value;
            }
            elseif($value->LocalStockColumns->type == null && $value->LocalStockColumns->name == "نظام الشحن والتعبئة"){
                $arrayData["charging_system"]               = $value->value;
            }
            elseif($value->LocalStockColumns->type == null && $value->LocalStockColumns->name == "العمر"){
                $arrayData["age"]               = $value->value;
            }
            elseif($value->LocalStockColumns->type == null && $value->LocalStockColumns->name == "النوع"){
                $arrayData["product_type"]               = $value->value;
            }
            elseif($value->LocalStockColumns->type == null && $value->LocalStockColumns->name == "نوع الكتكوت"){
                $arrayData["chick_type"]               = $value->value;
            }

            elseif($value->LocalStockColumns->type == null && $value->LocalStockColumns->name == "وزن العبوة"){
                $arrayData["weight_container"]               = $value->value;
            }
            elseif($value->LocalStockColumns->type == null && $value->LocalStockColumns->name == "حالة السعر"){
                $arrayData["price_status"]               = $value->value;
            }
//            elseif($value->LocalStockColumns->type == null){
//                $arrayData["new_columns"][]                 = $value->value;
//            }

            // state
            if($value->LocalStockColumns->type == 'state' ){
                if($value->value === 'up' ){
                    $arrayData["statistics"]        = URL::to('https://admin.elkenany.com/uploads/full_images/arrows3-01.png');
                }
                if($value->value === 'down' ){
                    $arrayData["statistics"]       = URL::to('https://admin.elkenany.com/uploads/full_images/arrows3-02.png');
                }
                if($value->value === 'equal' ){
                    $arrayData["statistics"]      = URL::to('https://admin.elkenany.com/uploads/full_images/arrows3-03.png');
                }
            }

        }


        $arrayData["type"]        = $this->type;

        return $arrayData;
    }

//    public function toArray($request)
//    {
////       return $this->LocalStockDetials;
//
//        $arrayData = [];
//        if($this->LocalStockMember->Company != null){
//            $arrayData['name']               = $this->LocalStockMember->Company->name;
//            $arrayData['mem_id']               = $this->LocalStockMember->Company->id;
//            $arrayData['kind']               = 'company';
//        }
//        // if product
//        if($this->LocalStockMember->LocalStockproducts != null){
//            $arrayData['name']              = $this->LocalStockMember->LocalStockproducts->name;
//            $arrayData['mem_id']               = $this->LocalStockMember->LocalStockproducts->id;
//            $arrayData['kind']               = 'product';
//        }
//
//        // last movement
//        foreach ($this->LocalStockDetials as $koo => $value)
//        {
//            // price
//            if($value->LocalStockColumns->type == 'price' ){
//                $arrayData["price"]         = $value->value;
//            }
//            // change
//            if($value->LocalStockColumns->type == 'change' ){
//                $arrayData["change"]         = (string) round($value->value, 2);
//                $arrayData["change_date"]         = Date::parse($this->created_at)->format('H:i / Y-m-d');
//            }
//            // if  null
//            if($value->LocalStockColumns->type == null ){
//                $arrayData["new_columns"][]                 = $value->value;
//            }
//
//
//            // state
//            if($value->LocalStockColumns->type == 'state' ){
//                if($value->value === 'up' ){
//                    $arrayData["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
//                }
//                if($value->value === 'down' ){
//                    $arrayData["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
//                }
//                if($value->value === 'equal' ){
//                    $arrayData["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
//                }
//            }
//        }
//
//
//        $arrayData["type"]        = $this->type;
//
//        return $arrayData;
//    }
}
