<?php

namespace Modules\Guide\Entities;

use App\MetaTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;

class Guide_Sub_Section extends Model
{
    protected $table = 'guide_sub_sections';
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if($this->image != ""){
            return URL::to('uploads/sections/avatar/'.$this->image);
        }
        return "";
    }
    public function Section()
    {
        return $this->belongsTo('Modules\Guide\Entities\Guide_Section', 'section_id', 'id');
    }

    public function Company(){
        return $this->belongsToMany('Modules\Guide\Entities\Company','companies_sections','sub_section_id','company_id');
    }

    public function logooos()
    {

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$this->id)->where('type','guide')->pluck('ads_id')->toArray();

      

        return  System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
    } 


    public function CompanyAlboumImages()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_Alboum_Images', 'sub_section_id', 'id');
    }
    public function Companyproduct()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_product', 'sub_section_id', 'id');
    }

    public function metaTags()
    {
        return $this->hasMany(MetaTag::class);
    }
}
