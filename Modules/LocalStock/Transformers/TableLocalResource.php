<?php

namespace Modules\LocalStock\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Date;
use Illuminate\Support\Facades\URL;

class TableLocalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $arrayData = [];
        // if company
        if($this->Company != null){
            $arrayData['name']               = $this->Company->name;
            $arrayData['mem_id']               = $this->Company->id;
            $arrayData['kind']               = 'company';

        }
        // if product
        if($this->LocalStockproducts != null){
            $arrayData['name']              = $this->LocalStockproducts->name;
            $arrayData['mem_id']               = $this->LocalStockproducts->id;
            $arrayData['kind']               = 'product';
        }

        // last movement
        foreach ($this->LastMovement()->LocalStockDetials as $k => $value)
        {
            // price
            if($value->LocalStockColumns->type == 'price' ){
                $arrayData["price"]         = $value->value;
            }
            // change
            if($value->LocalStockColumns->type == 'change' ){
                $arrayData["change"]         = (string) round($value->value, 2);
                $arrayData["change_date"]         = Date::parse($this->created_at)->format('H:i / Y-m-d');
            }

            // if  null
            if($value->LocalStockColumns->type == null ){
                $arrayData["new_columns"][]                 = $value->value;
            }

            // state
            if($value->LocalStockColumns->type == 'state' ){
                if($value->value === 'up' ){
                    $arrayData["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                }
                if($value->value === 'down' ){
                    $arrayData["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                }
                if($value->value === 'equal' ){
                    $arrayData["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                }
            }
        }

        $arrayData["type"] = 1;

        return $arrayData;
  }
}
