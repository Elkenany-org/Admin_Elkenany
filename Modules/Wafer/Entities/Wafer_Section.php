<?php

namespace Modules\Wafer\Entities;

use Illuminate\Database\Eloquent\Model;

class Wafer_Section extends Model
{
    protected $table = 'wafer_sections';

    public function WaferFarmers()
    {
        return $this->hasMany('Modules\Wafer\Entities\Wafer_Farmer', 'section_id', 'id');
    }

    public function WaferPosts()
    {
        return $this->hasMany('Modules\Wafer\Entities\Wafer_Post', 'section_id', 'id');
    }

}
