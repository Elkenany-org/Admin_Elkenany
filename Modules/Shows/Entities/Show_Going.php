<?php

namespace Modules\Shows\Entities;

use Illuminate\Database\Eloquent\Model;

class Show_Going extends Model
{
    protected $table = 'show_going';

    public function Show()
    {
        return $this->belongsTo('Modules\Shows\Entities\Show', 'show_id', 'id');
    }

    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }
   
}
