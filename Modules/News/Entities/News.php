<?php

namespace Modules\News\Entities;

use App\Events\NewsCreate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = ['title','desc','image','link','section_id'];

    protected $appends = ['image_url','image_thum_url'];

    public function getImageUrlAttribute()
    {
        return URL::to('/uploads/news/avatar/').'/'.$this->image;
    }

    public function getImageThumUrlAttribute()
    {
        return URL::to('/uploads/news/avatar/thumbnail').'/'.$this->image;
    }

    public function Newsimages()
    {
        return $this->hasMany('Modules\News\Entities\News_images', 'news_id', 'id');
    }

    public function Section()
    {
        return $this->belongsTo('Modules\News\Entities\News_Section', 'section_id', 'id');
    }

    
    public function sections(){
        return $this->belongsToMany('Modules\News\Entities\News_Section','multi_news','new_id','section_id');
    }

    public function NewsAdditions(){
        return $this->hasMany('Modules\News\Entities\NewsAddition','new_id')->orderBy('sort','ASC');
    }

    public function NewsReferences(){
        return $this->hasMany('Modules\News\Entities\NewsReference','new_id');
    }
    protected $dispatchesEvents = [
        'created'=>NewsCreate::class
    ];
}
