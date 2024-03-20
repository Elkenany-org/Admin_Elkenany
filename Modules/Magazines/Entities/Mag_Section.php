<?php

namespace Modules\Magazines\Entities;

use Illuminate\Database\Eloquent\Model;

class Mag_Section extends Model
{
    protected $table = 'magazines_sections';

    public function Magazine(){
        return $this->belongsToMany('Modules\Magazines\Entities\Magazine','magazines_secs','section_id','maga_id');
    }
}
