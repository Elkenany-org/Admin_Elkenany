<?php

namespace Modules\FodderStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Stock_Feeds extends Model
{
    protected $table = 'stock_feeds';

    public function FodderStocks()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Fodder_Stock', 'fodder_id', 'id');
    }

    public function FodderStockMoves()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Fodder_Stock_Move', 'fodder_id', 'id');
    }

    public function Section()
    {
        return $this->belongsTo('Modules\FodderStock\Entities\Stock_Fodder_Sub', 'section_id', 'id');
    }

    public function MiniSub()
    {
        return $this->belongsTo('Modules\FodderStock\Entities\Mini_Sub', 'mini_id', 'id');
    }
    
}
