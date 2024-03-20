<?php

namespace Modules\Analysis\Entities;

use Illuminate\Database\Eloquent\Model;

class Data_Analysis extends Model
{
    protected $table = 'data_analysis';
    
    public function Keyword()
    {
        return $this->belongsTo('Modules\Analysis\Entities\Data_Analysis_Keywords', 'keyword_id', 'id');
    }
    
}
