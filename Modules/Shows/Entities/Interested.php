<?php

namespace Modules\Shows\Entities;

use Illuminate\Database\Eloquent\Model;

class Interested extends Model
{
    protected $table = 'show_interested';

    public function Show()
    {
        return $this->belongsTo('Modules\Shows\Entities\Show', 'show_id', 'id');
    }

    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }
   
}
