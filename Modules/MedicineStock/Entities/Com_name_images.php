<?php

namespace Modules\MedicineStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Com_name_images extends Model
{
    protected $table = 'names_images';

    public function Comname()
    {
        return $this->belongsTo('Modules\MedicineStock\Entities\Com_name', 'name_id', 'id');
    }

   

    
}
