<?php
namespace Modules\Magazines\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cities\Entities\City;
use Modules\Magazines\Entities\Mag_Section;
use Modules\Magazines\Entities\Magazin_Alboum_Images;
use Modules\Magazines\Entities\Magazin_gallary;
use Modules\Magazines\Entities\Magazin_magazines;
use Modules\Magazines\Entities\Magazin_Social_media;
use Modules\Magazines\Entities\Magazine_address;
use Modules\Magazines\Entities\Magazine_Rate;
use Modules\Magazines\Entities\Magazine_Sec;
use Modules\Magazines\Entities\Magazine;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Illuminate\Support\Facades\Input;
use App\Social;
use Session;
use Image;
use File;
use View;
use Auth;

class MagazineFrontController extends Controller
{


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function magazines(Request $request, $id)
    {
        $city  = Input::get("city");

        if(is_null($city))
        {
                
            $section = Mag_Section::with('Magazine')->where('id',$id)->first();

            $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','magazines')->pluck('ads_id')->toArray();

            $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
            $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

            $majs = Magazine_Sec::where('section_id' , $id)->pluck('maga_id')->toArray();
            $magazines = Magazine::whereIn('id',$majs)->orderby('name')->paginate(10);
            $secs = Mag_Section::get();
            $cities = City::with('Magazine')->get();
            $city= null;

        }elseif(!is_null($city)){

            
            $section = Mag_Section::with('Magazine')->where('id',$id)->first();

            $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','magazines')->pluck('ads_id')->toArray();
    
            $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
            $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
    
            $majs = Magazine_Sec::where('section_id' , $id)->pluck('maga_id')->toArray();
            $magazines = Magazine::whereIn('id',$majs)->where('city_id',$city)->orderby('name')->paginate(10);
            $secs = Mag_Section::get();
            $cities = City::with('Magazine')->get();
            $city= $city;
        
        }
        $sort= '0';


        return view('magazines::fronts.magazines',compact('magazines','section','secs','sort','cities','ads','logos','city'));
    }

    # sub sections sort rate
    public function sortmagazinesrate(Request $request, $id)
    {

        $section = Mag_Section::with('Magazine')->where('id',$id)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','magazines')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $majs = Magazine_Sec::where('section_id' , $id)->pluck('maga_id')->toArray();
        $magazines = Magazine::whereIn('id',$majs)->orderby('rate' , 'desc')->paginate(10);
        $secs = Mag_Section::get();
        $cities = City::with('Magazine')->get();
        $sort= '1';
       
        if ($request->ajax()) {

    		$view = view('magazines::fronts.data',compact('magazines','section','secs','sort','cities','ads','logos'))->render();

            return response()->json(['html'=>$view]);

        }
        return view('magazines::fronts.magazines',compact('magazines','section','secs','sort','cities','ads','logos'));
    }

    # sub sections sort city
    public function sortmagazinescity(Request $request, $id)
    {

        $section = Mag_Section::with('Magazine')->where('id','1')->first();

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','magazines')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $magazines = Magazine::where('city_id',$id)->orderby('name')->paginate(10);
        $secs = Mag_Section::get();
        $cities = City::with('Magazine')->get();
        $city= $id;
        
        if ($request->ajax()) {

            $view = view('magazines::fronts.data',compact('magazines','section','secs','city','cities','ads','logos'))->render();

            return response()->json(['html'=>$view]);

        }
        return view('magazines::fronts.magazines',compact('magazines','section','secs','city','cities','ads','logos'));
    }


    # Magazine
    public function Magazine($id)
    {
        $magazines = Magazine::with('MagazineAlboumImages','Magazingallary.MagazineAlboumImages','Magazineaddress','MagazinSocialmedia.Social','Magazinguide','MagazineRate')->where('id',$id)->first();
        $phone     = $magazines->phones;
        $phones    = json_decode($phone);
        $email     = $magazines->emails;
        $emails    = json_decode($email);
        $mobile    = $magazines->mobiles;
        $mobiles   = json_decode($mobile);
        $fax    = $magazines->faxs;
        $faxs   = json_decode($fax);
        $social    = Social::with('MagazinSocialmedia')->latest()->get();
        $cities = City::get();
        if(Auth::guard('customer')->user())
        {
            $rating = Magazine_Rate::with('Magazine')->where('user_id',Auth::guard('customer')->user()->id)->where('maga_id',$magazines->id)->first();
            return view('magazines::fronts.magazine',compact('magazines','faxs','phones','emails','cities','mobiles','social','rating'));
        }
        return view('magazines::fronts.magazine',compact('magazines','phones','faxs','emails','cities','mobiles','social'));
    }

    # get magazines ajax
    public function Getmagazines(Request $request)
    {
        $majs = Magazine_Sec::where('section_id' , $request->id)->pluck('maga_id')->toArray();
        $datas = Magazine::with('sections')->whereIn('id',$majs)->get();
        $seco = Mag_Section::with('Magazine')->where('id',$request->id)->first();

        return response()->json(['datas' => $datas ,'seco' => $seco,], 200);
    }

      # get magazines ajax
      public function GetmagazinesByName(Request $request)
      {
          if($request->search != null){
              $datas = Magazine::with('sections')->where('name' , 'like' , "%". $request->search ."%")->take(50)->latest()->get();
          }
          else{
              $majs = Magazine_Sec::where('section_id' , $request->section_id)->pluck('maga_id')->toArray();
              $datas = Magazine::whereIn('id',$majs)->orderby('name')->take(50)->latest()->get();
          }
  
          return response()->json(['datas' => $datas ,], 200);
      }

    # get magazines ajax
    public function Getrating(Request $request)
    {
        
        if($request->id)
        {   

            $majs = Magazine_Sec::where('section_id' , $request->id)->pluck('maga_id')->toArray();
            if($request->sort == "0"){
                $datas =  Magazine::with('sections')->whereIn('id',$majs)->orderby('name')->get();
            }else{
                $datas = Magazine::with('sections')->whereIn('id',$majs)->orderby('rate' , 'desc')->get();
            }
           
            $seco = Mag_Section::with('Magazine')->where('id',$request->id)->first();
            return response()->json(['datas' => $datas ,'seco' => $seco,], 200);

        }else{

            if($request->sort == "0"){
                $datas =  Magazine::with('sections')->orderby('name')->get();
            }else{
                $datas = Magazine::with('sections')->orderBy('rate' , 'desc')->get();
            }
           
        }
        return response()->json(['datas' => $datas ], 200);
    }

    # add rating
    public function rating(Request $request)
    {
        $rating = Magazine_Rate::with('Magazine')->where('user_id',Auth::guard('customer')->user()->id)->where('maga_id',$request->maga_id)->first();
//        $rating = Magazine_Rate::firstOrNew(['user_id' => Auth::guard('customer')->user()->id,'maga_id'=>$request->maga_id]);
        if($rating){
            $rating->rate       = $request->reat;
            $rating->save();
        }else{
            $rating = new Magazine_Rate;
            $rating->rate       = $request->reat;
            $rating->maga_id       = $request->maga_id;
            $rating->user_id       = Auth::guard('customer')->user()->id;
            $rating->save();
        }
        $Magazine = Magazine::findOrFail($request->maga_id);
        $Magazine->rate =  Magazine_Rate::where('maga_id' , $request->maga_id)->avg('rate');
        $Magazine->save();
//        $rating = new Magazine_Rate;
//        $rating->rate       = $request->reat;
//        $rating->maga_id       = $request->maga_id;
//        $rating->user_id       = Auth::guard('customer')->user()->id;
//        $rating->save();
//
//        $Magazine = Magazine::findOrFail($request->maga_id);
//        $Magazine->rate =  Magazine_Rate::where('maga_id' , $request->maga_id)->avg('rate');
//        $Magazine->save();
//
        return $rating;
    }

    # add rating
    public function updaterating(Request $request)
    {
  
        $rating = Magazine_Rate::with('Magazine')->where('user_id',Auth::guard('customer')->user()->id)->where('maga_id',$request->maga_id)->first();
        $rating->rate       = $request->reat;
        $rating->save();

        $Magazine = Magazine::findOrFail($request->maga_id);
        $Magazine->rate =  Magazine_Rate::where('maga_id' , $request->maga_id)->avg('rate');
        $Magazine->save();

        return $rating;
    }
    

    public function custmerRate($magazine_id)
    {
       $magazin = Magazine::with('MagazineRate')->where('id',$magazine_id)->first();
        return count($magazin->MagazineRate);
    }

    public function getRateOfMagazin($magazin_id)
    {
        $magazin = Magazine::where('id',$magazin_id)->first();
        $rate = $magazin ? $magazin->rate : '0';
        return response()->json($rate);
    }
}
 
 