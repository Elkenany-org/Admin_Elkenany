<?php


namespace Modules\SystemAds\Http\Controllers;

use App\Main;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Modules\FodderStock\Entities\Stock_Fodder_Section;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use Modules\Guide\Entities\Guide_Section;
use Modules\Guide\Entities\Guide_Sub_Section;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\LocalStock\Entities\Local_Stock_Sub;
use Modules\LocalStock\Entities\Sec_All;
use Modules\SystemAds\Entities\Ads_User;
use Modules\SystemAds\Entities\Membership;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Modules\SystemAds\Entities\Ads_Company;
use Modules\Guide\Entities\Company;
use Carbon\Carbon;
use Session;
use Image;
use File;
use Auth;
use Symfony\Component\Config\Definition\Exception\Exception;

class AdsUserController extends Controller
{
    use ApiResponse;
    # index
    public function Index()
    {
    	$users = Ads_User::latest()->get();
    	return view('systemads::users.users',compact('users'));
    }

    # add user page
    public function AdduserPage()
    {
    	return view('systemads::users.add_user');
    }

    # get search companies ajax
    public function Searchcompany(Request $request)
    {

        $datas = Company::where('name' , 'like' , "%". $request->search ."%")->take(20)->latest()->get();
        return $datas;
    }

    # store user 
    public function Storeuser(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|unique:ads_users',
            'phone'     => 'required|min:11|numeric|unique:ads_users',
            'password'  => 'required',
            'avatar'    => 'nullable|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $user = new Ads_User;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->phone;
        $user->password = bcrypt($request->password);

       
        $user->save();

        if(!is_null($request->company_id))
        {
            foreach($request->company_id as $company){
                $ads = new Ads_Company;
                $ads->ads_user_id     = $user->id;
                $ads->company_id    = $company;
                $ads->save();
            }

        }
     
        MakeReport('بإضافة مستخدم جديد ' .$user->name);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # edit user
    public function Edituser($id)
    {
        $user = Ads_User::with('Memberships.Company','AdsCompanys.Company')->where('id',$id)->first();
        $majs = Ads_Company::where('ads_user_id' , $id)->pluck('company_id')->toArray();
    	return view('systemads::users.edit_user',compact('user','majs'));
    }

    # update user
    public function Updateuser(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|unique:ads_users,email,'.$request->id,
            'phone'     => 'required|min:11|numeric|unique:ads_users,email,'.$request->id,
            'avatar'    => 'nullable|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $user = Ads_User::where('id',$request->id)->first();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->phone;

        # password
        if(!is_null($request->password))
        {
            $user->password = bcrypt($request->password);
        }

        
        $user->save();

        Ads_Company::where('ads_user_id',$user->id)->delete();

        if(!is_null($request->company_id))
        {
            foreach($request->company_id as $company){
                $ads = new Ads_Company;
                $ads->ads_user_id     = $user->id;
                $ads->company_id    = $company;
                $ads->save();
            }

        }
        MakeReport('بتحديث مستخدم ' .$user->name);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete user
    public function Deleteuser(Request $request)
    {

    	$user = Ads_User::where('id',$request->id)->first();
    
    	MakeReport('بحذف مستخدم '.$user->name);
    	$user->delete();
    	Session::flash('success','تم الحذف');
    	return back();
    }
    
    # store membership 
    public function Storemembership(Request $request)
    {
        $request->validate([
            'ads_count' => 'required',
            'end_date'  => 'required',
            'type'      => 'required',
            'start_date'=> 'required|after_or_equal:today',
        ]);
        $user = Ads_User::where('id',$request->id)->first();

        $mem = new Membership;
        $mem->ads_count     = $request->ads_count;
        $mem->type          = $request->type;
        $mem->start_date    = $request->start_date;
        $mem->end_date      = $request->end_date;
        $mem->ads_user_id   = $user->id;
        $mem->company_id    = $request->company_id;
        $mem->main          = $request->main;
        $mem->sub           = $request->sub;
        $mem->status        = "1";
        if($request->main + $request->sub <= $request->ads_count){
            $mem->save();

            $user->status       = "1";
            $user->update();
            MakeReport('بإضافة عضوية ' .$mem->id.'لعضو   ' .$user->name);
            Session::flash('success','تم الحفظ');
            return back();
        }else{
            Session::flash('danger','عدد الرئيسي والفرعي اكبر من العدد الكلي');
            return back();
        }

        $mem->save();

        $user->status       = "1";
        $user->update();
        MakeReport('بإضافة عضوية ' .$mem->id.'لعضو   ' .$user->name);
        Session::flash('success','تم الحفظ');
        return back();
    }


    # delete membership
    public function Deletmembership(Request $request)
    {

        $mem = Membership::where('id',$request->id)->first();
        $user = Ads_User::where('id',$mem->ads_user_id)->first();

        $related_ads= System_Ads::where('ads_user_id',$user->id)->where('type',$mem->type)->get();
//        dd($related_ads);
        foreach ($related_ads as $ad){
            $ad->status='4';
            $ad->update();
        }
        MakeReport('بحذف عضوية '.$mem->id.' لمستخدم '.$user->name);
        $mem->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # systemads
    public function systemads(Request $request)
    {
        $ads = System_Ads::with('AdsUser','Company');
        if($request->query('user_id') && $request->query('type')){
            $ads->where('type',$request->query('type'))->where('ads_user_id',$request->query('user_id'));
        }
        $ads = $ads->latest()->get();
        return view('systemads::ads.ads',compact('ads'));
    }

    # add systemads
    public function Addsystemads()
    {
        $users = Ads_User::latest()->get();
    	return view('systemads::ads.add_ads',compact('users'));
    }

    # store systemads 
    public function Storesystemads(Request $request)
    {
    

        $ads = new System_Ads;
        $ads->title     = $request->title;
        $ads->desc      = $request->desc;
        $ads->ads_user_id   = $request->ads_user_id;
        $ads->link      = $request->link;
        $ads->type      = $request->type;
        $ads->company_id      = $request->company_id;
        $ads->end_date      = $request->end_date;

         if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/ads/'.$name);
            $ads->image=$name;
        }

        $ads->save();

        MakeReport('بإضافة اعلان ' .$ads->title);
        Session::flash('success','تم الحفظ');
        return back();
    }

     # edit user
    public function Editads($id)
    {
        $ads = System_Ads::with('AdsUser','Company','SystemAdsPages')->where('id',$id)->first();

        return view('systemads::ads.edit_ads',compact('ads'));
    }

    public function getSection($type_place)
    {
        $sections = '';
        if( $type_place !== 'ships'){
            $sections = Main::select('id','name','type')->latest()->get();
        }
        return response()->json($sections);
    }

    public function getSubSection($type_place,$section_type,$main)
    {

        $list = [];

        if($main == "0"){

            if($type_place === 'guide'){

                $section = Guide_Section::where('type',$section_type)->first();

                $subs = Guide_Sub_Section::select('id','name')->where('section_id',$section->id)->get();
                $list['sub_sections'] = $subs;

            }elseif($type_place === 'localstock'){

                $section = Local_Stock_Sections::where('type',$section_type)->first();

                $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();

                $subs = Local_Stock_Sub::select('id','name')->where('section_id',$section->id)->orderby('name');

                if(count($majs) == 0 ){ $subs->orWhereIn('id',$majs); }

                $subs = $subs->get();

                $list['sub_sections'] = $subs;

            }elseif($type_place === 'fodderstock'){

                $section = Stock_Fodder_Section::where('type',$section_type)->first();

                $subs = Stock_Fodder_Sub::select('id','name')->where('section_id',$section->id)->get();
                $list['sub_sections'] = $subs;

            }else{
                $list['chack'][]       = "1";
                $list['chack'][]       = "0";
            }
        }

        return response()->json($list);
    }
     # store systemads 
    public function updateads(Request $request)
    {

        $ads = System_Ads::where('id',$request->id)->first();
//        return $ads;
        $mem = Membership::where('ads_user_id',$ads->ads_user_id)->where('type',$ads->type)->where('company_id',$ads->company_id)->latest()->first();

        $adss = System_Ads::where('ads_user_id',$ads->ads_user_id)->where('type',$ads->type)->where('company_id',$ads->company_id)->where('status','1')->get();
        $adssmain = System_Ads::where('ads_user_id',$ads->ads_user_id)->where('type',$ads->type)->where('main','1')->where('company_id',$ads->company_id)->where('status','1')->get();
        $adsssub = System_Ads::where('ads_user_id',$ads->ads_user_id)->where('type',$ads->type)->where('sub','1')->where('company_id',$ads->company_id)->where('status','1')->get();
        $today = Carbon::now();


        if(count($adss) >= $mem->ads_count && $request->status == "1" && $ads->status != 1){
            return redirect()->back()->with(['danger'=>'You have exceeded the specified number of ad type']);
        }elseif($mem->end_date <=  $today->format('Y-m-d') ){

            return redirect()->back()->with(['danger'=>'You have passed the due date']);

        }elseif($ads->end_date <=  $today->format('Y-m-d') || $request->end_date < date('Y-m-d')){

            return redirect()->back()->with(['danger'=>'You in end date']);

        }else{
            if($ads->main == '1' && $ads->status != 1){
                if(count($adssmain) > 0){
                    if(count($adssmain) >= $mem->main && $request->status == "1" ){
                        return redirect()->back()->with(['danger'=>'You have exceeded the specified number of ad type main']);
                    }
                }
            }
            if($ads->sub == '1' && $ads->status != 1){
                if(count($adsssub) > 0){
                    if(count($adsssub) >= $mem->sub && $request->status == "1" ){
                        return redirect()->back()->with(['danger'=>'You have exceeded the specified number of ad type sub']);
                    }
                }
            }

            if($request->has('image')){
                $ads->image = $this->storeImage($request->image,'full_images');
            }

            $request->has('not_time') ? $ads->not_time = $request->not_time : '';
            $ads->title = $request->title;

            $ads->end_date = $request->end_date;
            $ads->link = $request->link;
            $ads->desc = $request->desc;
            if(!$request->has('status')){
                return redirect()->back()->with(['danger'=>'status is required']);
            }
            $ads->status  = $request->status;
            $ads->save();

//            if($request->has('system_ads_pages')){
//                $ad_page = System_Ads_Pages::where('ads_id',$request->id)->first();
//                $ad_page->type = $request['system_ads_pages']['type'] ? $request['system_ads_pages']['type'] : '';
//                isset($request['system_ads_pages']['section_type']) ? $ad_page->section_type = $request['system_ads_pages']['section_type'] : '';
//                isset($request['system_ads_pages']['sub_id']) ? $ad_page->sub_id = $request['system_ads_pages']['sub_id'] : '';
//                isset($request['system_ads_pages']['status']) ? $ad_page->status = $request['system_ads_pages']['status'] : '';
//                $ad_page->save();
//            }
        }

       

        MakeReport('بمراجعة اعلان ' .$ads->title);
        Session::flash('success','تم الحفظ');
        return back();
    }


    public function Updatemembership(Request $request,$id)
    {
        $member = Membership::findOrFail($id);
        $requestData = $request->except(['_token']);

        try {
            $member->update($requestData);
        }catch (\Exception $e){
            return \response()->json(['error'=>$e->getMessage()],422);
        }

        return redirect()->back()->with(['success'=>'تم التعديل']);
    }
}
