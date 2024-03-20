<?php

namespace Modules\News\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RecommendiationNewsResource extends JsonResource
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
            'id'    => $this->id,
            'name'  => $this->title,
            'type'  => 'news',
            'image' => $this->image_url,
            'sector_type' => $this->Section->name,
        ];
    }
}
