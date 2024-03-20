<?php

namespace Modules\FodderStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Stock_Fodder_Section extends Model
{
    protected $table = 'stock_fodder_sections';

    public function FodderStocks()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Fodder_Stock', 'section_id', 'id');
    }
    
    public function FodderStockMoves()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Fodder_Stock_Move', 'section_id', 'id');
    }

   
    public function StockFodderSubs()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Stock_Fodder_Sub', 'section_id', 'id');
    }
}
