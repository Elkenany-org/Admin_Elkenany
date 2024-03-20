<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Model;

class Job_Offer extends Model
{
    protected $table = 'job_offers';

    public function Admin()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'admin_id', 'id');
    }
    public function Recruiter()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'recruiter_id', 'id');
    }

    public function Category()
    {
        return $this->belongsTo('Modules\Recruitment\Entities\Job_Categories', 'category_id', 'id');
    }

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }

    public function Sector()
    {
        return $this->belongsTo('App\Main', 'sector_id', 'id');
    }


    public function Applicants()
    {
        return $this->hasMany('Modules\Recruitment\Entities\Job_Application', 'applicant_id', 'id');
    }

}