<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;

class News_images extends Model
{
    protected $table = 'news_images';
   
    public function News()
    {
        return $this->belongsTo('Modules\News\Entities\News', 'news_id', 'id');
    }
}
