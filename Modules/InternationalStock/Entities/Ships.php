<?php

namespace Modules\InternationalStock\Entities;

use Illuminate\Database\Eloquent\Model;
//use Modules\InternationalStock\Entities\Ships;
class Ships extends Model
{
    protected $table = 'ships';

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }

    public function ShipsProduct()
    {
        return $this->belongsTo('Modules\InternationalStock\Entities\Ships_Product', 'product_id', 'id');
    }

    public function Ports()
    {
        return $this->belongsTo('Modules\InternationalStock\Entities\Ports', 'port_id', 'id');
    }

    public function nums()
    {
 
        $old = Ships::where('product_id',$this->product_id)->where('company_id',$this->company_id)->where('country',$this->country)->where('id', '<',$this->id)->latest()->first();

        if(!$old){
            $nums = 0;
        }else{
            if($old->id != $this->id){

                $change = $this->load - $old->load;

                $result = (float) ($change / $old->load) * 100;

                $nums = number_format($result, 2);
            }
        }
        return $nums;
    }





}
