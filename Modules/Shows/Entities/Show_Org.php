<?php

namespace Modules\Shows\Entities;

use Illuminate\Database\Eloquent\Model;

class Show_Org extends Model
{
    protected $table = 'shows_organisers';

    public function Show()
    {
        return $this->belongsTo('Modules\Shows\Entities\Show', 'show_id', 'id');
    }

    public function Organ()
    {
        return $this->belongsTo('Modules\Shows\Entities\Organ', 'org_id', 'id');
    } 

   
}
