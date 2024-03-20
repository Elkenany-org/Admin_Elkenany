<?php

namespace Modules\Store\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class RecommendiationStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $data = [
            'id'          => $this->id,
            'name'        => $this->title,
            'type'        => 'store',
            'sector_type' => $this->StoreSection ? $this->StoreSection->name : '',
        ];

        if(count($this->StoreAdsimages) > 0){
            $data['image']       = URL::to('uploads/stores/alboum/'.$this->StoreAdsimages->first()->image);
        }
        return $data;

    }
}
