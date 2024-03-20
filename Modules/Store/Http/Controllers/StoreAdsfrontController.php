<?php

namespace Modules\Store\Http\Controllers;

use App\Traits\ApiResponse;
use App\Traits\SearchReg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Store\Entities\Customer;
use Modules\Store\Entities\Store_Ads;
use Modules\Store\Entities\Store_Ads_images;
use Modules\Store\Entities\Store_Ads_Comment;
use Modules\Store\Entities\Store_Section;
use Modules\Store\Entities\Chats;
use Modules\Store\Entities\Chat_Mas;
use Illuminate\Support\Facades\Input;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use Carbon\Carbon;
use Session;
use Image;
use File;
use Auth;

class StoreAdsfrontController extends Controller
{
    use SearchReg,ApiResponse;
    /**
     * @param $name
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function sections($name)
    {
        
        $sort  = Input::get("sort");
        $date  = Input::get("date");
        $section = Store_Section::where('type',$name)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','store')->pluck('ads_id');

        $banners = System_Ads::whereIn('id',$page)->whereIn('type',['banner','logo'])->where('status','1')->inRandomOrder()->get();

        $customer = Customer::with('StoreAds')->where('memb','1')->pluck('id');

        $secs = Store_Section::get();

            if(Auth::guard('customer')->user() && Auth::guard('customer')->user()->memb != 1){
                if($date && $date < Carbon::now()->subDays(7)) {
                    return redirect()->back()->with(['danger' => ' ليست لديك الصلاحية']);
                }
            }else{
                if($date && $date < Carbon::now()->subDays(7) && !Auth::guard('customer')->user()){
                    return redirect()->back()->with(['danger'=>' ليست لديك الصلاحية']);
                }
            }


            $storesmemb = Store_Ads::with('StoreAdsimages')->whereIn('user_id',$customer)->where('section_id',$section->id);
            $stores     = Store_Ads::with('StoreAdsimages')->whereNotIn('user_id',$customer)->where('section_id',$section->id);
            $storesa    = Store_Ads::with('StoreAdsimages')->Where('user_id' , null)->where('section_id',$section->id);

            if($sort == '0' || is_null($sort)){
                $storesmemb->orderBy('title','desc');
                $stores->orderBy('title','desc');
                $storesa->orderBy('title','desc');
            }elseif($sort == '1'){
                $storesmemb->orderBy('view_count','desc');
                $stores->orderBy('view_count','desc');
                $storesa->orderBy('view_count','desc');
            }elseif($sort == '2'){
                $storesmemb->where('con_type','الرسائل')->orderBy('title');
                $stores->where('con_type','الرسائل')->orderBy('title');
                $storesa->where('con_type','الرسائل')->orderBy('title');
            }elseif($sort == '3'){
                $storesmemb->where('con_type','الموبايل')->orderBy('title');
                $stores->where('con_type','الموبايل')->orderBy('title');
                $storesa->where('con_type','الموبايل')->orderBy('title');
            }elseif($sort == '4'){
                $storesmemb->where('con_type','كلاهما')->orderBy('title');
                $stores->where('con_type','كلاهما')->orderBy('title');
                $storesa->where('con_type','كلاهما')->orderBy('title');
            }
            if($date){
                $storesmemb->whereDate('created_at',$date);
                $stores->whereDate('created_at',$date);
                $storesa->whereDate('created_at',$date);
            }


            $storesmemb = $storesmemb->paginate(10);
            $stores = $stores->paginate(10);
            $storesa = $storesa->paginate(10);

        return view('store::fronts.store_ads',compact('banners','stores','storesmemb','section','storesa','secs'));
    }

    # datas
    public function searchByName(Request $request)
    {

  
        $datas = Store_Ads::with('StoreAdsimages')->where('title' , 'like' , "%". $request->search ."%")->take(50)->latest()->get();



        return response()->json(['datas'=>$datas]);

    }
 
    # datas
    public function datas(Request $request)
    {
 
        $section = Store_Section::where('id',$request->id)->first();
        $limit =5;
        $datas = Store_Ads::with('StoreAdsimages')->where('section_id',$section->id)->orderby('title')->take($limit)->get();
        $id = $request->id;
   
 
        return response()->json(['datas'=>$datas, 'limit'=>$limit, 'id'=>$id]);

    }

    # mores
    public function mores(Request $request)
    {

        $section = Store_Section::where('id',$request->id)->first();
        $count = $request->count;
        $limit = $count + 5;
        $datas = Store_Ads::with('StoreAdsimages')->where('section_id',$section->id)->orderby('title')->skip($count)->take(5)->get();
        $id = $request->id;
    

        return response()->json(['datas'=>$datas, 'limit'=>$limit, 'id'=>$id]);

    }

    # store_ads
    public function ads(Request $request, $id)
    {
 

        $ads = Store_Ads::with('StoreAdsimages','Customer','User')->where('id',$id)->first();
        $ads->view_count = $ads->view_count + 1;
        $ads->save();

        $section = Store_Section::where('id' , $ads->section_id)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('section_type',$section->type)->where('type','store')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
 
        return view('store::fronts.one_store_ads',compact('ads','adss','logos'));

    }

    # start chat
    public function startchat(Request $request, $id)
    {


        $ads = Store_Ads::with('StoreAdsimages','Customer')->where('id',$id)->first();
        $section = Store_Section::where('id' , $ads->section_id)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('section_type',$section->type)->where('type','store')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
 

        $cha = Chats::where('owner_id' , $ads->Customer->id)->where('user_id' , Auth::guard('customer')->user()->id)->first();
        if(!$cha){
            $chaa = Chats::where('user_id' , $ads->Customer->id)->where('owner_id' , Auth::guard('customer')->user()->id)->first();
            if(!$chaa){
                $chat = new Chats;
                $chat->owner_id= $ads->Customer->id;
                $chat->user_id  = Auth::guard('customer')->user()->id;
                $chat->save();
            }
        }
       

    
        $chats = Chats::with('User','Owner')->where('user_id' , Auth::guard('customer')->user()->id)->orWhere('owner_id', Auth::guard('customer')->user()->id)->latest()->get();

      
        return view('store::fronts.chats',compact('ads','chats','section','adss','logos'));

    }

    # my story
    public function myads($id)
    {
        $section = Store_Section::where('id' , $id)->first();

        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('section_type',$section->type)->where('type','store')->pluck('ads_id')->toArray();

        $banners = System_Ads::whereIn('id',$page)->whereIn('type',['logo','banner'])->where('status','1')->inRandomOrder()->get();

        if(Auth::guard('customer')->user())
        {
            $ads = Store_Ads::with('StoreAdsimages','Customer','User')->where('user_id' , Auth::guard('customer')->user()->id)->get();

            return view('store::fronts.my_store_ads',compact('section','ads','banners'));
        }
    
        
        return view('store::fronts.my_store_ads',compact('section','banners'));
    }


    # add store
    public function addstore($id)
    {
        $section = Store_Section::where('id' , $id)->first();
     
    
        
        return view('store::fronts.add_store_ads',compact('section'));
    }

     # store ads 
    public function Storestoreads(Request $request)
    {
        $request->validate([
            'title'    => 'required',
            'desc'     => 'required',
            'phone'    => 'required',
            'salary'   => 'required',
        ]);

        $customer = Customer::with('StoreAds')->where('id',Auth::guard('customer')->user()->id)->first();
        if($customer->memb == '0')
        {
            if(count($customer->StoreAds) == 5)
            {
                return redirect()->back()->with(['danger'=>' ليست لديك الصلاحية']);
            }
        }

        $store = new Store_Ads;
        $store->title     = $request->title;
        $store->desc      = $request->desc;
        $store->phone     = $request->phone;
        $store->address   = $request->address;
        $store->con_type  = $request->con_type;
        $store->salary    = $request->salary;
        $store->section_id= $request->section_id;
        $store->user_id  = Auth::guard('customer')->user()->id;
        $store->save();

        if($request->has('images'))
        {
            foreach($request->images as $image)
            {
                $name = $this->storeImage($image,"stores/alboum");

                    $img = new Store_Ads_images;
                    $img->ads_id = $store->id;
                    $img->image = $name;
                    $img->save();
            }
        }
            return redirect()->route('front_my_ads',$store->section_id);
    }

    # my story
    public function editads(Request $request, $id)
    {
        $ads = Store_Ads::with('StoreAdsimages','Customer','User')->where('id' , $id)->first();
   
        
        return view('store::fronts.edit_store_ads',compact('ads'));
    }

    # update ads 
    public function updatestoreads(Request $request)
    {
        $request->validate([
            'title'    => 'required',
            'desc'     => 'required',
            'phone'    => 'required',
            'salary'   => 'required',
        ]);

        $store = Store_Ads::where('id' , $request->id)->first();
        $store->title     = $request->title;
        $store->desc      = $request->desc;
        $store->phone     = $request->phone;
        $store->address   = $request->address;
        $store->con_type  = $request->con_type;
        $store->salary    = $request->salary;
        $store->save();

        Store_Ads_images::where('ads_id' , $request->id)->delete();
        
        if($request->hasfile('images'))
        {
            foreach($request->images as $image){

            $photo=$image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/stores/alboum/'.$name);
            
                $img = new Store_Ads_images;
                $img->ads_id = $store->id;
                $img->image = $name;
                $img->save();
            }
        }
        return redirect()->route('front_my_ads',$store->section_id);
    }

    # delete ads
    public function Deleteads(Request $request)
    {

        $store = Store_Ads::where('id',$request->id)->first();

        $store->delete();
        return back();
    }

    # my chats
    public function chats(Request $request, $id)
    {
        $section = Store_Section::where('id' , $id)->first();

        if(Auth::guard('customer')->user())
        {
            $chats = Chats::with('User','Owner')->where('user_id' , Auth::guard('customer')->user()->id)->orWhere('owner_id', Auth::guard('customer')->user()->id)->latest()->get();

            return view('store::fronts.chats',compact('section','chats'));
        }
        
        return view('store::fronts.chats',compact('section'));
    }



    # my chats
    public function chatsmassages(Request $request)
    {


        $datas = Chat_Mas::with('send','resav')->where('chat_id' , $request->id)->get();

        
        return $datas;
    }

    # write massage
    public function writemassage(Request $request)
    {


        $chat = Chats::where('id',$request->id)->first();
        
        if($chat->owner_id === Auth::guard('customer')->user()->id){

            $mass = new Chat_Mas;
            $mass->chat_id= $chat->id;
            $mass->resav_id= $chat->user_id;
            $mass->massage= $request->massage;
            $mass->sender_id  = Auth::guard('customer')->user()->id;
            $mass->save();

        }else{

            $mass = new Chat_Mas;
            $mass->chat_id= $chat->id;
            $mass->resav_id= $chat->owner_id;
            $mass->massage= $request->massage;
            $mass->sender_id  = Auth::guard('customer')->user()->id;
            $mass->save();
        }
        
        $datas = Chat_Mas::with('send','resav')->where('id' , $mass->id)->first();
    
    
        return $datas;

    }



  

}
