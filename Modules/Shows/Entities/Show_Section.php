<?php

namespace Modules\Shows\Entities;

use Illuminate\Database\Eloquent\Model;

class Show_Section extends Model
{
    protected $table = 'show_sections';

   

    public function Shows(){
        return $this->belongsToMany('Modules\Shows\Entities\Show','shows_sec','section_id','show_id');
    }

   
}
