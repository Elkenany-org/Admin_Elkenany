<?php

namespace Modules\Shows\Entities;

use Illuminate\Database\Eloquent\Model;

class Showers extends Model
{
    protected $table = 'showers';

    public function Show()
    {
        return $this->belongsTo('Modules\Shows\Entities\Show', 'show_id', 'id');
    }


   
}
