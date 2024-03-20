<?php

namespace Modules\Cities\Entities;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    public function Company()
    {
        return $this->hasMany('Modules\Guide\Entities\Company', 'city_id', 'id');
    }

    public function Shows()
    {
        return $this->hasMany('Modules\Shows\Entities\Show', 'city_id', 'id');
    }

    public function Tender()
    {
        return $this->hasMany('Modules\Tenders\Entities\Tender', 'city_id', 'id');
    }


    public function Magazine()
    {
        return $this->hasMany('Modules\Magazines\Entities\Magazine', 'city_id', 'id');
    }

    public function Companytransports()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_transport', 'city_id', 'id');
    }

    public function Country()
    {
        return $this->belongsTo('Modules\Countries\Entities\Country', 'country_id', 'id');
    }

}
