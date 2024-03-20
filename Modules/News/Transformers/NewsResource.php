<?php

namespace Modules\News\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Date;
class NewsResource extends JsonResource
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
            'title'=>$this->title,
            'image'=>$this->image_thum_url,
            'desc'=>$this->desc,
            'link'=>$this->link,
            'date_time'=>Date::parse($this->created_at)->diffForHumans(),
            'additions'=>NewsAdditionsResource::collection($this->NewsAdditions),
            'references'=>NewsReferencesResource::collection($this->NewsReferences)
        ];
    }
}
