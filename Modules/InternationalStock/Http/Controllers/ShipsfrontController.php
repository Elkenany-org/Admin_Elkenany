<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\InternationalStock\Entities\Ports;
use Modules\InternationalStock\Entities\Ships_Product;
use Modules\InternationalStock\Entities\Ships;
use Modules\Guide\Entities\Company;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Session;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Auth;
class ShipsfrontController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        $date  = $request->input("date");

        $page = System_Ads_Pages::with('SystemAds')->where('type','ships')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
        if(is_null($date))
        {
            $ships = Ships::with('Company','ShipsProduct','Ports')->latest()->paginate();
            $dates = Ships::latest()->get()->unique('date');
        }else{

            if(Auth::guard('customer')->user()){
                if(Auth::guard('customer')->user()->memb == '1')
                {
                 
                    $ships = Ships::with('Company','ShipsProduct','Ports')->where('date',$date)->latest()->paginate();
                    $dates = Ships::latest()->get()->unique('date');
                }else{
        
                    if($date < Carbon::now()->subDays(7)){
                        Session::flash('danger',' ليست لديك الصلاحية');
                        return back();
                    }else{
                     
                        $ships = Ships::with('Company','ShipsProduct','Ports')->where('date',$date)->latest()->paginate();
                        $dates = Ships::latest()->get()->unique('date');
                    }
                  
        
                }
            }else{
                if($date < Carbon::now()->subDays(7)){
                    Session::flash('danger',' ليست لديك الصلاحية');
                    return back();
                }else{
                 
                    $ships = Ships::with('Company','ShipsProduct','Ports')->where('date',$date)->latest()->paginate();
                    $dates = Ships::latest()->get()->unique('date');
                }
            }
          

          
        }
        if ($request->ajax()) {
            $view = view('internationalstock::fronts.data',compact('ships'))->render();
            return response()->json(['html'=>$view]);
        }
    	return view('internationalstock::fronts.ships',compact('ships','dates','date','ads','logos'));
    }

    # show statistic drop
    public function statistic(Request $request)
    {
        $from  = $request->input("from");
        $to    = $request->input("to");
        $kind    = $request->input("kind");
        $country    = $request->input("country");


        if(Auth::guard('customer')->user() && Auth::guard('customer')->user()->memb != 1){
            if($from && $from < Carbon::now()->subDays(7)) {
                return redirect()->back()->with(['danger' => ' ليست لديك الصلاحية']);
            }
        }else{
            if($from && $from < Carbon::now()->subDays(7) && !Auth::guard('customer')->user()){
                return redirect()->back()->with(['danger'=>' ليست لديك الصلاحية']);
            }
        }

        $products = Ships_Product::with('Ships')->latest()->get();
        $products->map(function ($product) use ($kind){
            $product['selected'] = $kind ? $kind == $product->id ? 1 : 0 : 0;
            return $product;
        });

        $countries = Ships::latest()->get()->unique('country');
        $countries->map(function ($cont) use ($country){
            $cont['selected'] = $country ? $country == $cont->country ? 1 : 0 : 0;
            return $cont;
        });


        $ships = Ships::with('Company','ShipsProduct.Ships','Ports');

            if($country){
                $ships->where('country',$country);
            }
            if($from){
                $ships->whereBetween( 'date', [$from, $to]);
            }
            if($kind){
                $ships->where('product_id',$kind);
            }
        $ships = $ships->latest()->get()->unique('country');

        return view('internationalstock::fronts.changes',compact('ships','products','countries'));
    }

     # show statistic drop
     public function detials(Request $request, $id)
     {
     $from  = $request->input("from");
     $to    = $request->input("to");
     $country    = $request->input("country");



         if(Auth::guard('customer')->user() && Auth::guard('customer')->user()->memb != 1){
             if($from && $from < Carbon::now()->subDays(7)) {
                 return redirect()->back()->with(['danger' => ' ليست لديك الصلاحية']);
             }
         }else{
             if($from && $from < Carbon::now()->subDays(7) && !Auth::guard('customer')->user()){
                 return redirect()->back()->with(['danger'=>' ليست لديك الصلاحية']);
             }
         }


         $product = Ships_Product::with('Ships')->where('id',$id)->latest()->first();
         $countries = Ships::latest()->get()->unique('country');

         $ships = Ships::with('Company','ShipsProduct','Ports')->where('product_id',$id);
         if($from && $to){
             $ships->whereBetween( 'date', [$from, $to]);
         }
         if($country){
             $ships->where('country',$country);
         }
         $ships = $ships->latest()->get();

        $companies = Company::with('Ships')->whereHas('Ships',function ($q) use($id,$from,$to,$country){
            $q->where('product_id',$id);
            if($from && $to) {
                $q->whereBetween('date', [$from, $to]);
            }
            if($country){
                $q->where('country',$country);
            }
        })->get();

     return view('internationalstock::fronts.detials',compact('companies','ships','product','countries'));
     }


   
   
}
