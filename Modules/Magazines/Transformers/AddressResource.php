<?php

namespace Modules\Magazines\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'key'=>'address',
            'address' =>$this->address,
            'latitude' =>$this->latitude,
            'longitude' =>$this->longitude,
        ];
    }
}
