<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetaTag extends Model
{
    //
    public function news()
    {
        return $this->belongsTo('News::Class','news_id');
    }
}
