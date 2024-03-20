<?php

namespace Modules\Shows\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shows\Entities\Show_Section;
use Modules\Shows\Entities\Organ;
use Modules\Shows\Entities\Show_Img;
use Modules\Shows\Entities\Show_Org;
use Modules\Shows\Entities\Show_Tac;
use Modules\Shows\Entities\Shows_Sec;
use Modules\Shows\Entities\Show;
use Modules\Shows\Entities\Showers;
use Modules\Shows\Entities\Speaker;
use Modules\Cities\Entities\City;
use Modules\Countries\Entities\Country;
use Modules\Shows\Entities\Place;
use Session;
use Image;
use File;
use View;

class ShowsController extends Controller
{
    use ApiResponse;
    public function __construct()
    {
        $sections = Show_Section::latest()->get();
        View::share([
            'sections' => $sections,
        ]);
    }

    # index
    public function Index()
    {
        $shows = Show::with('Section')->latest()->paginate(100);
//        $shows =null;

        return view('shows::shows.shows',compact('shows'));
    }

    # shows result
    public function showajax(Request $request)
    {
        $datas = Show::where('name' , 'like' , "%". $request->search ."%")->with('Section')->take(50)->latest()->get();
        return $datas;
    }

    # add
    public function Add()
    {

        $cities = City::latest()->get();
        $countries = Country::latest()->get();
        $organisers = Organ::get();
        return view('shows::shows.add_show_setps',compact('countries','organisers'));
    }

    # store
    public function Store(Request $request)
    {
        $request->validate([
            'name'           => 'required|max:500',
            'image'          => 'required', 
            'paied'          => 'required',
            'desc'           => 'required',
            'times'           => 'required',
          
    
        ]);

        $show = new Show;
        $show->name            = $request->name;
        $show->paied           = $request->paied;
        $show->desc            = $request->desc;
        $show->city_id         = $request->city_id;
        $show->country_id         = $request->country_id;
    
        if(!is_null($request->watch))
        { 
            $watchs    = json_encode($request->watch);
            $show->watch= $watchs;
        }else{
            $show->watch= null;
        }
        
        if(!is_null($request->times))
        { 
            $times    = json_encode($request->times);
            $show->time= $times;
        }else{
            $show->time= null;
        }
        
        if(!is_null($request->image))
        {
            $show->image = $this->storeImage($request->image,'show/images');
//            $photo=$request->image;
//            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
//            Image::make($photo)->save('uploads/show/images/'.$name);
//            $show->image=$name;
        }
        $show->save();


        $kind      = $request->kind;
        $price     = $request->price;
        $tackits  = [];

    
        foreach ($kind as $key => $value)
        {
            $tackits[$value] = [
                'pri'    => $price[$key],
            ];
        }

        if(!is_null($request->organs))
        {

            foreach($request->organs as $organs){

                $org = new Show_Org;
                $org->show_id = $show->id;
                $org->org_id = $organs;
                $org->save();
            }
        }

        foreach($tackits as $key => $value)
        {
            $data     = json_encode($value);
            $data     = json_decode($data);

            if($key != null && $data->pri != null )
            {
                $item = new Show_Tac;
                $item->name     = $key;
                $item->price  = $data->pri;
                $item->show_id  = $show->id;
                $item->save();
            }
        }

        if($request->hasfile('images'))
        {
            foreach($request->images as $image){

            $photo=$image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/show/alboum/'.$name);
            
                $img = new Show_Img;
                $img->show_id = $show->id;
                $img->image = $name;
                $img->save();
            }
        }

        $datass  = Show_Section::whereIn('id',$request->Section)->get();
            if($datass)
            {
                foreach($datass as $s){
                    $section = new Shows_Sec;
                    $section->section_id = $s->id;
                    $section->show_id = $show->id;
                    $section->save();
                }
            }

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة معرض '.$show->name);
        return redirect()->route('editshow', ['id' => $show->id]);
        
    }

    # edit
    public function Edit($id)
    {
        $shows = Show::with('ShowOrgs','ShowImgs','ShowTacs','Showers','Speakers')->where('id',$id)->first();
        $time     = $shows->time;
        $times    = json_decode($time);

        $watch     = $shows->watch;
        $watchs    = json_decode($watch);
        
        $cities    = City::latest()->get();
        $organisers = Organ::get();
        $countries = Country::latest()->get();
        $majs = Show_Org::where('show_id' , $id)->pluck('org_id')->toArray();
        $secs = Shows_Sec::where('show_id' , $id)->pluck('section_id')->toArray();
        return view('shows::shows.edit_show',compact('shows','majs','cities','organisers','times','secs','countries','watchs'));
    }

    # Update
    public function Update(Request $request)
    {
        $request->validate([
            'name'           => 'required|max:500',
            'paied'          => 'required',
            'desc'           => 'required',
            'times'           => 'required',
            
    
        ]);

        $show = Show::where('id',$request->id)->first();
        $show->name            = $request->name;
        $show->paied           = $request->paied;
        $show->desc            = $request->desc;
        $show->city_id         = $request->city_id;
        $show->country_id         = $request->country_id;
    
        if(!is_null($request->watchs))
        { 
            $watchs    = json_encode($request->watchs);
            $show->watch= $watchs;
        }else{
            $show->watch= null;
        }
        
        if(!is_null($request->times))
        { 
            $times    = json_encode($request->times);
            $show->time= $times;
        }else{
            $show->time= null;
        }
        

        if(!is_null($request->image))
        {
            File::delete('uploads/show/images/'.$show->image);
            $show->image= $this->storeImage($request->image,'show/images');
//            $photo=$request->image;
//            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
//            Image::make($photo)->save('uploads/show/images/'.$name);
//            $show->image=$name;
        }
        $show->save();
        Show_Org::where('show_id',$show->id)->delete();


        if(!is_null($request->organs))
        {

            foreach($request->organs as $organs){

                $org = new Show_Org;
                $org->show_id = $show->id;
                $org->org_id = $organs;
                $org->save();
            }
        }

        Shows_Sec::where('show_id',$show->id)->delete();

        $datass  = Show_Section::whereIn('id',$request->Section)->get();

        if($datass)
            {
                foreach($datass as $s){
                    $section = new Shows_Sec;
                    $section->section_id = $s->id;
                    $section->show_id = $show->id;
                    $section->save();
                }
            }


        Session::flash('success','تم الحفظ');
        MakeReport('بتحديث معرض '.$show->name);
        return back();
        
    }


    # update tackits
    public function Updatetac(Request $request)
    {
 

      $show = Show::where('id',$request->id)->first();

      Show_Tac::where('show_id',$show->id)->delete();
      if(!is_null($request->kind) || !is_null($request->price))
      {
        $kind      = $request->kind;
        $price     = $request->price;
        $tackits  = [];

    
        foreach ($kind as $key => $value)
        {
            $tackits[$value] = [
                'pri'    => $price[$key],
            ];
        }

        foreach($tackits as $key => $value)
        {
            $data     = json_encode($value);
            $data     = json_decode($data);

            if($key != null && $data->pri != null )
            {
                $item = new Show_Tac;
                $item->name     = $key;
                $item->price  = $data->pri;
                $item->show_id  = $show->id;
                $item->save();
            }
        }
      }
      Session::flash('success','تم حفظ التعديلات');
      MakeReport('بتحديث الدخول '.$show->name);
      return back();
    }

    # images 
    public function storeImages(Request $request)
    {
        $show = Show::where('id',$request->id)->first();

        if($request->hasfile('images'))
        {
        foreach($request->images as $image){

        $photo=$image;
        $name =date('d-m-y').time().rand().'.'.$photo->extension();
        Image::make($photo)->save('uploads/show/alboum/'.$name);
        
            $img = new Show_Img;
            $img->show_id     = $show->id;
            $img->image = $name;
            $img->save();
        }
        }

        Session::flash('success','تم الاضافة');
        MakeReport('باضافة صور '.$show->name);
        return back();
    }

    # delete image
    public function DeleteImage(Request $request)
    {

        $image = Show_Img::where('id',$request->id)->first();
        if($image->image != 'default.png')
        {
                File::delete('uploads/show/alboum/'.$image->image);
        }
        MakeReport('بحذف صورة لشركة ');
        $image->delete();
        Session::flash('success','تم الحذف');
        return back();
    }	

    # show_shower store 
    public function storeshower(Request $request)
    {

    $request->validate([
        'shower_name'  => 'required|max:500',
        'shower_image' => 'required',
    ]);

        $show = Show::where('id',$request->shower_id)->first();
        $shower = new Showers;
        $shower->name        = $request->shower_name;
        $shower->show_id     = $show->id;

        if(!is_null($request->shower_image))
        {
            $photo=$request->shower_image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/show/shower/'.$name);
            $shower->image = $name;
        }

        $shower->save();

        Session::flash('success','تم الاضافة');
        MakeReport('باضافة عارض '.$shower->name.' لشركة '.$show->name);
        
        return back();
    }


    # show_shower update
    public function updateshower(Request $request)
    {

        $request->validate([
            'edit_shower_name'  => 'required|max:500',
            'edit_shower_image' => 'mimes:jpeg,png,jpg,gif|',
        ]);



        $shower = Showers::findOrFail($request->edit_shower_id);
        $shower->name = $request->edit_shower_name;
    

        if(!is_null($request->edit_shower_image))
        {
            $photo=$request->edit_shower_image;
            File::delete('uploads/show/shower/'.$shower->image);
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/show/shower/'.$name);
            $shower->image = $name;
        }

        
        $shower->save();
        $show = Show::where('id',$shower->show_id)->first();
        Session::flash('success','تم الاضافة');
        MakeReport('بتعديل عارض '.$shower->name .' لشركة '.$show->name);
        
        return back();
    }

    # delete shower
    public function Deleteshower(Request $request)
    {

        $shower = Showers::where('id',$request->id)->first();

        File::delete('uploads/show/shower/'.$shower->image);
        MakeReport('بحذف العارض '.$shower->name);
        $shower->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
    
    
    # show_speaker store 
    public function storespeaker(Request $request)
    {

    $request->validate([
        'speaker_name'  => 'required|max:500',
        'speaker_image' => 'required',
        'speaker_type' => 'required',
    ]);

        $show = Show::where('id',$request->speaker_id)->first();
        $speaker = new Speaker;
        $speaker->name        = $request->speaker_name;
        $speaker->type        = $request->speaker_type;
        $speaker->show_id     = $show->id;

        if(!is_null($request->speaker_image))
        {
            $photo=$request->speaker_image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/show/speaker/'.$name);
            $speaker->image = $name;
        }

        $speaker->save();

        Session::flash('success','تم الاضافة');
        MakeReport('باضافة متحدث '.$speaker->name.' لشركة '.$show->name);
        
        return back();
    }


    # show_speaker update
    public function updatespeaker(Request $request)
    {

        $request->validate([
            'edit_speaker_name'  => 'required|max:500',
            'edit_speaker_image' => 'mimes:jpeg,png,jpg,gif|',
            'edit_speaker_type'  => 'required|max:500',
        ]);



        $speaker = Speaker::findOrFail($request->edit_speaker_id);
        $speaker->name = $request->edit_speaker_name;
        $speaker->type = $request->edit_speaker_type;

        if(!is_null($request->edit_speaker_image))
        {
            $photo=$request->edit_speaker_image;
            File::delete('uploads/show/speaker/'.$speaker->image);
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/show/speaker/'.$name);
            $speaker->image = $name;
        }

        
        $speaker->save();
        $show = Show::where('id',$speaker->show_id)->first();
        Session::flash('success','تم الاضافة');
        MakeReport('بتعديل متحدث '.$speaker->name .' لشركة '.$show->name);
        
        return back();
    }

    # delete speaker
    public function Deletespeaker(Request $request)
    {

        $speaker = Speaker::where('id',$request->id)->first();

        File::delete('uploads/show/speaker/'.$speaker->image);
        MakeReport('بحذف المتحدث '.$speaker->name);
        $speaker->delete();
        Session::flash('success','تم الحذف');
        return back();
    }	

    # delete 
    public function Delete(Request $request)
    {
        $show = Show::where('id',$request->id)->first();
        File::delete('uploads/show/images/'.$show->image);
        Session::flash('success','تم الحذف');
        MakeReport('بحذف معرض '.$show->name);
        $show->delete();
        return back();
    }

    # index
    public function places()
    {
        $places = Place::with('Show')->latest()->get();
        return view('shows::shows.places',compact('places'));
    }

    # delete 
    public function Deleteplaces(Request $request)
    {
        $places = Place::where('id',$request->id)->first();
        Session::flash('success','تم الحذف');
        MakeReport('بحذف طلب '.$places->name);
        $places->delete();
        return back();
    }

   
}
