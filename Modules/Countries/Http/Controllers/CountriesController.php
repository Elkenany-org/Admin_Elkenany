<?php

namespace Modules\Countries\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Countries\Entities\Country;
use Session;

class CountriesController extends Controller
{
    public function index()
    {
        $countries = Country::latest()->get();
    	return view('countries::countries.countries',compact('countries'));
    }

    # add countries
    public function Store(Request $request)
    {
        $request->validate([
            'name'         => 'required',

        ]);

        $countries = new Country;
        $countries->name       = $request->name;
        $countries->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة  دولة '.$countries->name);
        return back();
    }

    # update countries
    public function Update(Request $request)
    {
        $request->validate([
            'edit_id'          => 'required',
            'edit_name'        => 'required',

        ]);

        $countries = Country::findOrFail($request->edit_id);
        $countries->name      = $request->edit_name;

    
        $countries->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  دولة '.$countries->name);
        return back();
    }
 
    # delete countries
    public function Delete(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $countries = Country::where('id',$request->id)->first();
        
        MakeReport('بحذف دولة '.$countries->name);
        $countries->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
}
