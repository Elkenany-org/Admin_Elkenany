<?php

namespace Modules\Guide\Entities;

use App\MetaTag;
use Illuminate\Database\Eloquent\Model;

class Guide_Section extends Model
{
    protected $table = 'guide_sections';

    public function SubSections()
    {
        return $this->hasMany('Modules\Guide\Entities\Guide_Sub_Section', 'section_id', 'id');
    }


    public function Company(){
        return $this->belongsToMany('Modules\Guide\Entities\Company','companies_sections','section_id','company_id');
    }

    public function CompanyAlboumImages()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_Alboum_Images', 'section_id', 'id');
    }
    public function Companyproduct()
    {
        return $this->hasMany('Modules\Guide\Entities\Company_product', 'section_id', 'id');
    }

    public function metaTags()
    {
        return $this->hasMany(MetaTag::class);
    }
}
