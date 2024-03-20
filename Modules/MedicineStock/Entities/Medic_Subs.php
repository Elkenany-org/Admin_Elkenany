<?php

namespace Modules\MedicineStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Medic_Subs extends Model
{
    protected $table = 'medicine_substances';


    public function Medicmembers()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Medic_member', 'active_id', 'id');
    }

    public function Medicmoves()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Medic_move', 'active_id', 'id');
    }
    
}
