<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Model;

class Job_Application extends Model
{
    protected $table = 'job_application';

    public function Applicant()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'applicant_id', 'id');
    }
    public function JobOffer()
    {
        return $this->belongsTo('Modules\Recruitment\Entities\Job_Offer', 'job_id', 'id');
    }



}