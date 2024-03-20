<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\URL;


class NewsAddition extends Model
{
//    use HasFactory;
    protected $table = 'news_additions';


    protected $fillable = ['key','value','new_id','sort'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if($this->key == 'image'){
            return URL::to('/uploads/news/avatar/').'/'.$this->value;
        }
        return "";
    }

    public function getImageThumUrlAttribute()
    {
        if($this->key == 'image') {
            return URL::to('/uploads/news/avatar/thumbnail') . '/' . $this->value;
        }
        return "";
    }

//    protected static function newFactory()
//    {
//        return \Modules\News\Database\factories\NewsAdditionFactory::new();
//    }
}
