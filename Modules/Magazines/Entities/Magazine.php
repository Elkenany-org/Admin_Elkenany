<?php

namespace Modules\Magazines\Entities;

use App\Events\MagazinCreate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Magazine extends Model
{
    protected $table = 'magazines';

   protected $appends = ['image_url','image_thum_url'];

   public function getImageUrlAttribute()
   {
       return URL::to('/uploads/magazine/images/').'/'.$this->image;
   }

    public function getImageThumUrlAttribute()
    {
       return URL::to('/uploads/magazine/images/thumbnail/').'/'.$this->image;
    }

    public function City()
    {
        return $this->belongsTo('Modules\Cities\Entities\City', 'city_id', 'id');
    } 

    public function sections(){
        return $this->belongsToMany('Modules\Magazines\Entities\Mag_Section','magazines_secs','maga_id','section_id');
    }

    public function MagazineAlboumImages()
    {
        return $this->hasMany('Modules\Magazines\Entities\Magazin_Alboum_Images', 'maga_id', 'id');
    }

    public function MagazinSocialmedia()
    {
        return $this->hasMany('Modules\Magazines\Entities\Magazin_Social_media', 'maga_id', 'id');
    }

    public function Magazinguide()
    {
        return $this->hasMany('Modules\Magazines\Entities\Magazin_guide', 'maga_id', 'id');
    }

    public function Magazineaddress()
    {
        return $this->hasMany('Modules\Magazines\Entities\Magazine_address', 'maga_id', 'id');
    }

    public function Magazingallary()
    {
        return $this->hasMany('Modules\Magazines\Entities\Magazin_gallary', 'maga_id', 'id');
    }

    public function MagazineRate()
    {
        return $this->hasMany('Modules\Magazines\Entities\Magazine_Rate', 'maga_id', 'id');
    }

    protected $dispatchesEvents = [
      'created'=>MagazinCreate::class
    ];

  
}
