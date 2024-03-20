<?php

namespace Modules\News\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsReferencesResource extends JsonResource
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
            'title'=>$this->title,
            'link'=>$this->link,
        ];
    }
}
