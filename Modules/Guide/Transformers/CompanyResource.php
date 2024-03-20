<?php

namespace Modules\Guide\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CompanyResource extends JsonResource
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
            'id'      => $this->id,
            'name'    => $this->name,
            'rate'    => $this->rate,
            'image'   => URL::to('uploads/company/images/'.$this->image),
            'desc'    => Str::limit($this->short_desc, 60, '...'),
            'address' => $this->address,
        ];
    }
}
