<?php

namespace Modules\FodderStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Mini_Sub extends Model
{
    protected $table = 'mini_sub';


    public function Section()
    {
        return $this->belongsTo('Modules\FodderStock\Entities\Stock_Fodder_Sub', 'section_id', 'id');
    }
    

    public function StockFeeds()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Stock_Feeds', 'mini_id', 'id');
    }
}
