<?php

namespace Modules\Notification\Http\Controllers\Api;

use App\Main;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Noty;
use Modules\Store\Entities\Customer;

class NotificationController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $notifications = [];
        if($request->header('Authorization'))
        {
            $token = explode(' ',$request->header('Authorization'))[1];
            $customer = Customer::where('api_token',$token)->first();
            if(!$customer){
                return $this->ErrorMsg('TOKEN_IS_INVALID');
            }
//            $notifications = Noty::with('Company','Companyproduct')->get();

            $notifications = Noty::with('Company','Companyproduct')->where('created_at','>',$customer->created_at)->latest()->take(30)->get();
            if($request->hasHeader('device')){
                $notifications->map(function ($notification) {
                    $notification['time'] = $notification->created_at->diffForHumans();
                });
            }
//            if($request->hasHeader('android')){
//                $notifications->map(function ($notification){
//                foreach (config('notification.source_mobile') as $k => $v){
//                    if($notification->model_name != null){
//                        if($k == $notification->model_name && $v=="shows"){
//                            $section_id=$notification->model_name::where('id',$notification->model_id)->first();
//                            if($section_id){
//                                $section_id=$section_id->section_id;
//                                $notification['type']= Main::where('id',$section_id)->first()->type;
//                            }
//                        }
//                    }
//
//                }
//                return $notification;
//            });
//            }

            $notifications->map(function ($notification){
                foreach (config('notification.source_mobile') as $k => $v){

                    if($notification->model_name != null){
                        if($k == $notification->model_name){
                            $notification['key_name'] = $v;
                            $notification['key_id'] = $notification->model_id;
                        }
                    } else{
                        if($notification->pro_id != null){
                            $notification['image'] = $notification->Companyproduct->image_url;
                            $notification['key_id'] = $notification->Companyproduct->id;
                            $notification['key_name'] = 'new_product';
                        }elseif ($notification->company_id != null){
                            $notification['key_id'] = $notification->Company->id;
                            $notification['key_name'] = 'companies';
                            $notification['image'] = $notification->Company->image_url;
                        }
                    }


                }
                return $notification;
            });

            $notifications->makeHidden(['updated_at', 'company_id','pro_id','model_name','model_id','Company','Companyproduct']);
        }
        return $this->ReturnData(['result'=>$notifications]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('notification::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('notification::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('notification::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
