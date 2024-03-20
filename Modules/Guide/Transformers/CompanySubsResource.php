<?php

namespace Modules\Guide\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class CompanySubsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        $data = [
           'id'          => $this->id,
           'name'        => $this->name,
           'image'       => URL::to('uploads/sections/avatar/'.$this->image),
           'companies_count'     => count($this->Company),
        ];
        $data['logo_in'] = [];
        foreach ($this->logooos() as $looo)
        {
            $data['logo_in'][] = [
                'id'=>$looo->id,
                'link'=>$looo->link,
                'image'=>URL::to('uploads/full_images/'.$looo->image),
            ];
        }
        return $data;
    }
}
