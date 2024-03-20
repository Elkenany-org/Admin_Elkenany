<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InternationalStock\Entities\Ports;
use Modules\InternationalStock\Entities\Ships_Product;
use Modules\InternationalStock\Entities\Ships;
use Modules\Guide\Entities\Company;
use Session;

class ShipsController extends Controller
{
 
    public function index()
    {
        $ships = Ships::latest()->get();
    	return view('internationalstock::ships.ships',compact('ships'));
    }

    # add ships
    public function addships()
    {
        $ports = Ports::latest()->get();
        $products = Ships_Product::latest()->get();
        $companies = Company::latest()->get();
        return view('internationalstock::ships.add_ships',compact('ports','products','companies'));
    }

    # get search companies ajax
    public function Searchcompany(Request $request)
    {

        $datas = Company::where('name' , 'like' , "%". $request->search ."%")->take(20)->latest()->get();
        return $datas;
    }
 

    # add ships
    public function Storeships(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'load'         => 'required',
            'country'      => 'required',
            'date'         => 'required'
        ]);

        $ships             = new Ships;
        $ships->name       = $request->name;
        $ships->load       = $request->load;
        $ships->country    = $request->country;
        $ships->dir_date   = $request->dir_date;
        $ships->agent      = $request->agent;
        $ships->date       = $request->date;
        $ships->product_id = $request->product_id;
        $ships->company_id = $request->company_id;
        $ships->port_id    = $request->port_id;
       
        $ships->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة  حركة سفينة '.$ships->name);
        return back();
    }


    # edit ships
    public function Editships($id)
    {
        $ships = Ships::with('Company')->where('id',$id)->first();
        $ports = Ports::latest()->get();
        $products = Ships_Product::latest()->get();
        $companies = Company::latest()->get();
        
        return view('internationalstock::ships.edit_ships',compact('ships','ports','products','companies'));
          
    }

    # update ships
    public function Updateships(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'load'         => 'required',
            'country'      => 'required',
            'date'         => 'required'
        ]);

        $ships = Ships::where('id',$request->id)->first();

        $ships->name       = $request->name;
        $ships->load       = $request->load;
        $ships->country    = $request->country;
        $ships->date       = $request->date;
        $ships->dir_date   = $request->dir_date;
        $ships->agent      = $request->agent;
        $ships->product_id = $request->product_id;
        $ships->company_id = $request->company_id;
        $ships->port_id    = $request->port_id;
       
        $ships->save();
        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  حركة سفن '.$ships->name);
        return back();
    }

    # delete ships
    public function Deleteships(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $ships = Ships::where('id',$request->id)->first();
        
        MakeReport('بحذف حركة سفن '.$ships->name);
        $ships->delete();
        Session::flash('success','تم الحذف');
        return back();
    }



   
}
