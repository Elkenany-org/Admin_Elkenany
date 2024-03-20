<?php

namespace Modules\Countries\Entities;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    public function cities()
    {
        return $this->hasMany('Modules\Cities\Entities\City', 'country_id', 'id');
    }

    public function Company()
    {
        return $this->hasMany('Modules\Guide\Entities\Company', 'country_id', 'id');
    }

    public function Shows()
    {
        return $this->hasMany('Modules\Shows\Entities\Show', 'country_id', 'id');
    }

   
}
