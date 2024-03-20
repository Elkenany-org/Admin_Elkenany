<?php

namespace Modules\Shows\Entities;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $table = 'show_places';

    public function Show()
    {
        return $this->belongsTo('Modules\Shows\Entities\Show', 'show_id', 'id');
    }


   
}
