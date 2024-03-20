<?php

namespace Modules\Magazines\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class GalleryResource extends JsonResource
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
            'image'=>URL::to('uploads/gallary/avatar/'.$this->image),
            'name' =>$this->name,
            'id'   =>$this->id,
        ];
    }
}
