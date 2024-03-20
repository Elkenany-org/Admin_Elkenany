<?php

namespace Modules\LocalStock\Entities;

use App\Events\LocalStockChanges;
use Illuminate\Database\Eloquent\Model;

class Local_Stock_Detials extends Model
{
    protected $table = 'local_stock_movement_details';

    protected $fillable = ['value','column_id','column_type','movement_id','member_id','section_id'];
    public function Section()
    {
        return $this->belongsTo('Modules\LocalStock\Entities\Local_Stock_Sub', 'section_id', 'id');
    }

    public function LocalStockMember()
    {
        return $this->belongsTo('Modules\LocalStock\Entities\Local_Stock_Member', 'member_id', 'id');
    }
    
    public function LocalStockMovement()
    {
        return $this->belongsTo('Modules\LocalStock\Entities\Local_Stock_Movement', 'movement_id', 'id');
    }
    
    public function LocalStockColumns()
    {
        return $this->belongsTo('Modules\LocalStock\Entities\Local_Stock_Columns', 'column_id', 'id');
    }

    protected $dispatchesEvents = [
        'created'=>LocalStockChanges::class
    ];
}
