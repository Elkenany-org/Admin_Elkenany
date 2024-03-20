<?php

namespace Modules\Consultants\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;

class Sub_Section extends Model
{
    protected $table = 'sub_sections';

    public function Doctor(){
        return $this->belongsToMany('Modules\Consultants\Entities\Doctor','doctors_majors','sub_id','doctor_id');
    }

    public function Major()
    {
        return $this->belongsTo('Modules\Consultants\Entities\Major', 'major_id', 'id');
    }

    public function logooos()
    {

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$this->id)->where('type','consultants')->pluck('ads_id')->toArray();

      

        return  System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
    } 
}
