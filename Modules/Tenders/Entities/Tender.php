<?php

namespace Modules\Tenders\Entities;

use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    protected $table = 'tenders';


    public function Section()
    {
        return $this->belongsTo('Modules\Tenders\Entities\Tender_Section', 'section_id', 'id');
    }

    public function City()
    {
        return $this->belongsTo('Modules\Cities\Entities\City', 'city_id', 'id');
    } 
  
}
