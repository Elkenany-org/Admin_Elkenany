<?php

namespace Modules\MedicineStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Medic_Section extends Model
{
    protected $table = 'medicine_sections';

    
    public function MedicStocks()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Medic_Stock', 'section_id', 'id');
    }


    public function MultiMedicStocks(){
        return $this->belongsToMany('Modules\MedicineStock\Entities\Medic_Stock','multi_medicine_stocks','section_id','sub_id');
    }

    public function Medicmembers()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Medic_member', 'section_id', 'id');
    }

    public function Medicmoves()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Medic_move', 'section_id', 'id');
    }

}
