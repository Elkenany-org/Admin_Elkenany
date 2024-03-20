<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Main extends Model
{
	protected $table = 'main_sections';
	
	protected $appends = ['image_url'];

    protected $hidden = ['created_at','updated_at','image_url'];

    public function getImageUrlAttribute()
    {
        if($this->image != ""){
            return URL::to('uploads/main/'.$this->image);
        }
        return "";
    }

}
