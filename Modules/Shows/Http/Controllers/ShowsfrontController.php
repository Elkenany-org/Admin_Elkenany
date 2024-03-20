<?php

namespace Modules\Shows\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Shows\Entities\Show_Section;
use Modules\Shows\Entities\Organ;
use Modules\Shows\Entities\Show_Img;
use Modules\Shows\Entities\Show_Org;
use Modules\Shows\Entities\Show_Tac;
use Modules\Shows\Entities\Show;
use Modules\Shows\Entities\Showers;
use Modules\Shows\Entities\Speaker;
use Modules\Shows\Entities\Interested;
use Modules\Shows\Entities\Shows_Sec;
use Modules\Shows\Entities\Place;
use Modules\Shows\Entities\Show_Going;
use Modules\Shows\Entities\Show_Reat;
use Modules\Cities\Entities\City;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Modules\Countries\Entities\Country;
use Session;
use Auth;
use Image;
use File;
use View;

class ShowsfrontController extends Controller
{

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function shows($id)
    {
        $section = Show_Section::where('id',$id)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','shows')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $majs = Shows_Sec::where('section_id' , $id)->pluck('show_id')->toArray();

        $shows = Show::whereIn('id',$majs)->with('Section','City','Country')->orderby('name')->paginate(15);
        $countries = Country::get();

        $secs = Show_Section::get();

        $sort= '0';
        $cities = City::get();

        return view('shows::fronts.shows',compact('shows','cities','section','secs','sort','ads','logos','countries'));
    }

    # shows
    public function showssort(Request $request, $name)
    {

        $section = Show_Section::where('type',$name)->first();
        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','shows')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $majs = Shows_Sec::where('section_id' , $section->id)->pluck('show_id')->toArray();
        $shows = Show::whereIn('id',$majs)->with('Section','City','Country')->orderby('view_count' , 'desc')->paginate(15);
        $countries = Country::get();
        $secs = Show_Section::get();
        $cities = City::get();
        $sort= '1';
        return view('shows::fronts.shows',compact('shows','cities','section','secs','sort','ads','logos','countries'));
    }

    # shows
    public function showslast(Request $request, $name)
    {

        $section = Show_Section::where('type',$name)->first();
        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','shows')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $majs = Shows_Sec::where('section_id' , $section->id)->pluck('show_id')->toArray();

//        $shows = Show::whereIn('id',$majs)->with('Section','City','Country')->latest()->paginate(15);
//        $shows = Show::whereIn('id',$majs)->with('Section','City','Country')->orderByRaw('JSON_ARRAY(time) DESC')->paginate(15);
        $shows = Show::whereIn('id',$majs)->pluck('time','id');

        $array = json_decode($shows, true);
        arsort($array);
        $ids = [];
        foreach ($array as $key => $val){
            $ids[] = $key;
        }
        $shows = Show::whereIn('id',$ids)->whereIn('id',$majs)->with('Section','City','Country');
            if(count($ids) > 0){
                $shows->orderByRaw('FIELD(id,'.implode(", ",$ids).')');
            }
        $shows = $shows->paginate(15);
        $countries = Country::get();
        $secs = Show_Section::get();
        $cities = City::get();
        $sort= '2';
        return view('shows::fronts.shows',compact('shows','cities','section','secs','sort','ads','logos','countries'));
    }

    # sort city
    public function showssortcity(Request $request, $id)
    {

        $section = Show_Section::where('id','1')->first();
        $cis = City::where('id' , $id)->first();
        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','shows')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
        $countries = Country::get();
        $shows = Show::where('city_id',$id)->with('Section','City','Country')->orderby('name')->paginate(15);
        $secs = Show_Section::get();
        $cities = City::where('country_id' , $cis->country_id)->get();
        
        $city= $id;
        $Country= $cis->country_id;
    
        return view('shows::fronts.shows',compact('shows','cities','section','Country','secs','city','ads','logos','countries'));
    }

    # sort countries
    public function showssortcountries(Request $request, $id)
    {

        $section = Show_Section::where('id','1')->first();

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','shows')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
        $countries = Country::get();
        $shows = Show::where('country_id',$id)->with('Section','City','Country')->orderby('name')->paginate(15);
        $secs = Show_Section::get();
        $cities = City::where('country_id' , $id)->get();
        $Country= $id;
    
        return view('shows::fronts.shows',compact('shows','cities','section','secs','Country','ads','logos','countries'));
    }

    # datas
    public function searchByName(Request $request)
    {

        $datas = Show::with('Section','City',"Country")->where('name' , 'like' , "%". $request->search ."%")->take(50)->latest()->get();
    

        return response()->json(['datas'=>$datas,]);

    }

    # datas
    public function datas(Request $request)
    {

        $section = Show_Section::where('id',$request->id)->first();
        $majs = Shows_Sec::where('section_id' , $section->id)->pluck('show_id')->toArray();
        $limit = 5;
        $datas = Show::whereIn('id',$majs)->with('Section','City',"Country")->orderby('name')->take($limit)->get();
        $id = $request->id;
    

        return response()->json(['datas'=>$datas, 'limit'=>$limit, 'id'=>$id]);

    }

    # mores
    public function mores(Request $request)
    {

        $section = Show_Section::where('id',$request->id)->first();
        $majs = Shows_Sec::where('section_id' , $section->id)->pluck('show_id')->toArray();
        $count = $request->count;
        $limit = $count + 5;
        $datas = Show::whereIn('id',$majs)->with('Section','City',"Country")->orderby('name')->skip($count)->take(5)->get();
        $id = $request->id;
    

        return response()->json(['datas'=>$datas, 'limit'=>$limit, 'id'=>$id]);

    }

    # Show
    public function oneShow(Request $request, $id)
    {

        $show = Show::with('ShowImgs','ShowOrgs.Organ.ShowOrgs.Show','ShowTacs','Showers')->where('id',$id)->first();
        $show->view_count = $show->view_count + 1;
        $show->save();

        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('type','shows')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        if(Auth::guard('customer')->user())
        {
            $rating = Show_Going::where('show_id',$show->id)->where('user_id', Auth::guard('customer')->user()->id)->first();
            $inter = Interested::where('show_id',$show->id)->where('user_id', Auth::guard('customer')->user()->id)->first();
            return view('shows::fronts.show',compact('show','rating','inter','ads','logos'));
        }
        return view('shows::fronts.show',compact('show','ads','logos'));
    }

    # Show
    public function oneShowrev(Request $request, $id)
    {

        $show = Show::with('ShowImgs','ShowOrgs.Organ.ShowOrgs.Show','ShowTacs','ShowReats')->where('id',$id)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('type','shows')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
     
        return view('shows::fronts.review',compact('show','ads','logos'));
    }


    # Showers
    public function Showers(Request $request, $id)
    {

        $show = Show::with('ShowImgs','ShowOrgs.Organ.ShowOrgs.Show','ShowTacs','Showers')->where('id',$id)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('type','shows')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
    
        return view('shows::fronts.showers',compact('show','ads','logos'));
    }

    # speakers
    public function speakers(Request $request, $id)
    {

        $show = Show::with('ShowImgs','ShowOrgs.Organ.ShowOrgs.Show','ShowTacs','Speakers')->where('id',$id)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('type','shows')->pluck('ads_id')->toArray();

        $ads = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
    
        return view('shows::fronts.speakers',compact('show','ads','logos'));
    }


    # add place
    public function place(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'email'        => 'required',
            'company'      => 'required',
            'phone'        => 'required',
            'desc'         => 'required',
        ]);
     
        $place = new Place;
        $place->name         = $request->name;
        $place->email        = $request->email;
        $place->company      = $request->company;
        $place->phone        = $request->phone;
        $place->desc         = $request->desc;
        $place->show_id      = $request->show_id;
        $place->save();

        Session::flash('success','تم الارسال');
        return back();
    }

    # add rating
    public function rating(Request $request)
    {

        $rating = new Show_Reat;
        $rating->desc        = $request->desc;
        $rating->show_id     = $request->show_id;
        $rating->name        = $request->name;
        $rating->email       = $request->email;
        $rating->rate        = $request->rating;
        $rating->save();

        $show = Show::findOrFail($request->show_id);
        $show->rate =  Show_Reat::where('show_id' , $request->show_id)->avg('rate');
        $show->save();

        Session::flash('success','تم الارسال');
        return back();
    }

    # add going
    public function going(Request $request)
    {

        $going = new Show_Going;
        $going->show_id     = $request->show_id;
        $going->user_id       = Auth::guard('customer')->user()->id;
        $going->save();


        Session::flash('success','تم التحديد');
        return back();
    }

    # not going
    public function notgoing(Request $request)
    {

        $going = Show_Going::where('show_id',$request->show_id)->where('user_id', Auth::guard('customer')->user()->id)->first();

        $going->delete();


        Session::flash('success','تم التحديد');
        return back();
    }

    # add inter
    public function inter(Request $request)
    {

        $inter = new Interested;
        $inter->show_id     = $request->show_id;
        $inter->user_id       = Auth::guard('customer')->user()->id;
        $inter->save();


        Session::flash('success','تم التحديد');
        return back();
    }

    # not inter
    public function notinter(Request $request)
    {

        $inter = Interested::where('show_id',$request->show_id)->where('user_id', Auth::guard('customer')->user()->id)->first();

        $inter->delete();


        Session::flash('success','تم التحديد');
        return back();
    }

   
   
}
