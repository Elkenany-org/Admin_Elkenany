<?php

namespace Modules\Guide\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Banners extends Model
{
    protected $table = 'banners_home_android';

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return URL::to('uploads/services/').'/'.$this->image;
    }


}
