<?php

namespace Modules\InternationalStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Ports extends Model
{
    protected $table = 'ports';

    public function Ships()
    {
        return $this->hasMany('Modules\InternationalStock\Entities\Ships', 'port_id', 'id');
    }
   
}
