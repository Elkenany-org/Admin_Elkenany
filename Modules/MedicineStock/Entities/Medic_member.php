<?php

namespace Modules\MedicineStock\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\MedicineStock\Entities\Medic_move;
use Date;
use Illuminate\Support\Facades\Input;

class Medic_member extends Model
{
    protected $table = 'medicine_members';

    public function Section()
    {
        return $this->belongsTo('Modules\MedicineStock\Entities\Medic_Section', 'section_id', 'id');
    }

    public function MedicStock()
    {
        return $this->belongsTo('Modules\MedicineStock\Entities\Medic_Stock', 'sub_id', 'id');
    }

    public function MedicSubs()
    {
        return $this->belongsTo('Modules\MedicineStock\Entities\Medic_Subs', 'active_id', 'id');
    }

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }

    public function Medicmoves()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Medic_move', 'member_id', 'id');
    }

    public function Comname()
    {
        return $this->belongsTo('Modules\MedicineStock\Entities\Com_name', 'name_id', 'id');
    }

    public function LastMovement()
    {
        return Medic_move::where('sub_id',$this->id)->latest()->first();
    } 

    public function movements()
    {
        $mem = Medic_move::where( 'created_at', '>', Carbon::now()->subDays(30))->where('sub_id',$this->id)
        ->take(7)->latest();

        $changes = $mem->pluck('change')->toArray();
        $dates   = [];
        foreach($mem->get() as $key => $m)
        {
            $push[$key] = Date::parse($m->created_at)->format('m/d');
            $dates      = $push;
        }
        return [
            'changes' => $changes,
            'dates' => $dates,
        ];

    }

    public function oldprice()
    {
        $from  = Input::get("from");
        $to    = Input::get("to");
        if(is_null($from) || is_null($to))
        {
            $mov = Medic_move::where( 'created_at', '>', Carbon::now()->subDays(30))->where('sub_id',$this->id)->first();
         
            $oldprice = (float) $mov->price;
                        
             
            $movnew = Medic_move::where('sub_id',$this->id)->latest()->first();
          
            $newprice = (float) $movnew->price;
                        
        
        }else{
            $mov = Medic_move::where( 'created_at', '>=', $from)->where('sub_id',$this->id)->first();
            if($mov)
            {
            $oldprice = (float) $mov->price;
            }else{
                $movo = Medic_move::where( 'created_at', '>', Carbon::now()->subDays(30))->where('sub_id',$this->id)->first();
                $oldprice = (float) $movo->price;
            }           
          
            $movnew = Medic_move::where( 'created_at', '<=', $to)->where('sub_id',$this->id)->latest()->first();
            if($mov)
            {
            $newprice = (float) $movnew->price;
            }else{
                $movnews = Medic_move::where('sub_id',$this->id)->latest()->first();
                $newprice = (float) $movnews->price;
            }   
        }
        $change = $newprice - $oldprice ;
        if($change == 0) {
            $result1 = 0;
        }else{
            if($newprice > 0) {
                $result = (float) ($change / $newprice) * 100;
           
               $result1 = number_format($result, 2);
           }else{
               
               $result1 = 0;
           }
       
        }
        
        return $result1;
    }

    # days
    public function days()
    {
        $from  = Input::get("from");
        $to    = Input::get("to");
        if(is_null($from) || is_null($to))
        {
            $mov = Medic_move::where('sub_id',$this->id)->latest()->skip(1)->take(1)
            ->first();
         
            if($mov){
                $oldprice = (float) $mov->price;
            }else{
                $mov = Medic_move::where('sub_id',$this->id)->latest()->first();
                $oldprice = (float) $mov->price;
            }

          
                        
             
            $movnew = Medic_move::where('sub_id',$this->id)->latest()->first();
          
            $newprice = (float) $movnew->price;
                        
        
        }else{
            $mov = Medic_move::where('created_at', '<=', $to)->where('sub_id',$this->id)->latest()->skip(1)->take(1)->first();
            if($mov)
            {
            $oldprice = (float) $mov->price;
            }else{
                $mov = Medic_move::where('sub_id',$this->id)->latest()->skip(1)->take(1)
            ->first();
         
            $oldprice = (float) $mov->price;
            }           
          
            $movnew = Medic_move::where( 'created_at', '<=', $to)->where('sub_id',$this->id)->latest()->first();
            if($mov)
            {
            $newprice = (float) $movnew->price;
            }else{
                $movnews = Medic_move::where('sub_id',$this->id)->latest()->first();
                $newprice = (float) $movnews->price;
            }   
        }
        $change = $newprice - $oldprice ;
        if($change == 0) {
            $result1 = 0;
        }else{
            if($newprice > 0) {
                $result = (float) ($change / $newprice) * 100;
           
               $result1 = number_format($result, 2);
           }else{
               
               $result1 = 0;
           }
        }
        
        return $result1;
    }

    # week

    public function week()
    {
        $from  = Input::get("from");
        $to    = Input::get("to");
        if(is_null($from) || is_null($to))
        {
            $mov = Medic_move::where( 'created_at', '>', Carbon::now()->subDays(7))->where('sub_id',$this->id)->first();
         
            $oldprice = (float) $mov->price;
                        
             
            $movnew = Medic_move::where('sub_id',$this->id)->latest()->first();
          
            $newprice = (float) $movnew->price;
                        
        
        }else{
            $mov = Medic_move::where( 'created_at', '>=', $from)->where('sub_id',$this->id)->first();
            if($mov)
            {
            $oldprice = (float) $mov->price;
            }else{
                
                $movo = Medic_move::where( 'created_at', '>', Carbon::now()->subDays(7))->where('sub_id',$this->id)->first();
                if($movo)
                {
                $oldprice = (float) $movo->price;
                }else{
                    $move = Medic_move::where( 'created_at', '>', Carbon::now()->subDays(30))->where('sub_id',$this->id)->first();
                    $oldprice = (float) $move->price;
                }
               
            }           
          
            $movnew = Medic_move::where( 'created_at', '<=', $to)->where('sub_id',$this->id)->latest()->first();
            if($mov)
            {
            $newprice = (float) $movnew->price;
            }else{
                $movnews = Medic_move::where('sub_id',$this->id)->latest()->first();
                $newprice = (float) $movnews->price;
            }   
        }
        $change = $newprice - $oldprice ;
        if($change == 0) {
            $result1 = 0;
        }else{
            if($newprice > 0) {
                $result = (float) ($change / $newprice) * 100;
           
               $result1 = number_format($result, 2);
           }else{
               
               $result1 = 0;
           }
        }
        return $result1;
    }


    # show count
    public function counts()
    {
        $from  = Input::get("from");
        $to    = Input::get("to");
        if(is_null($from) || is_null($to))
        {
        $count = Medic_move::where('sub_id',$this->id)->where( 'created_at', '>', Carbon::now()->subDays(30))->count();
        }else{
            $count = Medic_move::where('sub_id',$this->id)->whereBetween('created_at', [$from, $to])->count();
        }
        return $count;
    }



    

    
}
