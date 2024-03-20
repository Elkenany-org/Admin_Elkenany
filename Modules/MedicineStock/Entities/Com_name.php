<?php

namespace Modules\MedicineStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Com_name extends Model
{
    protected $table = 'commercial_names';

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }

    public function Comnameimages()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Com_name_images', 'name_id', 'id');
    }

    public function Medicmembers()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Medic_member', 'name_id', 'id');
    }

    public function Medicmoves()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Medic_move', 'name_id', 'id');
    }
   

    
}
