<?php

namespace Modules\SystemAds\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class LogoBannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $arrayData = [];

        if($this->type == 'popup') {
            $arrayData[] = [
                'id' => $this->id,
                'link' => $this->link,
                'media' => $this->image_url,
            ];
        }else{
            $arrayData = [
                'id'=>$this->id,
                'link'=>$this->link,
                'image'=>$this->image_url,
                'company_id'=> $this->company_id,
                'company_name' => $this->Company ? $this->Company->name : null,
            ];
            $temp = strstr( $this->link, 'elkenany.com');
                if($temp){
                    $arrayData['type'] = 'internal';
                }else{
                    $arrayData['type'] = 'external';
                }


        }
        return $arrayData;
    }
}
