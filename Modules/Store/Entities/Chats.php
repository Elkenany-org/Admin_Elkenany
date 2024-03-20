<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    protected $table = 'chats';
   
    public function User()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }

    public function Owner()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'owner_id', 'id');
    }

    public function massages()
    {
        return $this->hasMany('Modules\Store\Entities\Chat_Mas', 'chat_id', 'id');
    }
    
}
