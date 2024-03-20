<?php

namespace Modules\Shows\Entities;

use Illuminate\Database\Eloquent\Model;

class Organ extends Model
{
    protected $table = 'organisers';

    public function ShowOrgs()
    {
        return $this->hasMany('Modules\Shows\Entities\Show_Org', 'org_id', 'id');
    }

   
}
