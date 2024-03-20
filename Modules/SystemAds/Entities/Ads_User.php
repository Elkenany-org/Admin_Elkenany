<?php

namespace Modules\SystemAds\Entities;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PhpParser\Node\Expr\AssignOp\Mod;

class Ads_User extends Authenticatable
{
    use Notifiable;

    protected $table = 'ads_users';

    public function Memberships()
    {
        return $this->hasMany('Modules\SystemAds\Entities\Membership', 'ads_user_id', 'id');
    }

    public function SystemAds()
    {
        return $this->hasMany('Modules\SystemAds\Entities\System_Ads', 'ads_user_id', 'id');
    }

    public function AdsCompanys()
    {
        return $this->hasMany('Modules\SystemAds\Entities\Ads_Company', 'ads_user_id', 'id');
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

  
}
