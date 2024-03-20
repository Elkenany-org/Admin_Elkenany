<?php

namespace Modules\Guide\Entities;

use Illuminate\Database\Eloquent\Model;

class Company_Rate extends Model
{
    protected $table = 'companies_reating';

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }

    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }
    
}
