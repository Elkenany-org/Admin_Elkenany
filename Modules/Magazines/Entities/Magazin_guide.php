<?php

namespace Modules\Magazines\Entities;

use Illuminate\Database\Eloquent\Model;

class Magazin_guide extends Model
{
    protected $table = 'magazines_guides';

  
    public function Magazine()
    {
        return $this->belongsTo('Modules\Magazines\Entities\Magazine', 'maga_id', 'id');
    }
}
