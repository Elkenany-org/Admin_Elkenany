<?php

namespace Modules\MedicineStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Medic_Stock extends Model
{
    protected $table = 'medicine_stocks';

    public function Section()
    {
        return $this->belongsTo('Modules\MedicineStock\Entities\Medic_Section', 'section_id', 'id');
    }

    public function sections(){
        return $this->belongsToMany('Modules\MedicineStock\Entities\Medic_Section','multi_medicine_stocks','sub_id','section_id');
    }

    public function Medicmembers()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Medic_member', 'sub_id', 'id');
    }

    public function Medicmoves()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Medic_move', 'sub_id', 'id');
    }


    
}
