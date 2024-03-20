<?php

namespace Modules\LocalStock\Entities;
use Modules\LocalStock\Entities\Local_Stock_Movement;
use Modules\LocalStock\Entities\Member_Count;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;

use Date;



class Local_Stock_Member extends Model
{
    protected $table = 'local_stock_members';

    public function Section()
    {
        return $this->belongsTo('Modules\LocalStock\Entities\Local_Stock_Sub', 'section_id', 'id');
    }

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }

    public function LocalStockproducts()
    {
        return $this->belongsTo('Modules\LocalStock\Entities\Local_Stock_product', 'product_id', 'id');
    }


    public function LocalStockMovement()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Movement', 'member_id', 'id');
    }

    public function MemberCounts()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Member_Count', 'member_id', 'id');
    }


    public function LastMovement()
    {
        return Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$this->id)->latest()->first();
    } 

    public function movements()
    {
        $mem = Member_Count::where('member_id',$this->id)->take(7)->latest();
        if(isset($_GET['date'])){
            $dateBefore = date('Y-m-d', strtotime('-7 days', strtotime($_GET['date'])));
            $date = date('Y-m-d',strtotime($_GET['date']));
            $mem->whereDate( 'created_at','<=', $date);
            $mem->whereDate( 'created_at','>', $dateBefore);
        }else{
            $mem->where( 'created_at', '>', Carbon::now()->subDays(30));
        }


        $changes = $mem->pluck('change')->toArray();
        $mem = $mem->get();

        $dates   = [];
        foreach($mem as $key => $m)
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
            $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->whereDate( 'created_at', '>', Carbon::now()->subDays(30))->where('member_id',$this->id)->first();
            if(!empty($mov->LocalStockDetials)){
                foreach($mov->LocalStockDetials as $Mco){
                    if($Mco->column_type == 'price'){
                        $oldprice = (float) $Mco->value;
                        
                    }
                }
            }else{
                
                $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$this->id)->latest()->first();
                if(!empty($mov->LocalStockDetials)){
                    foreach($mov->LocalStockDetials as $Mco){
                        if($Mco->column_type == 'price'){
                            $oldprice = (float) $Mco->value;
                            
                        }
                    }
                }

            }

            $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$this->id)->latest()->first();
            if(!empty($movnew->LocalStockDetials)){
                foreach($movnew->LocalStockDetials as $Mcow){
                    if($Mcow->column_type == 'price'){
                        $newprice = (float) $Mcow->value;
                        
                    }
                }
            }
        }else{
            $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->whereDate( 'created_at', '>=', $from)->where('member_id',$this->id)->first();
            if(!empty($mov->LocalStockDetials)){
                foreach($mov->LocalStockDetials as $Mco){
                    if($Mco->column_type == 'price'){
                        $oldprice = (float) $Mco->value;
                        
                    }
                }
            }else{
                $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->whereDate( 'created_at', '>', Carbon::now()->subDays(30))->where('member_id',$this->id)->first();
                if(!empty($mov->LocalStockDetials)){
                    foreach($mov->LocalStockDetials as $Mco){
                        if($Mco->column_type == 'price'){
                            $oldprice = (float) $Mco->value;
                            
                        }
                    }
                } 
            }

            $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->whereDate( 'created_at', '<=', $to)->where('member_id',$this->id)->latest()->first();
            if(!empty($movnew->LocalStockDetials)){
                foreach($movnew->LocalStockDetials as $Mcow){
                    if($Mcow->column_type == 'price'){
                        $newprice = (float) $Mcow->value;
                        
                    }
                }
            }else{
                    
                $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$this->id)->latest()->first();
                if(!empty($movnew->LocalStockDetials)){
                    foreach($movnew->LocalStockDetials as $Mcow){
                        if($Mcow->column_type == 'price'){
                            $newprice = (float) $Mcow->value;
                            
                        }
                    }
                }
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
            $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$this->id)->latest()->skip(1)->take(1)
            ->first();
            if(!empty($mov->LocalStockDetials)){
                foreach($mov->LocalStockDetials as $Mco){
                    if($Mco->column_type == 'price'){
                        $oldprice = (float) $Mco->value;
                        
                    }
                }
            }else{
                
                $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$this->id)->first();
                if(!empty($mov->LocalStockDetials)){
                    foreach($mov->LocalStockDetials as $Mco){
                        if($Mco->column_type == 'price'){
                            $oldprice = (float) $Mco->value;
                            
                        }
                    }
                }
            }

            $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$this->id)->latest()->first();
            if(!empty($movnew->LocalStockDetials)){
                foreach($movnew->LocalStockDetials as $Mcow){
                    if($Mcow->column_type == 'price'){
                        $newprice = (float) $Mcow->value;
                        
                    }
                }
            }
        }else{
            $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '<=', $to)->where('member_id',$this->id)->latest()->skip(1)->take(1)->first();
            if(!empty($mov->LocalStockDetials)){
                foreach($mov->LocalStockDetials as $Mco){
                    if($Mco->column_type == 'price'){
                        $oldprice = (float) $Mco->value;
                        
                    }
                }
            }else{
                $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$this->id)->latest()->skip(1)->take(1)
                ->first();
                if(!empty($mov->LocalStockDetials)){
                    foreach($mov->LocalStockDetials as $Mco){
                        if($Mco->column_type == 'price'){
                            $oldprice = (float) $Mco->value;
                            
                        }
                    }
                }
            }

            $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '<=', $to)->where('member_id',$this->id)->latest()->first();
            if(!empty($movnew->LocalStockDetials)){
                foreach($movnew->LocalStockDetials as $Mcow){
                    if($Mcow->column_type == 'price'){
                        $newprice = (float) $Mcow->value;
                        
                    }
                }
            }else{
                $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$this->id)->latest()->first();
                if(!empty($movnew->LocalStockDetials)){
                    foreach($movnew->LocalStockDetials as $Mcow){
                        if($Mcow->column_type == 'price'){
                            $newprice = (float) $Mcow->value;
                            
                        }
                    }
                }
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
            $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '>', Carbon::now()->subDays(7))->where('member_id',$this->id)->first();
            if(!empty($mov->LocalStockDetials)){
                foreach($mov->LocalStockDetials as $Mco){
                    if($Mco->column_type == 'price'){
                        $oldprice = (float) $Mco->value;
                        
                    }
                }
            }else{
                $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$this->id)->latest()->first();
                if(!empty($mov->LocalStockDetials)){
                    foreach($mov->LocalStockDetials as $Mco){
                        if($Mco->column_type == 'price'){
                            $oldprice = (float) $Mco->value;
                            
                        }
                    }
                }
            }

            $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$this->id)->latest()->first();
            if(!empty($movnew->LocalStockDetials)){
                foreach($movnew->LocalStockDetials as $Mcow){
                    if($Mcow->column_type == 'price'){
                        $newprice = (float) $Mcow->value;
                        
                    }
                }
            }
        }else{
            $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '>=', $from)->where('member_id',$this->id)->first();
            if(!empty($mov->LocalStockDetials)){
                foreach($mov->LocalStockDetials as $Mco){
                    if($Mco->column_type == 'price'){
                        $oldprice = (float) $Mco->value;
                        
                    }
                }
            }else{
                $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '>', Carbon::now()->subDays(7))->where('member_id',$this->id)->first();
                if(!empty($mov->LocalStockDetials)){
                    foreach($mov->LocalStockDetials as $Mco){
                        if($Mco->column_type == 'price'){
                            $oldprice = (float) $Mco->value;
                            
                        }
                    }
                }else{
                    $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '>', Carbon::now()->subDays(30))->where('member_id',$this->id)->first();
                    if(!empty($mov->LocalStockDetials)){
                        foreach($mov->LocalStockDetials as $Mco){
                            if($Mco->column_type == 'price'){
                                $oldprice = (float) $Mco->value;
                                
                            }
                        }
                    } 
                }
            }

            $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '<=', $to)->where('member_id',$this->id)->latest()->first();
            if(!empty($movnew->LocalStockDetials)){
                foreach($movnew->LocalStockDetials as $Mcow){
                    if($Mcow->column_type == 'price'){
                        $newprice = (float) $Mcow->value;
                        
                    }
                }
            }else{
                    
                $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$this->id)->latest()->first();
                if(!empty($movnew->LocalStockDetials)){
                    foreach($movnew->LocalStockDetials as $Mcow){
                        if($Mcow->column_type == 'price'){
                            $newprice = (float) $Mcow->value;
                            
                        }
                    }
                }
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
        $count = Member_Count::where('member_id',$this->id)->where( 'created_at', '>', Carbon::now()->subDays(30))->count();
        }else{
            $count = Member_Count::where('member_id',$this->id)->whereBetween('created_at', [$from, $to])->count();
        }
        return $count;
    }

    public function LocalStockDetials()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Detials', 'member_id', 'id');
    }
    
    
}
