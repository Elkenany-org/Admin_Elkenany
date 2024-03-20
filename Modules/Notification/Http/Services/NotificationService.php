<?php

namespace Modules\Notification\Http\Services;

use App\Configuration;
use App\Noty;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Modules\Guide\Entities\Company;
use Modules\LocalStock\Entities\Local_Stock_Detials;
use Modules\Notification\Entities\Notification;
use Modules\Store\Entities\Customer;

class NotificationService
{
    use ApiResponse;

    public $access_key;
    public $fcms;
    public $regIdChunk;
    public function __construct()
    {
        $this->access_key = Configuration::first()->fcm_server_key;
        $this->fcms = Customer::where('device_token','!=',null)->Where('device_token', 'like', '%:%')->get()->unique('device_token')->pluck('device_token')->toArray();
        $this->regIdChunk = array_chunk($this->fcms, 100);

        }

    public function storeNotification(Request $request): Notification
    {
        $data = $request['notification'];
        if(count($data) > 1){
            unset($data[0]);
        }
        foreach ($data as $k => $value)
        {
            $notification = new Notification();
            $notification->duration_type = $request->duration_type;
            $notification->title         = $value['title'];
            $notification->body          = $value['body'];
            $notification->date_at       = $value['date_at'] != null ? $value['date_at'] : date('Y-m-d');
            $notification->time_at       = $value['time_at'] != null ? $value['time_at'] : date('H:i');
            $notification->status        = $request->duration_type == 'now' ?  1 : 0;
            if($request->has('company_id')){
                $notification->company_id = $request->company_id;
            }
            if(isset($value['image'])){
                $notification->image = $this->storeImage($value['image'],'notifications');
            }
            $notification->save();
            MakeReport('بإضافة إشعار '.$notification->title);
        }
        return $notification;

    }

    /**
     * @param $event
     * @return void
     * listen to function from event
     */
    public function sendNotificastion($event)
    {
//        dd($event);
        $model_name = key($event);
        $attributes = $event->$model_name;
        $notification = new Noty();
        $notification->model_id = $attributes->id;

//        dd( $notification);
        if(str_contains(get_class($event),'Magazin')){
            $notification->title =  'مجله جديده: '.$attributes->name;
            $notification->desc = $attributes->short_desc;
            $notification->model_name = 'Modules\\Magazines\\Entities\\Magazine';
            $notification->image = $attributes->image_thum_url;
        }
        if(str_contains(get_class($event),'News')){
            $notification->title =  'خبر جديد: '.$attributes->title;
            $notification->desc = $attributes->title;
            $notification->model_name = 'Modules\\News\\Entities\\News';
            $notification->image = $attributes->image_thum_url;
        }
        if(str_contains(get_class($event),'CompanyProduct')){
            $comapny = Company::find($attributes->company_id);
            $notification->title =  'منتج جديد لشركة: '.$comapny->name;
            $notification->desc = ' لشركه '.$attributes->name.'تم إضافه منتج '.$comapny->name;
            $notification->model_name = 'Modules\\Guide\\Entities\\Company_product';
            $notification->image = $attributes->image_url;
            $notification->company_id = $attributes->company_id;
        }
//        if(str_contains(get_class($event),'LocalStockChanges')){
//            $local = Local_Stock_Detials::with('LocalStockMember.LocalStockproducts','Section')->where('id',$attributes->id)->first();
//
//            $name = $local->LocalStockMember ? $local->LocalStockMember->LocalStockproducts->name : '';
//            $section_name = $local->Section ? $local->Section->name : '';
//            $notification->title =  'تم تحديث '.$section_name;
//            $notification->desc = 'تم تغير سعر '.$name;
//            $notification->model_name = 'Modules\\LocalStock\\Entities\\Local_Stock_Detials';
//            $notification->image = '';
//        }
        if(str_contains(get_class($event),'Show')){
            $notification->title =  'معرض جديد: '.$attributes->name;
            $notification->desc = $attributes->desc;
            $notification->model_name = 'Modules\\Shows\\Entities\\Show';
            $notification->image = $attributes->image_thum_url;
        }
        if(str_contains(get_class($event),'Company')){
            $notification->title =  'شركه جديده: '.$attributes->name;
            $notification->desc = $attributes->short_desc;
            $notification->model_name = 'Modules\\Guide\\Entities\\Company';
            $notification->image = $attributes->image_thum_url;
        }
        $notification->save();
        $this->sendFcm($notification->title,$notification->desc,$notification->image);
    }

    /**
     * @param $title
     * @param $desc
     * @param $image
     * @return bool|\Exception|Exeption|string|void
     */


    public function sendFcm($title,$desc,$image = null)
    {
        define('API_ACCESS_KEY', $this->access_key);
        $url = 'https://fcm.googleapis.com/fcm/send';

        $msg = array(
            'title' => $title,
            'body' => $desc,
            'vibrate' => 1,
            'sound' => 1,
            'image' => $image,

        );


        foreach ($this->regIdChunk as $RegId) {
//            dump($RegId);
            $fields = array(
                'registration_ids' => $RegId,
                'data' => $msg,
                "topic" => "elkenany",
                'notification' => $msg,

            );
            $headers = array(
                'Authorization: key=' . API_ACCESS_KEY,
                'Content-type: Application/json'
            );
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
//            info('==> Result ==> '.json_encode($result));
                if ($result === FALSE) {
                    var_dump('false');
                    die('Oops! FCM Send Error: ' . curl_error($ch));
                }

                curl_close($ch);
//            info('curl_close($ch) ******> '.json_encode(curl_close($ch)));
            } catch (Exeption $e) {
//            info('==> exception ==> '.json_encode($e));
                return $e;
            }
        }
//        info('*********> '+json_decode($result));

            return $result;
        }



}

