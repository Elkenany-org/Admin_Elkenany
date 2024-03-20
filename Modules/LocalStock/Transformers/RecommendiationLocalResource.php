<?php

namespace Modules\LocalStock\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RecommendiationLocalResource extends JsonResource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'type'        => 'local',
            'image'      => $this->image_url,
            'sector_type'=>$this->Section ? $this->Section->name : '',
            'members'     => count($this->LocalStockMembers)
        ];
    }
}
