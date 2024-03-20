<?php

namespace Modules\Guide\Entities;

use Illuminate\Database\Eloquent\Model;

class Company_Alboum_Images extends Model
{
    protected $table = 'companies_alboum_images';

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

    public function Companygallary()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company_gallary', 'gallary_id', 'id');
    }
    
}
