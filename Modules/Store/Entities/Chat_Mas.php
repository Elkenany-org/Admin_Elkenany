<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;

class Chat_Mas extends Model
{
    protected $table = 'chat_massages';
   
    public function send()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'sender_id', 'id');
    }

    public function resav()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'resav_id', 'id');
    }

    public function Chat()
    {
        return $this->belongsTo('Modules\Store\Entities\Chats', 'chat_id', 'id');
    }

    
}
