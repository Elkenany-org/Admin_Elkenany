<?php

namespace Modules\LocalStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Local_Stock_Sections extends Model
{
    protected $table = 'local_stock_sections';


    public function LocalStockSub()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Sub', 'section_id', 'id')->orderBy('sort');
    }

    
    public function LocalStockSubs(){
        return $this->belongsToMany('Modules\LocalStock\Entities\Local_Stock_Sub','subs_all','section_id','sub_id')->orderBy('sort');
    }

   public function LocalSunIds()
   {
      $Local_Stock_Sub = $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Sub', 'section_id', 'id')->pluck('local_stock_subsections.id')->toArray();
      $local_Stock = $this->belongsToMany('Modules\LocalStock\Entities\Local_Stock_Sub','subs_all','section_id','sub_id')->pluck('local_stock_subsections.id')->toArray();

      return  array_merge($Local_Stock_Sub,$local_Stock);
   }
}
