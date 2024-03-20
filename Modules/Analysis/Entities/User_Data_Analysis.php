<?php

namespace Modules\Analysis\Entities;

use Illuminate\Database\Eloquent\Model;

class User_Data_Analysis extends Model
{
    protected $table = 'users_data_analysis';
    
    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }

    public function Keyword()
    {
        return $this->belongsTo('Modules\Analysis\Entities\Data_Analysis_Keywords', 'keyword_id', 'id');
    }
    
}
