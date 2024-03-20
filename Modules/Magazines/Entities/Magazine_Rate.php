<?php

namespace Modules\Magazines\Entities;

use Illuminate\Database\Eloquent\Model;

class Magazine_Rate extends Model
{
    protected $table = 'magazines_reating';

    public function Magazine()
    {
        return $this->belongsTo('Modules\Magazines\Entities\Magazine', 'maga_id', 'id');
    }
    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }
    
}
