<?php

namespace Modules\Us\Http\Controllers;


use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Us\Entities\Office;
use Session;
use Image;
use File;
use View;
class OfficesController extends Controller
{
  
    

    # index
    public function Index()
    {
        $offices = Office::paginate(100);
        return view('us::offices.offices',compact('offices'));
    }

    
    # add
    public function Add()
    {

        return view('us::offices.add_office_setps');
    }

   
    # store
    public function Store(Request $request)
    {
         $request->validate([
            'name'           => 'required|max:500',
            'address'        => 'required',
            'mobiles'        => 'required',
            'phones'         => 'required',
            'emails'         => 'required',
            'faxs'         => 'required',
     
        ]);

        $office = new Office;
        $office->name            = $request->name;
        $office->address         = $request->address;
        $office->latitude        =  $request->latitude ;
        $office->longitude       =  $request->longitude ;
        $office->desc       = $request->desc;
     
        if(count($request->mobiles) > 0)
        {
            $mobiles    = json_encode($request->mobiles);
            $office->mobiles= $mobiles;     
        }
        if(count($request->phones) > 0)
        {
            $phones     = json_encode($request->phones);
            $office->phones = $phones;
        }
        if(count($request->emails) > 0)
        {
            $emails     = json_encode($request->emails);
            $office->emails=$emails; 
        }
        if(count($request->faxs) > 0)
        {
            $faxs     = json_encode($request->faxs);
            $office->faxs=$faxs; 
        }
       
        $office->save();

    
        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة مقر '.$office->name);
        return redirect()->route('editoffices', ['id' => $office->id]);
       
    }

    # edit
    public function Edit($id)
    {
        $offices = Office::where('id',$id)->first();

        $phone     = $offices->phones;
        $phones    = json_decode($phone);
        $email     = $offices->emails;
        $emails    = json_decode($email);
        $mobile    = $offices->mobiles;
        $mobiles   = json_decode($mobile);
        $fax       = $offices->faxs;
        $faxs      = json_decode($fax);
      
        return view('us::offices.edit_office',compact('offices','faxs','phones','emails','mobiles',));
    }

    # update
    public function Update(Request $request)
    {
        $request->validate([
            'name'          => 'required|max:500',
            'address'       => 'required',
         
      
        ]);

        if($request->status === "0"){
            $feeds = Office::latest()->get();
            foreach($feeds as $fad){
                $fod = Office::where('id',$fad->id)->first();
                $fod->status       = 1;
                $fod->update();

            }
        }

        $office = Office::where('id',$request->id)->first();
        $office->name          = $request->name;
        $office->address       = $request->address;
        $office->latitude      =  $request->latitude ;
        $office->longitude     =  $request->longitude ;
        $office->desc       = $request->desc;

        $office->status       = $request->status;
     
        $office->save();

        
        Session::flash('success','تم حفظ التعديلات');
        MakeReport('بتحديث مقر '.$office->name);
        return back();
    }

    # update contact
    public function Updatecontact(Request $request)
    {
        $request->validate([
            'mobiles'       => 'required',
            'phones'        => 'required',
            'emails'        => 'required',
            'faxs'        => 'required',
        ]);

        $office = Office::where('id',$request->id)->first();
        if(count($request->mobiles) > 0)
        { 
            $mobiles    = json_encode($request->mobiles);
            $office->mobiles= $mobiles;
        }
        if(count($request->phones) > 0)
        {
            $phones     = json_encode($request->phones);
            $office->phones = $phones; 
        }
        if(count($request->emails) > 0)
        {
            $emails     = json_encode($request->emails);
            $office->emails=$emails;  
        }

        if(count($request->faxs) > 0)
        {
            $faxs     = json_encode($request->faxs);
            $office->faxs=$faxs;  
        }

        $office->save();
        
      
        Session::flash('success','تم حفظ التعديلات');
        MakeReport('بتحديث مقر '.$office->name);
        return back();
    }

     

    # delete 
    public function Delete(Request $request)
    {
        $office = Office::where('id',$request->id)->first();
        Session::flash('success','تم الحذف');
        MakeReport('بحذف مقر '.$office->name);
        $office->delete();
        return back();
    }

   
}
