<?php

namespace Modules\Us\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Us\Entities\Contuct;
use Modules\Us\Entities\Office;
use App\Setting;
use Session;
use Image;
use File;
use View;

class UsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $contuct = Contuct::get();
        return view('us::contuct.contuct',compact('contuct'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function Delete(Request $request)
    {
        $contuct = Contuct::where('id',$request->id)->first();
        Session::flash('success','تم الحذف');
        MakeReport('بحذف رسالة '.$contuct->name);
        $contuct->delete();
        return back();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getcontuct()
    {
        $setting = Setting::first();
        $main = Office::where('status',0)->first();
        $all = Office::get();
        return view('us::fronts.contuct',compact('setting','main','all'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contuct(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'email'        => 'required',
            'company'      => 'required',
            'phone'        => 'required',
            'desc'         => 'required',
            'job'          => 'required',
        ]);
    
        $contuct = new Contuct;
        $contuct->name         = $request->name;
        $contuct->email        = $request->email;
        $contuct->company      = $request->company;
        $contuct->phone        = $request->phone;
        $contuct->desc         = $request->desc;
        $contuct->job          = $request->job;
        $contuct->save();

        Session::flash('success','تم الارسال');
        return back();
    }

    public function about()
    {
        $setting = Setting::first();
        return view('us::fronts.about',compact('setting'));
    }

    public function terms()
    {
        $setting = Setting::first();
        return view('us::fronts.terms',compact('setting'));
    }

    public function privacy()
    {
        $setting = Setting::first();
        return view('us::fronts.privacy',compact('setting'));
    }

  
}
