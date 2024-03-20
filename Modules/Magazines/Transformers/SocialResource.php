<?php

namespace Modules\Magazines\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class SocialResource extends JsonResource
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
            'key'=>'social',
            'social_id'=>$this->id,
            'social_link'=>$this->social_link,
            'social_name'=>$this->Social->social_name,
            'social_icon'=>URL::to($this->Social->social_icon),
        ];
    }
}
