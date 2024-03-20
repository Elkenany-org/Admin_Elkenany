<?php

namespace Modules\Shows\Entities;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    protected $table = 'show_speakers';

    public function Show()
    {
        return $this->belongsTo('Modules\Shows\Entities\Show', 'show_id', 'id');
    }


   
}
