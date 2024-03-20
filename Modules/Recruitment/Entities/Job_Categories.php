<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Model;

class Job_Categories extends Model
{
    protected $table = 'job_catergories';

    public function JobOffers()
    {
        return $this->hasMany('Modules\Recruitment\Entities\Job_Offer', 'job_catergories', 'id');
    }

}