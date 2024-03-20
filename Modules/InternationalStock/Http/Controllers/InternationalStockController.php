<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InternationalStock\Entities\Ports;
use Modules\InternationalStock\Entities\Ships_Product;
use Session;

class InternationalStockController extends Controller
{
 
    public function index()
    {
        $ports = Ports::latest()->get();
    	return view('internationalstock::ports.ports',compact('ports'));
    }

    # add ports
    public function Store(Request $request)
    {
        $request->validate([
            'name'         => 'required',

        ]);

        $ports = new Ports;
        $ports->name       = $request->name;
        $ports->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة  ميناء '.$ports->name);
        return back();
    }

    # update ports
    public function Update(Request $request)
    {
        $request->validate([
            'edit_id'          => 'required',
            'edit_name'        => 'required',

        ]);

        $ports = Ports::findOrFail($request->edit_id);
        $ports->name      = $request->edit_name;

    
        $ports->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  ميناء '.$ports->name);
        return back();
    }
 
    # delete ports
    public function Delete(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $ports = Ports::where('id',$request->id)->first();
        
        MakeReport('بحذف ميناء '.$ports->name);
        $ports->delete();
        Session::flash('success','تم الحذف');
        return back();
    }


    // products ships

    public function products()
    {
        $products = Ships_Product::latest()->get();
    	return view('internationalstock::products.products',compact('products'));
    }

    # add product
    public function Storeproduct(Request $request)
    {
        $request->validate([
            'name'         => 'required',

        ]);

        $products = new Ships_Product;
        $products->name       = $request->name;
        $products->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة  منتج '.$products->name);
        return back();
    }

    # update product
    public function Updateproduct(Request $request)
    {
        $request->validate([
            'edit_id'          => 'required',
            'edit_name'        => 'required',

        ]);

        $product = Ships_Product::findOrFail($request->edit_id);
        $product->name      = $request->edit_name;

    
        $product->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  منتج '.$product->name);
        return back();
    }
 
    # delete product
    public function Deleteproduct(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $product = Ships_Product::where('id',$request->id)->first();
        
        MakeReport('بحذف منتج '.$product->name);
        $ports->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
}
