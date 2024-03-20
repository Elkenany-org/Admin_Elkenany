<?php

namespace Modules\Magazines\Entities;

use Illuminate\Database\Eloquent\Model;

class Magazin_Alboum_Images extends Model
{
    protected $table = 'magazines_alboum_images';

    public function Magazine()
    {
        return $this->belongsTo('Modules\Magazines\Entities\Magazine', 'maga_id', 'id');
    }

    public function Magazinegallary()
    {
        return $this->belongsTo('Modules\Magazines\Entities\Magazin_gallary', 'gallary_id', 'id');
    }
    
}
