<?php

namespace Modules\Magazines\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cities\Entities\City;
use Modules\Magazines\Entities\Mag_Section;
use Modules\Magazines\Entities\Magazin_Alboum_Images;
use Modules\Magazines\Entities\Magazin_gallary;
use Modules\Magazines\Entities\Magazin_guide;
use Modules\Magazines\Entities\Magazin_Social_media;
use Modules\Magazines\Entities\Magazine_address;
use Modules\Magazines\Entities\Magazine_Rate;
use Modules\Magazines\Entities\Magazine_Sec;
use Modules\Magazines\Entities\Magazine;
use App\Social;
use Session;
use Image;
use File;
use View;

class MagazinesController extends Controller
{
    use ApiResponse;
 
    public function __construct()
    {
        $sections = Mag_Section::latest()->get();
        View::share([
            'sections' => $sections,
        ]);
    }

    # index
    public function Index()
    {
        $magazines = Magazine::with('sections')->latest()->get();
        return view('magazines::magazines.magazines',compact('magazines'));
    }

    # add
    public function Add()
    {
        $social    = Social::latest()->get();
        $cities = City::latest()->get();
        return view('magazines::magazines.add_maga_setps',compact('social','cities'));
    }

    # store
    public function Store(Request $request)
    {
         $request->validate([
            'name'           => 'required|max:500',
            'image'          => 'required',
            'address'        => 'required',
            'paied'          => 'required',
            'short_desc'     => 'required',
            'manage_phone'   => 'required',
            'manage_email'   => 'required',
            'about'          => 'required',
            'mobiles'        => 'required',
            'phones'         => 'required',
            'emails'         => 'required',
            'faxs'         => 'required',
     
        ]);

        $magazine = new Magazine;
        $magazine->name            = $request->name;
        $magazine->paied           = $request->paied;
        $magazine->address         = $request->address;
        $magazine->short_desc      = $request->short_desc;
        $magazine->manage_phone    = $request->manage_phone;
        $magazine->manage_email    = $request->manage_email;
        $magazine->about           = $request->about;
        $magazine->latitude        =  $request->latitude ;
        $magazine->longitude       =  $request->longitude ;
        $magazine->city_id         = $request->city_id;
    
        if(count($request->mobiles) > 0)
        {
            $mobiles    = json_encode($request->mobiles);
            $magazine->mobiles= $mobiles;     
        }
        if(count($request->phones) > 0)
        {
            $phones     = json_encode($request->phones);
            $magazine->phones = $phones;
        }
        if(count($request->emails) > 0)
        {
            $emails     = json_encode($request->emails);
            $magazine->emails=$emails; 
        }
        if(count($request->faxs) > 0)
        {
            $faxs     = json_encode($request->faxs);
            $magazine->faxs=$faxs; 
        }
        if(!is_null($request->image))
        {
            $magazine->image = $this->storeImage($request->image,'magazine/images');
//            $photo=$request->image;
//            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
//            Image::make($photo)->save('uploads/magazine/images/'.$name);
//            $magazine->image=$name;
        }
        $magazine->save();

        //social
        if(!is_null($request->social_link))
        {
            foreach(array_combine($request->social_id, $request->social_link) as $social_id => $social_link)
            {
                if($social_link != null)
                {
                    Magazin_Social_media::insert(['social_link' => $social_link, 'maga_id' => $magazine->id,'social_id' => $social_id]);
                }
            }
        }

       
  
 
            $loca      = $request->loca;
            $lat       = $request->lat;
            $long      = $request->long;
            $location  = [];

        
            foreach ($loca as $key => $value)
            {
                $location[$value] = [
                    'latt'    => $lat[$key],
                    'lng'     => $long[$key],
                ];
            }

            foreach($location as $key => $value)
            {
                $data     = json_encode($value);
                $data     = json_decode($data);
    
                if($key != null && $data->latt != null &&  $data->lng != null)
                {
                    $item = new Magazine_address;
                    $item->address    = $key;
                    $item->latitude  = $data->latt;
                    $item->longitude = $data->lng;
                    $item->maga_id  = $magazine->id;
                    $item->save();
                }
            }
    

        $datass  = Mag_Section::whereIn('id',$request->Section)->get();
            if($datass)
            {
                foreach($datass as $s){
                    $section = new Magazine_Sec;
                    $section->section_id = $s->id;
                    $section->maga_id = $magazine->id;
                    $section->save();
                }
            }
        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة المجلة '.$magazine->name);
        return redirect()->route('editmagazine', ['id' => $magazine->id]);
       
    }

    # edit
    public function Edit($id)
    {
        $magazine = Magazine::with('MagazineAlboumImages','Magazingallary','Magazineaddress','MagazinSocialmedia.Social','Magazinguide')->where('id',$id)->first();
        $phone     = $magazine->phones;
        $phones    = json_decode($phone);
        $email     = $magazine->emails;
        $emails    = json_decode($email);
        $mobile    = $magazine->mobiles;
        $mobiles   = json_decode($mobile);
        $fax       = $magazine->faxs;
        $faxs      = json_decode($fax);
        $soc       = Magazin_Social_media::where('maga_id' , $id)->pluck('social_id')->toArray();
        $social    = Social::whereNotIn('id',$soc)->with('MagazinSocialmedia')->latest()->get();
        $cities    = City::latest()->get();
        $majs = Magazine_Sec::where('maga_id' , $id)->pluck('section_id')->toArray();
        return view('magazines::magazines.edit_maga',compact('magazine','majs','faxs','phones','emails','mobiles','social','cities'));
    }

    # update
    public function Update(Request $request)
    {
        $request->validate([
            'name'          => 'required|max:500',
            'image'         => 'mimes:jpeg,png,jpg,gif|',
            'address'       => 'required',
            'paied'         => 'required',
            'short_desc'    => 'required',
            'manage_phone'  => 'required',
            'manage_email'  => 'required',
            'about'         => 'required',
        ]);

        $magazine = Magazine::where('id',$request->id)->first();
        $magazine->name          = $request->name;
        $magazine->address       = $request->address;
        $magazine->short_desc    = $request->short_desc;
        $magazine->about         = $request->about;
        $magazine->paied         = $request->paied;
        $magazine->manage_phone  = $request->manage_phone;
        $magazine->manage_email  = $request->manage_email;
        $magazine->latitude      =  $request->latitude ;
        $magazine->longitude     =  $request->longitude ;
        $magazine->city_id       = $request->city_id;

     
        if(!is_null($request->image))
        {
            File::delete('uploads/magazine/images/'.$magazine->image);
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/magazine/images/'.$name);
            $magazine->image=$name;
        }

        $magazine->save();
        Magazine_Sec::where('maga_id',$magazine->id)->delete();

        $datass  = Mag_Section::whereIn('id',$request->Section)->get();

        if($datass)
            {
                foreach($datass as $s){
                    $section = new Magazine_Sec;
                    $section->section_id = $s->id;
                    $section->maga_id = $magazine->id;
                    $section->save();
                }
            }
        Session::flash('success','تم حفظ التعديلات');
        MakeReport('بتحديث المجلة '.$magazine->name);
        return back();
    }

    # update contact
    public function Updatecontact(Request $request)
    {
        $request->validate([
            'mobiles'       => 'required',
            'phones'        => 'required',
            'emails'        => 'required',
            'faxs'          => 'required',
        ]);

        $magazine = Magazine::where('id',$request->id)->first();
        if(count($request->mobiles) > 0)
        { 
            $mobiles    = json_encode($request->mobiles);
            $magazine->mobiles= $mobiles;
        }
        if(count($request->phones) > 0)
        {
            $phones     = json_encode($request->phones);
            $magazine->phones = $phones; 
        }
        if(count($request->emails) > 0)
        {
            $emails     = json_encode($request->emails);
            $magazine->emails=$emails;  
        }

        if(count($request->faxs) > 0)
        {
            $faxs     = json_encode($request->faxs);
            $magazine->faxs=$faxs;  
        }

        $magazine->save();
        
      
        Session::flash('success','تم حفظ التعديلات');
        MakeReport('بتحديث المجلة '.$magazine->name);
        return back();
    }

      # update local
      public function Updatelocal(Request $request)
      {
   
  
        $magazine = Magazine::where('id',$request->id)->first();

        Magazine_address::where('maga_id',$magazine->id)->delete();
        if(!is_null($request->loca) || !is_null($request->lat) || !is_null($request->long))
        {
            $loca      = $request->loca;
            $lat       = $request->lat;
            $long      = $request->long;
            $location  = [];

        
            foreach ($loca as $key => $value)
            {
                $location[$value] = [
                    'latt'    => $lat[$key],
                    'lng'     => $long[$key],
                ];
            }

            foreach($location as $key => $value)
            {
                $data     = json_encode($value);
                $data     = json_decode($data);

                if($key != null && $data->latt != null &&  $data->lng != null)
                {
                    $item = new Magazine_address;
                    $item->address    = $key;
                    $item->latitude  = $data->latt;
                    $item->longitude = $data->lng;
                    $item->maga_id  = $magazine->id;
                    $item->save();
                }
            }
        }
        Session::flash('success','تم حفظ التعديلات');
        MakeReport('بتحديث العناوين '.$magazine->name);
        return back();
    }

    # delete 
    public function Delete(Request $request)
    {
        $magazine = Magazine::where('id',$request->id)->first();
        File::delete('uploads/magazine/images/'.$magazine->image);
        Session::flash('success','تم الحذف');
        MakeReport('بحذف شركة '.$magazine->name);
        $magazine->delete();
        return back();
    }

    # storesocial
    public function storesocial(Request $request)
    {

        
        //social

        $magazine = Magazine::where('id',$request->id)->first();
        Magazin_Social_media::where('maga_id',$magazine->id)->delete();
        foreach(array_combine($request->social_id, $request->social_link) as $social_id => $social_link)
        {
            if($social_link != null)
            {
                Magazin_Social_media::insert(['social_link' => $social_link, 'maga_id' => $request->id,'social_id' => $social_id]);
            }
        }
    
        Session::flash('success','تم حفظ التعديلات');
        MakeReport('بتحديث مواقع التواصل لمجلة  ' .$magazine->name);
 
        return back();
    }

    # images 
    public function storeImages(Request $request)
    {
        $gallary = Magazin_gallary::where('id',$request->id)->first();
        $magazine = Magazine::where('id',$gallary->maga_id)->first();

        if($request->hasfile('images'))
        {
        foreach($request->images as $image){

        $photo=$image;
        $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
        Image::make($photo)->save('uploads/magazine/alboum/'.$name);
        
            $img = new Magazin_Alboum_Images;
            $img->gallary_id     = $gallary->id;
            $img->maga_id     = $magazine->id;
            $img->image = $name;
            $img->save();
        }
        }
 
        Session::flash('success','تم الاضافة');
        MakeReport('باضافة صور '.$magazine->name);
        return back();
    }

    # delete image
    public function DeleteImage(Request $request)
    {

        $image = Magazin_Alboum_Images::where('id',$request->id)->first();
        $magazine = Magazine::where('id',$image->maga_id)->first();
        if($image->image != 'default.png')
        {
                File::delete('uploads/magazine/alboum/'.$image->image);
        }
        MakeReport('بحذف صورة لمجلة '.$magazine->name);
        $image->delete();
        Session::flash('success','تم الحذف');
        return back();
    }	

    # update image
    public function Updateimage(Request $request)
    {
        $request->validate([
            'edit_id'          => 'required',
            'edit_note'        => 'required',
           
        ]);

        $image = Magazin_Alboum_Images::findOrFail($request->edit_id);
        $image->note       = $request->edit_note;
    
        $image->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث وصف الصورة  '.$image->note);
        return back();
    }

    # guide store 
    public function storeguide(Request $request)
    {

    $request->validate([
        'guide_name'  => 'required|max:500',
        'guide_image' => 'required',
    ]);

        $magazine = Magazine::where('id',$request->guide_id)->first();
        $guide = new Magazin_guide;
        $guide->name           = $request->guide_name;
        $guide->maga_id     = $magazine->id;
        $guide->link           = $request->guide_link;

        if(!is_null($request->guide_image))
        {
            $photo=$request->guide_image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/magazine/guides/'.$name);
            $guide->image = $name;
        }

        $guide->save();

        Session::flash('success','تم الاضافة');
        MakeReport('باضافة دلائل '.$guide->name.' لمجلة '.$magazine->name);
        
        return back();
    }


    # guide update
    public function updateguide(Request $request)
    {

        $request->validate([
            'edit_guide_name'  => 'required|max:500',
            'edit_guide_image' => 'mimes:jpeg,png,jpg,gif|',
        ]);



        $guide = Magazin_guide::findOrFail($request->edit_guide_id);
        $guide->name = $request->edit_guide_name;
        $guide->link = $request->edit_guide_link;
    

        if(!is_null($request->edit_guide_image))
        {
            $photo=$request->edit_guide_image;
            File::delete('uploads/magazine/guides/'.$guide->image);
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/magazine/guides/'.$name);
            $guide->image = $name;
        }

        
        $guide->save();
        $magazine = Magazine::where('id',$guide->maga_id)->first();
        Session::flash('success','تم الاضافة');
        MakeReport('بتعديل دلائل '.$guide->name .' لمجلة '.$magazine->name);
        
        return back();
    }

    # delete guide
    public function Deleteguide(Request $request)
    {

        $guide = Magazin_guide::where('id',$request->id)->first();

        File::delete('uploads/magazine/guides/'.$guide->image);
        MakeReport('بحذف الدلائل '.$guide->name);
        $guide->delete();
        Session::flash('success','تم الحذف');
        return back();
    }	

    # gallary
    public function storegallary(Request $request)
    {

        $request->validate([
            'gallary_name'  => 'required|max:500',
            'gallary_image' => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);



        $gallary = new Magazin_gallary;
        $gallary->name  = $request->gallary_name;
        $gallary->maga_id    = $request->maga_id;
        if(!is_null($request->gallary_image))
        {
            $photo=$request->gallary_image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/gallary/avatar/'.$name);
            $gallary->image =$name;
        }
        $gallary->save();
        $magazine = Magazine::where('id',$gallary->maga_id)->first();
        Session::flash('success','تم الاضافة');
        MakeReport('باضافة  البوم '.$gallary->name .' لمجلة '.$magazine->name);
        
        return back();
    }

    # gallary
    public function updategallary(Request $request)
    {

        $request->validate([
            'edit_gallary_name'  => 'required|max:500',
            'edit_gallary_image' => 'mimes:jpeg,png,jpg,gif,svg',
        ]);



        $gallary = Magazin_gallary::findOrFail($request->edit_gallary_id);
        $gallary->name  = $request->edit_gallary_name;
        if(!is_null($request->edit_gallary_image))
        {

            File::delete('uploads/gallary/avatar/'.$gallary->image);
            $photo=$request->edit_gallary_image;
        
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/gallary/avatar/'.$name);
            $gallary->image =$name;
        }
        $gallary->save();
        $magazine = Magazine::where('id',$gallary->maga_id)->first();
        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  البوم '.$gallary->name.' لمجلة '.$magazine->name);
        
        return back();
    }


    # delete gallary
    public function Deletegallary(Request $request)
    {

        $gallary = Magazin_gallary::where('id',$request->id)->first();
        $magazine = Magazine::where('id',$gallary->maga_id)->first();
        MakeReport('بحذف  البوم '.$gallary->name.' لمجلة '.$magazine->name);
        $gallary->delete();
        Session::flash('success','تم الحذف');
        return back();
    }	

    # gallary
    public function gallary($id)
    {
        $gallary = Magazin_gallary::with('MagazineAlboumImages')->where('id',$id)->first();
     
        return view('magazines::magazines.gallary',compact('gallary'));
    }
}
