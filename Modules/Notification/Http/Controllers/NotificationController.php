<?php

namespace Modules\Notification\Http\Controllers;

use App\Noty;
use App\Traits\ApiResponse;
use App\Traits\SearchReg;
use http\Env\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Guide\Entities\Company;
use Modules\Notification\Entities\Notification;
use Modules\Notification\Http\Services\NotificationService;
use Session;
use Symfony\Component\Config\Definition\Exception\Exception;

class NotificationController extends Controller
{
    use ApiResponse,SearchReg;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $notifications = Notification::with('company')->orderByDesc('date_at')->get();

        return view('notification::index',compact('notifications'));
    }

    public function index_not_scheduled()
    {
        $notifications = Noty::with('company')->latest()->get();
        return view('notification::index_not_scheduled',compact('notifications'));
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
    public function store(Request $request ,NotificationService $serviceNotify)
    {
        try{
            $serviceNotify->storeNotification($request);
        }catch (\Exception $e ){
            return \response()->json(['error'=>$e->getMessage()],422);
        }

        return redirect()->route('notification')->with(['success'=>'تم الحفظ']);
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
        $notification =  Notification::findOrFail($id);
        return view('notification::edit',compact('notification'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $requestData = $request->all();
        if($request->has('image')){
            $requestData['image'] = $this->storeImage($request->image,'notifications');
        }
        $notification->update($requestData);
        MakeReport('بتعديل الإشعار '.$notification->title);
        return redirect()->back()->with(['success'=>'تم التعديل']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        MakeReport('بحذف الإشعار '.$notification->title);
        return redirect()->back()->with(['success'=>'تم الحذف']);

    }

    public function delete_notification_notScheduled($id)
    {
        $notification = Noty::findOrFail($id);
        $notification->delete();
        MakeReport('بحذف الإشعار '.$notification->title);
        return redirect()->back()->with(['success'=>'تم الحذف']);
    }


    public function searchCompany(Request $request)
    {
        $keyword = $this->searchQuery($request->keyword);
        $company = Company::where('name','REGEXP',$keyword)->get();
        return response()->json($company);
    }
}
