<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsReference extends Model
{
//    use HasFactory;

    protected $table = 'news_references';

    protected $fillable = ['title','link','new_id'];
    
//    protected static function newFactory()
//    {
//        return \Modules\News\Database\factories\NewsReferenceFactory::new();
//    }
}
