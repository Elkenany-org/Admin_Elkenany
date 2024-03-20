<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    protected $table = 'inboxes';
    
    public function User()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    
}
