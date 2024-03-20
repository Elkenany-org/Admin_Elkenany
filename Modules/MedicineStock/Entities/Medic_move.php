<?php

namespace Modules\MedicineStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Medic_move extends Model
{
    protected $table = 'medicine_moves';

    public function Section()
    {
        return $this->belongsTo('Modules\MedicineStock\Entities\Medic_Section', 'section_id', 'id');
    }

    public function MedicStock()
    {
        return $this->belongsTo('Modules\MedicineStock\Entities\Medic_Stock', 'sub_id', 'id');
    }

    public function MedicSubs()
    {
        return $this->belongsTo('Modules\MedicineStock\Entities\Medic_Subs', 'active_id', 'id');
    }

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }

    public function Comname()
    {
        return $this->belongsTo('Modules\MedicineStock\Entities\Com_name', 'name_id', 'id');
    }

    public function Medicmember()
    {
        return $this->belongsTo('Modules\MedicineStock\Entities\Medic_member', 'member_id', 'id');
    }
    
}
