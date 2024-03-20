<?php

namespace Modules\LocalStock\Entities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Modules\LocalStock\Entities\Local_Stock_Member;
use Modules\LocalStock\Entities\Local_Stock_Movement;
use Modules\LocalStock\Entities\Local_Stock_Count;
use Illuminate\Database\Eloquent\Model;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;

class Local_Stock_Sub extends Model
{
    protected $table = 'local_stock_subsections';

    protected $hidden = ['pivot'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return URL::to('uploads/sections/sub/').'/'.$this->image;
    }

    public function Section()
    {
        return $this->belongsTo('Modules\LocalStock\Entities\Local_Stock_Sections', 'section_id', 'id');
    }

    
    public function logooos()
    {

        $page = System_Ads_Pages::where('sub_id',$this->id)->where('type','localstock')->pluck('ads_id');

        return  System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
    } 

    public function sections(){
        return $this->belongsToMany('Modules\LocalStock\Entities\Local_Stock_Sections','subs_all','sub_id','section_id');
    }

    public function LocalStockColumns()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Columns', 'section_id', 'id');
    }

    public function LocalStockCounts()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Count', 'section_id', 'id');
    }

    public function LocalStockMembers()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Member', 'section_id', 'id');
    }

    public function LocalStockMovements()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Movement', 'section_id', 'id');
    }

    public function LocalStockDetials()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Detials', 'section_id', 'id');
    }
    

    # show statistic
    public function laust(Request $request)
    {
        $members = Local_Stock_Member::where('section_id',$this->id)->get();
        $array = [];
        $from  = $request->input("from");
        $to    = $request->input("to");
        if(is_null($from) || is_null($to))
        {
            foreach($members as $mem){
                $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '>', Carbon::now()->subDays(30))->where('member_id',$mem->id)->first();
                if(!empty($mov->LocalStockDetials)){
                    foreach($mov->LocalStockDetials as $Mco){
                        if($Mco->column_type == 'price'){
                            $oldprice = (float) $Mco->value;
                            
                        }
                    }
                }else{
                    $oldprice = 0;
                }

                $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$mem->id)->latest()->first();
                if(!empty($movnew->LocalStockDetials)){
                    foreach($movnew->LocalStockDetials as $Mcow){
                        if($Mcow->column_type == 'price'){
                            $newprice = (float) $Mcow->value;
                            
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
                $array[] = $result1;
            }
        }else{
            foreach($members as $mem){
                $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '>=', $from)->where('member_id',$mem->id)->first();
                if(!empty($mov->LocalStockDetials)){
                    foreach($mov->LocalStockDetials as $Mco){
                        if($Mco->column_type == 'price'){
                            $oldprice = (float) $Mco->value;
                            
                        }
                    }
                }else{
                $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '>', Carbon::now()->subDays(30))->where('member_id',$mem->id)->first();
                if(!empty($mov->LocalStockDetials)){
                    foreach($mov->LocalStockDetials as $Mco){
                        if($Mco->column_type == 'price'){
                            $oldprice = (float) $Mco->value;
                            
                        }
                    }
                }else{
                    $oldprice = 0;
                }

                }

                $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '<=', $to)->where('member_id',$mem->id)->latest()->first();
                if(!empty($movnew->LocalStockDetials)){
                    foreach($movnew->LocalStockDetials as $Mcow){
                        if($Mcow->column_type == 'price'){
                            $newprice = (float) $Mcow->value;
                            
                        }
                    }
                }else{
                    
                $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$mem->id)->latest()->first();
                if(!empty($movnew->LocalStockDetials)){
                    foreach($movnew->LocalStockDetials as $Mcow){
                        if($Mcow->column_type == 'price'){
                            $newprice = (float) $Mcow->value;

                         
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
                $array[] = $result1;
            }
        }
       

        if(array_sum($array) > 0) {
            $results = array_sum($array) / count($array);
           
            $resultss = number_format($results, 2);
        }else{
            $resultss = 0;
        }
       
        return $resultss;
    }


    #new refactor show statistic
    public function newlaust(Request $request)
    {
        $members = Local_Stock_Member::where('section_id',$this->id)->get();
        $array = [];
        $from  = $request->input::get("from");
        $to    = $request->input::get("to");
        if(is_null($from) || is_null($to))
        {
            foreach($members as $mem){
                $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '>', Carbon::now()->subDays(30))->where('member_id',$mem->id)->first();
                if(!empty($mov->LocalStockDetials)){
                    foreach($mov->LocalStockDetials as $Mco){
                        if($Mco->column_type == 'price'){
                            $oldprice = (float) $Mco->value;

                        }
                    }
                }else{
                    $oldprice = 0;
                }

                $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$mem->id)->latest()->first();
                if(!empty($movnew->LocalStockDetials)){
                    foreach($movnew->LocalStockDetials as $Mcow){
                        if($Mcow->column_type == 'price'){
                            $newprice = (float) $Mcow->value;

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
                $array[] = $result1;
            }
        }else{
            foreach($members as $mem){
                $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '>=', $from)->where('member_id',$mem->id)->first();
                if(!empty($mov->LocalStockDetials)){
                    foreach($mov->LocalStockDetials as $Mco){
                        if($Mco->column_type == 'price'){
                            $oldprice = (float) $Mco->value;

                        }
                    }
                }else{
                    $mov = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '>', Carbon::now()->subDays(30))->where('member_id',$mem->id)->first();
                    if(!empty($mov->LocalStockDetials)){
                        foreach($mov->LocalStockDetials as $Mco){
                            if($Mco->column_type == 'price'){
                                $oldprice = (float) $Mco->value;

                            }
                        }
                    }
                }

                $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where( 'created_at', '<=', $to)->where('member_id',$mem->id)->latest()->first();
                if(!empty($movnew->LocalStockDetials)){
                    foreach($movnew->LocalStockDetials as $Mcow){
                        if($Mcow->column_type == 'price'){
                            $newprice = (float) $Mcow->value;

                        }
                    }
                }else{

                    $movnew = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns')->where('member_id',$mem->id)->latest()->first();
                    if(!empty($movnew->LocalStockDetials)){
                        foreach($movnew->LocalStockDetials as $Mcow){
                            if($Mcow->column_type == 'price'){
                                $newprice = (float) $Mcow->value;


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
                $array[] = $result1;
            }
        }


        if(array_sum($array) > 0) {
            $results = array_sum($array) / count($array);

            $resultss = number_format($results, 2);
        }else{
            $resultss = 0;
        }

        return $resultss;
    }

    # show count
    public function counts(Request $request)
    {
        $count1=0;
        $from  = $request->input("from");
        $to    = $request->input("to");
        $count = Local_Stock_Count::where('section_id',$this->id);
        if($from && $to){
            $count->whereBetween('created_at', [$from, $to]);
        }else{
            $count->where( 'created_at', '>', Carbon::now()->subDays(30));
        }

        $count = $count->count();
        return $count;
    }
  
}
