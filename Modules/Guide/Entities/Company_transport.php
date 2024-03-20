<?php

namespace Modules\Guide\Entities;

use Illuminate\Database\Eloquent\Model;

class Company_transport extends Model
{
    protected $table = 'company_transports';

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }

    public function City()
    {
        return $this->belongsTo('Modules\Cities\Entities\City', 'city_id', 'id');
    }
}
