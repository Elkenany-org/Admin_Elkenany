<?php

namespace Modules\Magazines\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Date;
use Illuminate\Support\Facades\URL;
use Modules\Magazines\Transformers\AddressResource;
use Modules\Magazines\Transformers\SocialResource;
use Modules\Magazines\Transformers\GalleryResource;

class MagazinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $phones    = json_decode($this->phones);
        $emails    = json_decode($this->emails);
        $mobiles   = json_decode($this->mobiles);
        $faxes   = json_decode($this->faxs);

        $data = [
            'id'          => $this->id,
            'name'        => $this->name,
            'short_desc'  => $this->short_desc,
            'about'       => $this->about,
            'address'     => $this->address,
            'latitude'    => $this->latitude,
            'longitude'   => $this->longitude,
            'rate'        => $this->rate,
            'count_rate'  => count($this->MagazineRate),
            'image'       => $this->image_url,
            'created_at'  => Date::parse($this->created_at)->diffForHumans(),
        ];

        $data['contact_list'] = [];
        if(count($phones) > 0 && $phones[0] !== null )
        {
            foreach ($phones as $key => $pho)
            {
                $data['contact_list'][] =   [
                    'key'=>'phone',
                    'phone'=>$pho
                ];
            }
        }

        if(count($emails) > 0 && $emails[0] != null)
        {
            foreach ($emails as $k => $em)
            {
                $data['contact_list'][] =   [
                    'key'=>'email',
                    'email'=>$em
                ];
            }
        }

        if(count($mobiles) > 0 && $mobiles[0] != null)
        {
            foreach ($mobiles as $ke => $mo)
            {
                $data['contact_list'][] =   [
                    'key'=>'mobile',
                    'mobile'=>$mo
                ];
            }
        }

        if(count($faxes) > 0 && $faxes[0] != null)
        {
            foreach ($faxes as $kx => $fa)
            {
                $data['contact_list'][] =   [
                    'key'=>'fax',
                    'fax'=>$fa
                ];
            }
        }

        foreach ($this->MagazinSocialmedia as $social)
        {
            $data['contact_list'][] =   new SocialResource($social);
        }


        foreach ($this->Magazineaddress as $value)
        {
            $data['contact_list'][] = new AddressResource($value);
        }

        $data['gallery'] = GalleryResource::collection($this->Magazingallary);


        $data['guides'] = [];
        foreach ($this->Magazinguide as $Kpd => $value)
        {
            $data['guides'][] = [
                'image'=>URL::to('uploads/magazine/guides/'.$value->image),
                'name' =>$value->name,
                'link'   =>$value->link,
            ];
        }


        return $data;
    }
}
