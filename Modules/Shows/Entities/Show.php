<?php

namespace Modules\Shows\Entities;

use App\Events\ShowCreate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Show extends Model
{
    protected $table = 'shows';

    protected $appends = ['image_url','image_thum_url'];

    public function getImageUrlAttribute()
    {
       return URL::to('uploads/show/images/').'/'.$this->image;
    }
    public function getImageThumUrlAttribute()
    {
       return URL::to('uploads/show/images/thumbnail/').'/'.$this->image;
    }

    public function Section(){
        return $this->belongsToMany('Modules\Shows\Entities\Show_Section','shows_sec','show_id','section_id');
    }

    public function City()
    {
        return $this->belongsTo('Modules\Cities\Entities\City', 'city_id', 'id');
    } 

    public function Country()
    {
        return $this->belongsTo('Modules\Countries\Entities\Country', 'country_id', 'id');
    }

    public function ShowOrgs()
    {
        return $this->hasMany('Modules\Shows\Entities\Show_Org', 'show_id', 'id');
    }

    public function ShowImgs()
    {
        return $this->hasMany('Modules\Shows\Entities\Show_Img', 'show_id', 'id');
    }

    public function ShowTacs()
    {
        return $this->hasMany('Modules\Shows\Entities\Show_Tac', 'show_id', 'id');
    }

    public function Showers()
    {
        return $this->hasMany('Modules\Shows\Entities\Showers', 'show_id', 'id');
    }

    public function Speakers()
    {
        return $this->hasMany('Modules\Shows\Entities\Speaker', 'show_id', 'id');
    }

    public function ShowReats()
    {
        return $this->hasMany('Modules\Shows\Entities\Show_Reat', 'show_id', 'id');
    }

    public function Places()
    {
        return $this->hasMany('Modules\Shows\Entities\Place', 'show_id', 'id');
    }

    public function Interested()
    {
        return $this->hasMany('Modules\Shows\Entities\Interested', 'show_id', 'id');
    }

    public function ShowGoing()
    {
        return $this->hasMany('Modules\Shows\Entities\Show_Going', 'show_id', 'id');
    }

     # show count
    public function time()
    {
        $time     = $this->time;
        $times    = json_decode($time);
        return $times;
    }

    # show count
    public function watch()
    {
        $watch     = $this->watch;
        $watchs    = json_decode($watch);
        return $watchs;
    }

    protected $dispatchesEvents = [
      'created'=>ShowCreate::class
    ];
   
}
