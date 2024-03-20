<?php

namespace Modules\Magazines\Entities;

use Illuminate\Database\Eloquent\Model;

class Magazin_gallary extends Model
{
    protected $table = 'magazines_gallary';

    public function Magazine()
    {
        return $this->belongsTo('Modules\Magazines\Entities\Magazine', 'maga_id', 'id');
    }

    public function MagazineAlboumImages()
    {
        return $this->hasMany('Modules\Magazines\Entities\Magazin_Alboum_Images', 'gallary_id', 'id');
    }

}
