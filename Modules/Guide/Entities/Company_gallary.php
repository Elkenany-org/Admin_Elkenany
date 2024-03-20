<?php

namespace Modules\Guide\Entities;

use Illuminate\Database\Eloquent\Model;

class Company_gallary extends Model
{
    protected $table = 'company_gallary';

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }

    public function CompanyAlboumImages()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_Alboum_Images', 'gallary_id', 'id');
    }

}
