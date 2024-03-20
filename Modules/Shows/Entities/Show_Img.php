<?php

namespace Modules\Shows\Entities;

use Illuminate\Database\Eloquent\Model;

class Show_Img extends Model
{
    protected $table = 'shows_images';

    public function Show()
    {
        return $this->belongsTo('Modules\Shows\Entities\Show', 'show_id', 'id');
    }


   
}
