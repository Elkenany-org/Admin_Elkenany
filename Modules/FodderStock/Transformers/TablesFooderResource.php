<?php

namespace Modules\FodderStock\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Date;
class TablesFooderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $arrayData =  [
          'name'=>$this->Company->name,
          'comp_id'=>$this->Company->id,
          'price'=>$this->price,
          $this->mergeWhen($request->hasHeader('device'), [
             'image' => $this->Company->image_url,
          ]),
          'feed' => $this->StockFeed->name,
          'mem_id' => $this->StockFeed->id,
          'change'=>(string) round($this->change, 2),
          'type'=>$this->ranking($this->sub_id),
        ];

        if($request->hasHeader('android')){
            $arrayData['change_date'] = Date::parse($this->created_at)->format('Y-m-d');

            }else{
            $arrayData['change_date'] = Date::parse($this->created_at)->format('H:i / Y-m-d');
            }
        if($this->status === 'up' ){
            $arrayData['statistics'] = URL::to('https://admin.elkenany.com/uploads/full_images/arrows3-01.png');
        }
        if($this->status === 'down' ){
            $arrayData['statistics'] = URL::to('https://admin.elkenany.com/uploads/full_images/arrows3-02.png');
        }
        if($this->status === 'equal' ){
            $arrayData['statistics'] = URL::to('https://admin.elkenany.com/uploads/full_images/arrows3-03.png');
        }

        return $arrayData;
    }
}
