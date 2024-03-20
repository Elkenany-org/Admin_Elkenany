<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;

class News_Section extends Model
{
    protected $table = 'news_sections';
   
  
    public function News()
    {
        return $this->hasMany('Modules\News\Entities\News', 'section_id', 'id');
    }

    public function sections(){
        return $this->belongsToMany('Modules\News\Entities\News','multi_news','section_id','new_id');
    }
}
