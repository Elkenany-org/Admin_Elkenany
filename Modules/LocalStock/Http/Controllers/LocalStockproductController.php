<?php

namespace Modules\LocalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\LocalStock\Entities\Local_Stock_product;
use Illuminate\Routing\Controller;
use Session;
use Image;
use File;

class LocalStockproductController extends Controller
{
    # index
    public function index()
    {
        $products = Local_Stock_product::latest()->get();
        return view('localstock::products.products',compact('products'));
    }

    # add product
    public function Storeproduct(Request $request)
    {
        $request->validate([
//            'name'         => 'required',
            'names'         => 'required',
            'image'        => 'mimes:jpeg,png,jpg,gif,svg',
        ]);
        $products_name = $request->input('names');
        $products_name = preg_split("/\r\n|\n|\r/", $products_name);

        foreach($products_name as $name){
        $product             = new Local_Stock_product;
        $product->name       = $name;
        $product->save();
        }
//        if(!is_null($request->image))
//        {
//            $photo=$request->image;
//            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
//            Image::make($photo)->save('uploads/products/avatar/'.$name);
//            $product->image =$name;
//        }
//        $product->save();


        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة المنتجات ');
        return back();
    }


    # update product
    public function Updateproduct(Request $request)
    {
        $request->validate([
            'edit_id'          => 'required',
            'edit_name'        => 'required',
            'edit_image'       => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        $product = Local_Stock_product::findOrFail($request->edit_id);
        $product->name       = $request->edit_name;

        if(!is_null($request->edit_image))
        {

            File::delete('uploads/products/avatar/'.$product->image);
            $photo=$request->edit_image;
           
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/products/avatar/'.$name);
            $product->image =$name;
        }
        $product->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث منتج  '.$product->name);
        return back();
    }

    # delete product
    public function Deleteproducts(Request $request)
    {
        $request->validate([
            'id'        => 'required'
        ]);

        $product = Local_Stock_product::where('id',$request->id)->first();
        File::delete('uploads/products/avatar/'.$product->image);
        MakeReport('بحذف  منتج '.$product->name);
        $product->delete();
        Session::flash('success','تم الحذف');
        return back();
    }


}
