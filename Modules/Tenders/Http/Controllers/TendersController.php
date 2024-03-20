<?php

namespace Modules\Tenders\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Tenders\Entities\Tender;
use Modules\Tenders\Entities\Tender_Section;
use Modules\Cities\Entities\City;
use Session;
use Image;
use File;
class TendersController extends Controller
{
    # index
   public function Index()
   {
       $threeMonthsAgo = Carbon::now()->subMonths(3)->toDateString();
       $tenders = Tender::with('Section')->whereDate('open_date', '>=', $threeMonthsAgo)->latest()->paginate(100);
       return view('tenders::tenders.tenders',compact('tenders'));
   }

   # add tenders page
   public function Addtenders()
   {
       $sections = Tender_Section::latest()->get();
       $cities = City::latest()->get();
       return view('tenders::tenders.add_tenders',compact('sections','cities'));
   }

   # store tenders 
   public function Storetenders(Request $request)
   {
       $request->validate([
           'title'    => 'required',
           'open_date'     => 'required',
           'desc'     => 'required',
           'section_id'     => 'required',
           'city_id'     => 'required',
           'image'    => 'required|mimes:jpeg,png,jpg,gif,svg',
       ],[
           'title.required'    => 'يرجي ادخال العنوان',
           'open_date.required'    => 'يرجي ادخال تاريخ فتح المظاريف',
           'desc.required'     => 'يرجي ادخال التفاصيل',
           'section_id.required'     => 'يرجي اختيار القطاع',
           'city_id.required'     => 'يرجي اختيار المدينة',
           'image.required'    => 'يرجي ادخال الصورة',
       ]);

       $tenders = new Tender;
       $tenders->title     = $request->title;
       $tenders->open_date     = $request->open_date;
       $tenders->desc    = $request->desc;
       $tenders->section_id = $request->section_id;
       $tenders->city_id = $request->city_id;

        # upload image
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
//            Image::make($photo)->save('uploads/tenders/avatar/'.$name);
            $request->image->move('uploads/tenders/avatar' , $name);
            $tenders->image=$name;
        }
        $tenders->save();
     

       
       MakeReport('بإضافة مناقصة جديدة ' .$tenders->title);
       Session::flash('success','تم الحفظ');
       return back();
   }

   # edit tenders
   public function Edittenders($id)
   {
       $tenders = Tender::where('id',$id)->first();
       $sections = Tender_Section::latest()->get();
       $cities = City::latest()->get();
       return view('tenders::tenders.edit_tenders',compact('tenders','sections','cities'));
   }

   # update tenders
   public function Updatetenders(Request $request)
   {
       $request->validate([
        'title'    => 'required',
        'open_date' => 'required',
        'desc'     => 'required',
        'image'    => 'mimes:jpeg,png,jpg,gif,svg',
       ]);

       $tenders = Tender::where('id',$request->id)->first();
       $tenders->title     = $request->title;
       $tenders->desc    = $request->desc;
       $tenders->open_date     = $request->open_date;
       $tenders->section_id = $request->section_id;
       $tenders->city_id = $request->city_id;

        # upload avatar
        if(!is_null($request->image))
        {
 
            File::delete('uploads/tenders/avatar/'.$tenders->image);
            # upload new image
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
//            Image::make($photo)->save('uploads/tenders/avatar/'.$name);
            $request->image->move('uploads/tenders/avatar' , $name);
            $tenders->image=$name;
        }

       $tenders->save();
       MakeReport('بتحديث مناقصة ' .$tenders->title);
       Session::flash('success','تم الحفظ');
       return back();
   }

   # delete tenders
   public function Deletetenders(Request $request)
   {

       $tenders = Tender::where('id',$request->id)->first();

       MakeReport('بحذف مناقصة '.$tenders->title);
       $tenders->delete();
       Session::flash('success','تم الحذف');
       return back();
   }


    # shows result
    public function tenderajax(Request $request)
    {
        $datas = Tender::where('title' , 'like' , "%". $request->search ."%")->with('Section')->take(50)->latest()->get();
        return $datas;
    }
}
