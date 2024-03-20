<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Model;

class Job_Favorite extends Model
{
    protected $table = 'job_favorite';

    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'customer_id', 'id');
    }
    public function JobOffer()

    {
        return $this->belongsTo('Modules\Recruitment\Entities\Job_Offer', 'job_id', 'id');
    }



}