<?php

namespace Modules\Guide\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RecommendiationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'type'=>'guide',
            'image'=>$this->image_url,
            'sector_type'=>$this->Section->name,
            'companies_count'=>count($this->Company),
        ];
    }
}
