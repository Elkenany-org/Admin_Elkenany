<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PhpParser\Node\Expr\AssignOp\Mod;
use App\Notifications\customerResetPasswordNotification;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $table = 'customers';

    public function StoreAds()
    {
        return $this->hasMany('Modules\Store\Entities\Store_Ads', 'user_id', 'id');
    }

    public function Chatsusers()
    {
        return $this->hasMany('Modules\Store\Entities\Chats', 'user_id', 'id');
    }

    public function Chatsowners()
    {
        return $this->hasMany('Modules\Store\Entities\Chats', 'owner_id', 'id');
    }

    public function sendrs()
    {
        return $this->hasMany('Modules\Store\Entities\Chat_Mas', 'sender_id', 'id');
    }

    public function resavs()
    {
        return $this->hasMany('Modules\Store\Entities\Chat_Mas', 'resav_id', 'id');
    }

    public function DoctorOrders()
    {
        return $this->hasMany('Modules\Consultants\Entities\Doctor_Orders', 'user_id', 'id');
    }

    public function CourseQuizzResults()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Quizz_Result', 'user_id', 'id');
    }

    public function CourseQuizzAnswers()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Quizz_Answer', 'user_id', 'id');
    }

    public function CourseComments()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Comment', 'user_id', 'id');
    }

    public function REPComments()
    {
        return $this->hasMany('Modules\Academy\Entities\REP_Comment', 'user_id', 'id');
    }

    public function StoreAdsComments()
    {
        return $this->hasMany('Modules\Store\Entities\Store_Ads_Comment', 'user_id', 'id');
    }

    public function WaferOrders()
    {
        return $this->hasMany('Modules\Wafer\Entities\Wafer_Order', 'user_id', 'id');
    }

    public function User_Analysis() {
        return $this->hasMany('Modules\Analysis\Entities\User_Data_Analysis', 'user_id', 'id');
    }

    public function Data_Analysis() {
        return $this->hasMany('Modules\Analysis\Entities\Data_Analysis', 'user_id', 'id');
    }

    public function CompanyRates()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_Rate', 'user_id', 'id');
    }

    public function MagazineRates()
    {
        return $this->hasMany('Modules\Magazines\Entities\Magazine_Rate', 'user_id', 'id');
    }

    public function DoctorRatings()
    {
        return $this->hasMany('Modules\Consultants\Entities\Doctor_Rating', 'user_id', 'id');
    }


    public function Interested()
    {
        return $this->hasMany('Modules\Shows\Entities\Interested', 'user_id', 'id');
    }

     public function ShowGoing()
    {
        return $this->hasMany('Modules\Shows\Entities\Show_Going', 'user_id', 'id');
    }

    public function Courseusers()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_user', 'user_id', 'id');
    }

    public function Uservideos()
    {
        return $this->hasMany('Modules\Academy\Entities\User_videos', 'user_id', 'id');
    }

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }
    protected $hidden = [
        'password', 'remember_token',
    ];

    

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new customerResetPasswordNotification($token));
    }

}
