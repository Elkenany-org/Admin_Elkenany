<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainImages extends Model
{
    //
    protected $table = 'main_images';

    public function Service()
    {
        return $this->belongsTo('Modules\Guide\Entities\Services','services');
    }

    public function Visited()
    {
        return $this->belongsTo('Modules\Guide\Entities\Services','most_visited');
    }

    public function Newest()
    {
        return $this->belongsTo('Modules\Guide\Entities\Services','newest');
    }
}
