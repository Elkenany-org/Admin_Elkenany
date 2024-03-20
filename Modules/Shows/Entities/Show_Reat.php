<?php

namespace Modules\Shows\Entities;

use Illuminate\Database\Eloquent\Model;

class Show_Reat extends Model
{
    protected $table = 'show_reats';

    public function Show()
    {
        return $this->belongsTo('Modules\Shows\Entities\Show', 'show_id', 'id');
    }

 
}
