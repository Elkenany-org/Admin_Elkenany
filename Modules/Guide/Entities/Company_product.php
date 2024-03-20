<?php

namespace Modules\Guide\Entities;

use App\Events\CompanyProductCreate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Company_product extends Model
{
    protected $table = 'company_products';

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if($this->image != ""){
            return URL::to('/uploads/company/product/').'/'.$this->image;
        }
        return "";
    }

    public function Section()
    {
        return $this->belongsTo('Modules\Guide\Entities\Guide_Section', 'section_id', 'id');
    }

    public function SubSections()
    {
        return $this->belongsTo('Modules\Guide\Entities\Guide_Sub_Section', 'sub_section_id', 'id');
    }
    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }

    
    public function Notys()
    {
    	return $this->hasMany('App\Noty','pro_id','id');
    }
    protected $dispatchesEvents = [
        'created'=>CompanyProductCreate::class
    ];
}
