<?php

namespace Modules\FodderStock\Entities;
use Illuminate\Support\Facades\URL;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;

use Illuminate\Database\Eloquent\Model;

class Stock_Fodder_Sub extends Model
{
    protected $table = 'fodder_sub_sections';

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return URL::to('uploads/sections/avatar/').'/'.$this->image;
    }

    public function Section()
    {
        return $this->belongsTo('Modules\FodderStock\Entities\Stock_Fodder_Section', 'section_id', 'id');
    }

    public function logooss()
    {

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$this->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $logoss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        return  $logoss;
    } 

    public function FodderStocks()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Fodder_Stock', 'sub_id', 'id');
    }
    
    public function FodderStockMoves()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Fodder_Stock_Move', 'sub_id', 'id');
    }

    public function StockFeeds()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Stock_Feeds', 'section_id', 'id');
    }

    public function MiniSubs()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Mini_Sub', 'section_id', 'id');
    }

}
