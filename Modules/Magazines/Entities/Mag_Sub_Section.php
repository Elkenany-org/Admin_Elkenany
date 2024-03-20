<?php

namespace Modules\Magazines\Entities;

use Illuminate\Database\Eloquent\Model;

class Mag_Sub_Section extends Model
{
    protected $table = 'magazines_sub_sections';

    public function Section()
    {
        return $this->belongsTo('Modules\Magazines\Entities\Mag_Section', 'section_id', 'id');
    }

    public function Magazine(){
        return $this->belongsToMany('Modules\Magazines\Entities\Magazine','magazines_secs','sub_section_id','maga_id');
    }
    
}
