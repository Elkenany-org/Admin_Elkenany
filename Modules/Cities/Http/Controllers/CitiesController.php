<?php

namespace Modules\Cities\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cities\Entities\City;
use Modules\Countries\Entities\Country;
use Session;


class CitiesController extends Controller
{
    public function index()
    {
        $cities = City::with('Country')->latest()->get();
        $countries = Country::latest()->get();
    	return view('cities::cities.cities',compact('cities','countries'));
    }

    # add cities
    public function Store(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'country_id'   => 'required',

        ]);

        $cities = new City;
        $cities->name       = $request->name;
        $cities->country_id = $request->country_id;
        $cities->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة  محافظة '.$cities->name);
        return back();
    }

    # update cities
    public function Update(Request $request)
    {
        $request->validate([
            'edit_id'          => 'required',
            'edit_name'        => 'required',

        ]);

        $cities = City::findOrFail($request->edit_id);
        $cities->name      = $request->edit_name;
        $cities->country_id = $request->edit_country_id;

    
        $cities->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  محافظة '.$cities->name);
        return back();
    }
 
    # delete cities
    public function Delete(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $cities = City::where('id',$request->id)->first();
        
        MakeReport('بحذف محافظة '.$cities->name);
        $cities->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
}
