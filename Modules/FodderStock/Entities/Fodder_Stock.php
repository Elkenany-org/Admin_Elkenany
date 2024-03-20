<?php

namespace Modules\FodderStock\Entities;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Modules\FodderStock\Entities\Fodder_Stock_Move;
use Date;
class Fodder_Stock extends Model
{
    protected $table = 'fodder_stocks';

    public function Section()
    {
        return $this->belongsTo('Modules\FodderStock\Entities\Stock_Fodder_Section', 'section_id', 'id');
    }

    public function subSection()
    {
        return $this->belongsTo('Modules\FodderStock\Entities\Stock_Fodder_Sub', 'sub_id', 'id');
    }

    public function StockFeed()
    {
        return $this->belongsTo('Modules\FodderStock\Entities\Stock_Feeds', 'fodder_id', 'id');
    }

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }

    public function FodderStockMoves()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Fodder_Stock_Move', 'stock_id', 'id');
    }

    public function LastMovement()
    {
        return Fodder_Stock_Move::where('stock_id',$this->id)->latest()->first();
    } 

    public function movements()
    {
        $mem = Fodder_Stock_Move::where('stock_id',$this->id)->take(7)->latest();

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

    public function oldprice(Request $request)
    {
        $from  = $request->input("from");
        $to    = $request->input("to");
        $oldprice =0.00;

        if(is_null($from) || is_null($to))
        {
            $mov = Fodder_Stock_Move::where( 'created_at', '>', Carbon::now()->subDays(30))->where('stock_id',$this->id)->first();

            if($mov) {
                $oldprice = (float)$mov->price;
            }

            else{
                $move = Fodder_Stock_Move::where( 'created_at', '>', Carbon::now()->subDays(45))->where('stock_id',$this->id)->first();
                if($move){
                    $oldprice = (float)$move->price;
                }
            }

            $movnew = Fodder_Stock_Move::where('stock_id',$this->id)->latest()->first();
            if($movnew){
                $newprice = (float) $movnew->price;
            }


        }else{
            $mov = Fodder_Stock_Move::where( 'created_at', '>=', $from)->where('stock_id',$this->id)->first();
            if($mov)
            {
            $oldprice = (float) $mov->price;
            }else{
                $movo = Fodder_Stock_Move::where( 'created_at', '>', Carbon::now()->subDays(30))->where('stock_id',$this->id)->first();
                $oldprice = (float) $movo->price;
            }

            $movnew = Fodder_Stock_Move::where( 'created_at', '<=', $to)->where('stock_id',$this->id)->latest()->first();
            if($mov)
            {
            $newprice = (float) $movnew->price;
            }else{
                $movnews = Fodder_Stock_Move::where('stock_id',$this->id)->latest()->first();
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
    public function days(Request $request)
    {
        $from  = $request->input("from");
        $to    = $request->input("to");
        if(is_null($from) || is_null($to))
        {
            $mov = Fodder_Stock_Move::where('stock_id',$this->id)->latest()->skip(1)->take(1)
            ->first();
         
            if($mov){
                $oldprice = (float) $mov->price;
            }else{
                $mov = Fodder_Stock_Move::where('stock_id',$this->id)->latest()->first();
                $oldprice = (float) $mov->price;
            }

          
                        
             
            $movnew = Fodder_Stock_Move::where('stock_id',$this->id)->latest()->first();
          
            $newprice = (float) $movnew->price;
                        
        
        }else{
            $mov = Fodder_Stock_Move::where('created_at', '<=', $to)->where('stock_id',$this->id)->latest()->skip(1)->take(1)->first();
            if($mov)
            {
            $oldprice = (float) $mov->price;
            }else{
                $mov = Fodder_Stock_Move::where('stock_id',$this->id)->latest()->skip(1)->take(1)
            ->first();
         
            $oldprice = (float) $mov->price;
            }           
          
            $movnew = Fodder_Stock_Move::where( 'created_at', '<=', $to)->where('stock_id',$this->id)->latest()->first();
            if($mov)
            {
            $newprice = (float) $movnew->price;
            }else{
                $movnews = Fodder_Stock_Move::where('stock_id',$this->id)->latest()->first();
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

    public function week(Request $request)
    {
        $from  = $request->input("from");
        $to    = $request->input("to");
        if(is_null($from) || is_null($to))
        {
            $mov = Fodder_Stock_Move::where( 'created_at', '>', Carbon::now()->subDays(7))->where('stock_id',$this->id)->first();
         
            $oldprice = (float) $mov->price;
                        
             
            $movnew = Fodder_Stock_Move::where('stock_id',$this->id)->latest()->first();
          
            $newprice = (float) $movnew->price;
                        
        
        }else{
            $mov = Fodder_Stock_Move::where( 'created_at', '>=', $from)->where('stock_id',$this->id)->first();
            if($mov)
            {
            $oldprice = (float) $mov->price;
            }else{
                
                $movo = Fodder_Stock_Move::where( 'created_at', '>', Carbon::now()->subDays(7))->where('stock_id',$this->id)->first();
                if($movo)
                {
                $oldprice = (float) $movo->price;
                }else{
                    $move = Fodder_Stock_Move::where( 'created_at', '>', Carbon::now()->subDays(30))->where('stock_id',$this->id)->first();
                    $oldprice = (float) $move->price;
                }
               
            }           
          
            $movnew = Fodder_Stock_Move::where( 'created_at', '<=', $to)->where('stock_id',$this->id)->latest()->first();
            if($mov)
            {
            $newprice = (float) $movnew->price;
            }else{
                $movnews = Fodder_Stock_Move::where('stock_id',$this->id)->latest()->first();
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
    public function counts(Request $request)
    {
        $from  = $request->input("from");
        $to    = $request->input("to");
        if(is_null($from) || is_null($to))
        {
        $count = Fodder_Stock_Move::where('stock_id',$this->id)->where( 'created_at', '>', Carbon::now()->subDays(30))->count();
        }else{
            $count = Fodder_Stock_Move::where('stock_id',$this->id)->whereBetween('created_at', [$from, $to])->count();
        }
        return $count;
    }



    
}
