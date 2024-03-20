<?php

namespace Modules\Guide\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Date;
use Modules\Cities\Entities\City;
use Modules\Guide\Entities\Company_transport;
use Modules\FodderStock\Entities\Fodder_Stock;

class CompanyGuideResource extends JsonResource
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
        $phones    = json_decode($this->phones);
        $emails    = json_decode($this->emails);
        $mobiles   = json_decode($this->mobiles);
        $faxs   = json_decode($this->faxs);

        $data = [
                'id'          => $this->id,
                'name'        => $this->name,
                'short_desc'  => $this->short_desc,
                'about'       => $this->about,
                'address'     => $this->address,
                'latitude'    => $this->latitude,
                'longitude'   => $this->longitude,
                'rate'        => $this->rate,
                'count_rate'  => count($this->CompanyRates),
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
            if(count($emails) > 0 && $emails[0] !== null)
            {
                foreach ($emails as $k => $em)
                {
                    $data['contact_list'][] =   [
                        'key'=>'email',
                        'email'=>$em
                    ];

                }
            }

            if(count($mobiles) > 0 && $mobiles[0] !== null)
            {
                foreach ($mobiles as $ke => $mo)
                {
                    $data['contact_list'][] =   [
                        'key'=>'mobile',
                        'mobile'=>$mo
                    ];
                }
            }

            if(count($faxs) > 0 && $faxs[0] !== null)
            {
                foreach ($faxs as $kx => $fa)
                {
                    $data['contact_list'][] =   [
                        'key'=>'fax',
                        'fax'=>$fa
                    ];
                }
            }

        foreach ($this->CompanySocialmedia as $kk => $social)
        {
            $data['contact_list'][] =   [
                'key'=>'social',
                'social_id'=>$social->id,
                'social_link'=>$social->social_link,
                'social_name'=>$social->Social->social_name,
                'social_icon'=>URL::to($social->Social->social_icon),
            ];
        }
        
        foreach ($this->Companyaddress as $kr => $value)
        {
            $data['contact_list'][] = [
                'key'=>'address',
                'address' =>$value->address,
                'latitude' =>$value->latitude,
                'longitude' =>$value->longitude,
            ];
        }
        $data['gallary'] = [];
        foreach ($this->Companygallary as $K => $value)
        {
            $data['gallary'][] = [
                'image'=>URL::to('uploads/gallary/avatar/'.$value->image),
                'name'=>$value->name,
                'id'=>$value->id,
            ];
        }
        $data['products'] = [];
        foreach ($this->Companyproduct as $Kpd => $value)
        {
            $data['products'][] = [
                'image'=>URL::to('uploads/company/product/'.$value->image),
                'name'=>$value->name,
            ];
        }
        $data['localstock'] = [];
        foreach ($this->LocalStockMember as $Kll => $value)
        {
            $data['localstock'][] = [
                'image'=>URL::to('uploads/sections/sub/'.$value->Section->image),
                'name'=>$value->Section->name,
                'id'=>$value->Section->id,
            ];
        }
        $data['cities'] = City::get(['id','name']);

        $stocks    = Fodder_Stock::where('company_id',$this->id)->with('subSection')->latest()->get()->unique('company_id');
        $transports = Company_transport::where('company_id',$this->id)->with('City')->where('city_id','1')->latest()->get();

        $data['fodderstock'] = [];
        foreach ($stocks as $Kf => $value)
        {
            $data['fodderstock'][] = [
                'image'=>URL::to('uploads/sections/avatar/'.$value->subSection->image),
                'name'=>$value->subSection->name,
                'id'=>$value->subSection->id,
            ];
        }

        $data['transports'] = [];
        foreach ($transports as $kt => $value)
        {
            if($value->product_type == "0"){
                $type  = 'تكلفة نقل الكتكوت';
            }else{
                $type  = 'تكلفة نقل العلف';
            }
            $data['transports'][] = [
                        'price'=>$value->price,
                        'name'=>$value->product_name,
                        'city'=>$value->City->name,
                        'type'=>$type
                    ];

        }

        return $data;
    }
}
