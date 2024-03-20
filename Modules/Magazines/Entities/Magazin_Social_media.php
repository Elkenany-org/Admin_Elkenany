<?php

namespace Modules\Magazines\Entities;

use Illuminate\Database\Eloquent\Model;

class Magazin_Social_media extends Model
{
    protected $table = 'magazines_social_media';

   
    public function Magazine()
    {
        return $this->belongsTo('Modules\Magazines\Entities\Magazine', 'maga_id', 'id');
    }
    public function Social()
    {
        return $this->belongsTo('App\Social', 'social_id', 'id');
    }
    
}
