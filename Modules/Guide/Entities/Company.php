<?php

namespace Modules\Guide\Entities;

use App\Events\CompanyCreate;
use App\MetaTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Company extends Model
{
    protected $table = 'companies';

    protected $appends = ['image_url','image_thum_url'];

    public function getImageUrlAttribute()
    {
        if($this->image != ""){
            return URL::to('uploads/company/images/').'/'.$this->image;
        }
        return "";
    }

    public function getImageThumUrlAttribute()
    {
        if($this->image != ""){
            return URL::to('uploads/company/images/thumbnail/').'/'.$this->image;
        }
        return "";
    }

    public function sections(){
        return $this->belongsToMany('Modules\Guide\Entities\Guide_Section','companies_sections','company_id','section_id');
    }

    public function SubSections(){
        return $this->belongsToMany('Modules\Guide\Entities\Guide_Sub_Section','companies_sections','company_id','sub_section_id');
    }

    public function Country()
    {
        return $this->belongsTo('Modules\Countries\Entities\Country', 'country_id', 'id');
    }

    public function Notys()
    {
    	return $this->hasMany('App\Noty','company_id','id');
    }

    public function City()
    {
        return $this->belongsTo('Modules\Cities\Entities\City', 'city_id', 'id');
    } 

    public function CompanyAlboumImages()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_Alboum_Images', 'company_id', 'id');
    }

    public function CompanySocialmedia()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_Social_media', 'company_id', 'id');
    }

    public function Companyproduct()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_product', 'company_id', 'id');
    }

    public function Companyaddress()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_address', 'company_id', 'id');
    }

    public function Companytransports()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_transport', 'company_id', 'id');
    }

    public function Companygallary()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_gallary', 'company_id', 'id');
    }

    public function LocalStockMember()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Member', 'company_id', 'id');
    }

    public function CompanyRates()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_Rate', 'company_id', 'id');
    }

    public function FodderStocks()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Fodder_Stock', 'company_id', 'id');
    }

    public function FodderStockMoves()
    {
        return $this->hasMany('Modules\FodderStock\Entities\Fodder_Stock_Move', 'company_id', 'id');
    }

    public function Ships()
    {
        return $this->hasMany('Modules\InternationalStock\Entities\Ships', 'company_id', 'id');
    }

    public function AdsCompanys()
    {
        return $this->hasMany('Modules\SystemAds\Entities\Ads_Company', 'company_id', 'id');
    }

    public function Memberships()
    {
        return $this->hasMany('Modules\SystemAds\Entities\Membership', 'company_id', 'id');
    }

    public function SystemAds()
    {
        return $this->hasMany('Modules\SystemAds\Entities\System_Ads', 'company_id', 'id');
    }

    public function Comnames()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Com_name', 'company_id', 'id');
    }

    public function Medicmembers()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Medic_member', 'company_id', 'id');
    }

    public function Medicmoves()
    {
        return $this->hasMany('Modules\MedicineStock\Entities\Medic_move', 'company_id', 'id');
    }

    public function metaTags()
    {
        return $this->hasMany(MetaTag::class);
    }

    protected $dispatchesEvents = [
        'created'=>CompanyCreate::class
    ];
}
