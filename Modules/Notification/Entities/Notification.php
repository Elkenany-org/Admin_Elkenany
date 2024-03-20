<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\URL;
use Modules\Guide\Entities\Company;

class Notification extends Model
{
//    use HasFactory;



    protected $table = 'notification_systems';

    protected $fillable = ['title','body','image','date_at','time_at','status','duration_type'];

    protected $appends = ['image_url','image_thum_url'];

    public function getImageUrlAttribute()
    {
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        if($this->image != "")
        {
            return URL::to('uploads/notifications').'/'.$this->image;
        }
        return "";
    }

    public function getImageThumUrlAttribute()
    {
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        if($this->image != "")
        {
            return URL::to('uploads/notifications/thumbnail/').'/'.$this->image;
        }
        return "";
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
//    protected static function newFactory()
//    {
//        return \Modules\Notification\Database\factories\NotificationFactory::new();
//    }
}
