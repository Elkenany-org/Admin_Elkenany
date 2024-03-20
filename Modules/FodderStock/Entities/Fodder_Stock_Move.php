<?php

namespace Modules\FodderStock\Entities;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Modules\SystemAds\Entities\System_Ads;
use Modules\SystemAds\Entities\System_Ads_Pages;

class Fodder_Stock_Move extends Model
{
    protected $table = 'fodder_stocks_movements';

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

    public function FodderStock()
    {
        return $this->belongsTo('Modules\FodderStock\Entities\Fodder_Stock', 'stock_id', 'id');
    }

    public function ranking($id){
        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$id)->where('type','fodderstock')->pluck('ads_id')->toArray();
        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();
        $type = 0;
        if(isset($this->company_id) && in_array($this->company_id,$ads)){
            $type = 1;
        }
        return $type;
    }
   


    
}
