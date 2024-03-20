<?php

namespace Modules\Guide\Entities;

use Illuminate\Database\Eloquent\Model;

class Company_Social_media extends Model
{
    protected $table = 'companies_social_media';

   
    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }
    public function Social()
    {
        return $this->belongsTo('App\Social', 'social_id', 'id');
    }
    
}
