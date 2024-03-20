<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Noty extends Model
{
	protected $table = 'notfications';
	
	public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }

    public function Companyproduct()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company_product', 'pro_id', 'id');
    }
	
}
