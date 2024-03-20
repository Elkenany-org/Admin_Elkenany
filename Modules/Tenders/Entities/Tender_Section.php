<?php

namespace Modules\Tenders\Entities;

use Illuminate\Database\Eloquent\Model;

class Tender_Section extends Model
{
    protected $table = 'tenders_sections';
   
  
    public function Tenders()
    {
        return $this->hasMany('Modules\Tenders\Entities\Tender', 'section_id', 'id');
    }
}
