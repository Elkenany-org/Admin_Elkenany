<?php

namespace Modules\Guide\Http\Controllers;


use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Guide\Entities\Guide_Section;
use Modules\Guide\Entities\Company;
use Modules\Guide\Entities\Company_Alboum_Images;
use Modules\Guide\Entities\Company_Social_media;
use Modules\Guide\Entities\Company_product;
use Modules\Guide\Entities\Company_address;
use Modules\Guide\Entities\Guide_Sub_Section;
use Modules\Guide\Entities\Company_transport;
use Modules\Guide\Entities\Company_gallary;
use Modules\Guide\Entities\Companies_Sec;
use Modules\Countries\Entities\Country;
use Modules\Cities\Entities\City;
use App\Noty;
use App\Social;
use Session;
use Image;
use File;
use View;
class CompaniesController extends Controller
{
    use ApiResponse;
    public function __construct()
    {
        $sections = Guide_Section::latest()->get();
        View::share([
            'sections' => $sections,
        ]);
    }

    # index
    public function Index()
    {
        $companies = Company::with('sections','SubSections')->orderby('sort')->paginate(100);
        return view('guide::companies.companies',compact('companies'));
    }

    # company result
    public function companyajax(Request $request)
    {
        $datas = Company::where('name' , 'like' , "%". $request->search ."%")->with('sections','SubSections')->take(50)->latest()->get();
        return $datas;
    }

    # edit
    public function sort(Request $request)
    {
        $companyso = Company::where('id',$request->id)->first();

        $comso = Company::where('sort',$request->sort)->first();

        if($comso){
            $comso->sort = $companyso->sort;

            $comso->save();

            $companyso->sort = $request->sort;

            $companyso->save();
    
            Session::flash('success','تم حفظ التعديلات');
            MakeReport('بتحديث الشركة '.$companyso->name);
            return back();
        }else{
            $companyso->sort = $request->sort;

            $companyso->save();
    
            Session::flash('success','تم حفظ التعديلات');
            MakeReport('بتحديث قسم فرعي '.$companyso->name);
            return back();
        }

        
    }

    # add
    public function Add()
    {
        $social    = Social::latest()->get();
        $countries = Country::latest()->get();
        $subs = Guide_Sub_Section::get();
        return view('guide::companies.add_company_setps',compact('social','countries','subs'));
    }

    # get cities
    public function Getcities(Request $request)
    {
        $datas = City::where('country_id',$request->country)->latest()->get();
        return $datas;
    }

    # store
    public function Store(Request $request)
    {
        $validator = Validator::make($request->all(),[
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
            'faxs'           => 'required',
     
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if(!$request->SubSections){
            return redirect()->back()->with(['danger'=>'يجب تحديد قسم فرعي']);
        }
        $company = new Company;
        DB::transaction(function () use($request,$company){
            $company->name = $request->name;
            $company->paied = $request->paied;
            $company->address = $request->address;
            $company->short_desc = $request->short_desc;
            $company->manage_phone = $request->manage_phone;
            $company->manage_email = $request->manage_email;
            $company->about = $request->about;
            $company->latitude = $request->latitude;
            $company->longitude = $request->longitude;
            $company->country_id = $request->country_id;
            $company->city_id = $request->city_id;

            if (count($request->mobiles) > 0) {
                $mobiles = json_encode($request->mobiles);
                $company->mobiles = $mobiles;
            }
            if (count($request->phones) > 0) {
                $phones = json_encode($request->phones);
                $company->phones = $phones;
            }
            if (count($request->emails) > 0) {
                $emails = json_encode($request->emails);
                $company->emails = $emails;
            }
            if (count($request->faxs) > 0) {
                $faxs = json_encode($request->faxs);
                $company->faxs = $faxs;
            }
            if (!is_null($request->image)) {
                $company->image = $this->storeImage($request->image,'company/images');
//                $photo = $request->image;
//                $name = date('d-m-y') . time() . rand() . '.' . $photo->getClientOriginalExtension();
//                Image::make($photo)->save('uploads/company/images/' . $name);
//                $company->image = $name;
            }
            $company->save();

            $company->sort = $company->id;
            $company->save();

            //social
            if (!is_null($request->social_link)) {
                foreach (array_combine($request->social_id, $request->social_link) as $social_id => $social_link) {
                    if ($social_link != null) {
                        Company_Social_media::insert(['social_link' => $social_link, 'company_id' => $company->id, 'social_id' => $social_id]);
                    }
                }
            }


            $loca = $request->loca;
            $lat = $request->lat;
            $long = $request->long;
            $type = $request->type;
            $location = [];


            foreach ($loca as $key => $value) {
                $location[$value] = [
                    'latt' => $lat[$key],
                    'lng' => $long[$key],
                    'type' => $type[$key],
                ];
            }

            foreach ($location as $key => $value) {
                $data = json_encode($value);
                $data = json_decode($data);

                if ($key != null && $data->latt != null && $data->lng != null) {
                    $item = new Company_address;
                    $item->address = $key;
                    $item->latitude = $data->latt;
                    $item->longitude = $data->lng;
                    $item->company_id = $company->id;
                    $item->type = $data->type;
                    $item->save();
                }
            }


            $datass = Guide_Sub_Section::with('Section')->whereIn('id', $request->SubSections)->get();

            if ($datass) {
                foreach ($datass as $s) {
                    $section = new Companies_Sec;
                    $section->sub_section_id = $s->id;
                    $section->section_id = $s->section_id;
                    $section->company_id = $company->id;
                    $section->save();
                }
            }
            MakeReport('بإضافة الشركة '.$company->name);

        });

        return redirect()->route('editcompany', ['id' => $company->id])->with(['success'=>'تم الحفظ']);
       
    }

    # edit
    public function Edit($id)
    {
        $companies = Company::with('CompanyAlboumImages','Companygallary','Companytransports.City','Companyaddress','CompanySocialmedia.Social','Companyproduct','LocalStockMember.Section')->where('id',$id)->first();
        $phone     = $companies->phones;
        $phones    = json_decode($phone);
        $email     = $companies->emails;
        $emails    = json_decode($email);
        $mobile    = $companies->mobiles;
        $mobiles   = json_decode($mobile);
        $fax       = $companies->faxs;
        $faxs      = json_decode($fax);
        $soc       = Company_Social_media::where('company_id' , $id)->pluck('social_id')->toArray();
        $social    = Social::whereNotIn('id',$soc)->with('CompanySocialmedia')->latest()->get();
        $cities    = City::where('country_id' , $companies->country_id)->latest()->get();
        $countries = Country::latest()->get();
        $transports    = Company_transport::latest()->get();
        $sectionss = Guide_Sub_Section::get();
        $majs = Companies_Sec::where('company_id' , $id)->pluck('section_id')->toArray();
        $secs = Companies_Sec::where('company_id' , $id)->pluck('sub_section_id')->toArray();
        return view('guide::companies.edit_company',compact('companies','majs','secs','sectionss','countries','faxs','phones','emails','mobiles','transports','social','cities'));
    }

    # update
    public function Update(Request $request)
    {
        $request->validate([
            'name'          => 'required|max:500',
            'address'       => 'required',
            'paied'         => 'required',
            'short_desc'    => 'required',
            'manage_phone'  => 'required',
            'manage_email'  => 'required',
            'about'         => 'required',
        ]);

        $company = Company::where('id',$request->id)->first();
        $company->name          = $request->name;
        $company->address       = $request->address;
        $company->short_desc    = $request->short_desc;
        $company->about         = $request->about;
        $company->paied         = $request->paied;
        $company->manage_phone  = $request->manage_phone;
        $company->manage_email  = $request->manage_email;
        $company->latitude      =  $request->latitude ;
        $company->longitude     =  $request->longitude ;
        $company->country_id    = $request->country_id;
        $company->city_id       = $request->city_id;

     
        if(!is_null($request->image))
        {
            File::delete('uploads/company/images/'.$company->image);
            $company->image= $this->storeImage($request->image,'company/images');
//            $photo=$request->image;
//            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
//            Image::make($photo)->save('uploads/company/images/'.$name);
//            $company->image=$name;
        }

        $company->save();
        Companies_Sec::where('company_id',$company->id)->delete();

        $datass  = Guide_Sub_Section::with('Section')->whereIn('id',$request->SubSections)->get();

        if($datass)
            {
                foreach($datass as $s){
                    $section = new Companies_Sec;
                    $section->sub_section_id = $s->id;
                    $section->section_id = $s->section_id;
                    $section->company_id = $company->id;
                    $section->save();
                }
            }
        Session::flash('success','تم حفظ التعديلات');
        MakeReport('بتحديث الشركة '.$company->name);
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

        $company = Company::where('id',$request->id)->first();
        if(count($request->mobiles) > 0)
        { 
            $mobiles    = json_encode($request->mobiles);
            $company->mobiles= $mobiles;
        }
        if(count($request->phones) > 0)
        {
            $phones     = json_encode($request->phones);
            $company->phones = $phones; 
        }
        if(count($request->emails) > 0)
        {
            $emails     = json_encode($request->emails);
            $company->emails=$emails;  
        }

        if(count($request->faxs) > 0)
        {
            $faxs     = json_encode($request->faxs);
            $company->faxs=$faxs;  
        }

        $company->save();
        
      
        Session::flash('success','تم حفظ التعديلات');
        MakeReport('بتحديث الشركة '.$company->name);
        return back();
    }

      # update local
      public function Updatelocal(Request $request)
      {
   

        $company = Company::where('id',$request->id)->first();

        Company_address::where('company_id',$company->id)->delete();
        if(!is_null($request->loca) || !is_null($request->lat) || !is_null($request->long))
        {
            $loca      = $request->loca;
            $lat       = $request->lat;
            $long      = $request->long;
            $type      = $request->type;
            $location  = [];

        
            foreach ($loca as $key => $value)
            {
                $location[$value] = [
                    'latt'    => $lat[$key],
                    'lng'     => $long[$key],
                    'type'     => $type[$key],
                ];
            }
            foreach($location as $key => $value)
            {
                $data     = json_encode($value);
                $data     = json_decode($data);

                if($key != null && $data->latt != null &&  $data->lng != null)
                {
                    $item = new Company_address;
                    $item->address    = $key;
                    $item->latitude  = $data->latt;
                    $item->longitude = $data->lng;
                    $item->company_id  = $company->id;
                    $item->type  = $data->type;
                    $item->save();
                }
            }
        }
        Session::flash('success','تم حفظ التعديلات');
        MakeReport('بتحديث العناوين '.$company->name);
        return back();
    }

    # delete 
    public function Delete(Request $request)
    {
        $company = Company::where('id',$request->id)->first();
        File::delete('uploads/company/images/'.$company->image);
        Session::flash('success','تم الحذف');
        MakeReport('بحذف شركة '.$company->name);
        $company->delete();
        return back();
    }

    # get sub sections ajax
    public function GetSubSections(Request $request)
    {
        $datas = Guide_Sub_Section::where('section_id',$request->section_id)->take(10)->latest()->get();
        return $datas;
    }

    # get sub sections ajax
    public function GetSubSectionsserch(Request $request)
    {
        $datas = Guide_Sub_Section::where('name' , 'like' , "%". $request->search ."%")->take(10)->latest()->get();
        return $datas;
    }

    # storesocial
    public function storesocial(Request $request)
    {

        
        //social

        $company = Company::where('id',$request->id)->first();
        Company_Social_media::where('company_id',$company->id)->delete();
        foreach(array_combine($request->social_id, $request->social_link) as $social_id => $social_link)
        {
            if(!is_null($social_link))
            {
            Company_Social_media::insert(['social_link' => $social_link, 'company_id' => $company->id,'social_id' => $social_id]);
            }
        }
    
        Session::flash('success','تم حفظ التعديلات');
        MakeReport('بتحديث مواقع التواصل لشركة  ' .$company->name);
 
        return back();
    }

    # updatesocial
    public function Updatesocial(Request $request)
    {

        $request->validate([
            'social_link'       => 'required|max:500',
        ]);
        $company = Company::where('id',$request->id)->first();
        foreach(array_combine($request->social_id, $request->social_link) as $social_id => $social_link)
        {
            Company_Social_media::where('id',$social_id)->where('company_id',$request->id)->update(['social_link' => $social_link,]);
        }
    
        Session::flash('success','تم حفظ التعديلات');
        MakeReport('بتحديث مواقع التواصل لشركة  ' .$company->name);
 
        return back();
    }

    # images 
    public function storeImages(Request $request)
    {
        $gallary = Company_gallary::where('id',$request->id)->first();
        $company = Company::where('id',$gallary->company_id)->first();

        if($request->hasfile('images'))
        {
        foreach($request->images as $image){

        $photo=$image;
        $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
        Image::make($photo)->save('uploads/company/alboum/'.$name);
        
            $img = new Company_Alboum_Images;
            $img->gallary_id     = $gallary->id;
            $img->company_id     = $company->id;
            $img->image = $name;
            $img->save();
        }
        }
 
        Session::flash('success','تم الاضافة');
        MakeReport('باضافة صور '.$company->name);
        return back();
    }

    # delete image
    public function DeleteImage(Request $request)
    {

        $image = Company_Alboum_Images::where('id',$request->id)->first();
        $company = Company::where('id',$image->company_id)->first();
        if($image->image != 'default.png')
        {
                File::delete('uploads/company/alboum/'.$image->image);
        }
        MakeReport('بحذف صورة لشركة '.$company->name);
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

        $image = Company_Alboum_Images::findOrFail($request->edit_id);
        $image->note       = $request->edit_note;
    
        $image->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث وصف الصورة  '.$image->note);
        return back();
    }

    # Company_product store 
    public function storeproduct(Request $request)
    {

    $request->validate([
        'product_name'  => 'required|max:500',
        'product_image' => 'required',
    ]);

        $company = Company::where('id',$request->product_id)->first();
        $product = new Company_product;
        $product->name           = $request->product_name;
        $product->company_id     = $company->id;

        if(!is_null($request->product_image))
        {
            $photo=$request->product_image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/company/product/'.$name);
            $product->image = $name;
        }

        $product->save();


//        $noty = new Noty;
//        $noty->title           = '  اشعار لشركة '.$company->name;
//        $noty->desc           = '  تم اضافة منتاجات جديدة لشركة '.$company->name;
//        $noty->company_id     = $company->id;
//        $noty->pro_id     = $product->id;
//        $noty->save();

        # send notification fcm
//        $title = trans($noty->title);
//        $body  =  $noty->desc;
//        $data  = ['foo'=>'bar'];
//        $image = '';
//        NotiForTopic($title,$body,$data,$image);

//        Session::flash('success','تم الاضافة');
        MakeReport('باضافة منتج '.$product->name.' لشركة '.$company->name);
        
        return redirect()->back()->with(['success'=>'تم الاضافة']);
    }


    # Company_product update
    public function updateproduct(Request $request)
    {

        $request->validate([
            'edit_product_name'  => 'required|max:500',
            'edit_product_image' => 'mimes:jpeg,png,jpg,gif|',
        ]);



        $product = Company_product::findOrFail($request->edit_product_id);
        $product->name = $request->edit_product_name;
    

        if(!is_null($request->edit_product_image))
        {
            $photo=$request->edit_product_image;
            File::delete('uploads/company/product/'.$product->image);
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/company/product/'.$name);
            $product->image = $name;
        }

        
        $product->save();
        $company = Company::where('id',$product->company_id)->first();
        Session::flash('success','تم الاضافة');
        MakeReport('بتعديل منتج '.$product->name .' لشركة '.$company->name);
        
        return back();
    }

    # delete product
    public function Deleteproduct(Request $request)
    {

        $product = Company_product::where('id',$request->id)->first();

        File::delete('uploads/company/product/'.$product->image);
        MakeReport('بحذف المنتج '.$product->name);
        $product->delete();
        Session::flash('success','تم الحذف');
        return back();
    }	

    # Company_transport
    public function storetransport(Request $request)
    {

        $request->validate([
            'transport_product_name'  => 'required|max:500',
            'transport_product_price' => 'required',
            'transport_product_type'  => 'required',
        ]);



        $transport = new Company_transport;
        $transport->product_name  = $request->transport_product_name;
        $transport->price         = $request->transport_product_price;
        $transport->product_type  = $request->transport_product_type;
        $transport->city_id       = $request->city_id;
        $transport->company_id    = $request->company_id;
        $transport->save();
        $company = Company::where('id',$transport->company_id)->first();
        Session::flash('success','تم الاضافة');
        MakeReport('باضافة طريقة شحن '.$transport->product_name .' لشركة '.$company->name);
        
        return back();
    }

    # Company_transport
    public function updatetransport(Request $request)
    {

        $request->validate([
            'edit_transport_product_name'  => 'required|max:500',
            'edit_transport_product_price' => 'required',
            'edit_transport_product_type'  => 'required',
        ]);



        $transport = Company_transport::findOrFail($request->edit_transport_id);
        $transport->product_name  = $request->edit_transport_product_name;
        $transport->price         = $request->edit_transport_product_price;
        $transport->product_type  = $request->edit_transport_product_type;
        $transport->city_id       = $request->edit_city_id;
        $transport->save();
        $company = Company::where('id',$transport->company_id)->first();
        Session::flash('success','تم التحديث');
        MakeReport('بتحديث تكلفة شحن '.$transport->product_name .' لشركة '.$company->name);
        
        return back();
    }


    # delete transport
    public function Deletetransport(Request $request)
    {

        $transport = Company_transport::where('id',$request->id)->first();
 
        MakeReport('بحذف تكلفة شحن '.$transport->product_name);
        $transport->delete();
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



        $gallary = new Company_gallary;
        $gallary->name  = $request->gallary_name;
        $gallary->company_id    = $request->company_id;
        if(!is_null($request->gallary_image))
        {
            $photo=$request->gallary_image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/gallary/avatar/'.$name);
            $gallary->image =$name;
        }
        $gallary->save();
        $company = Company::where('id',$gallary->company_id)->first();
        Session::flash('success','تم الاضافة');
        MakeReport('باضافة  البوم '.$gallary->name .' لشركة '.$company->name);
        
        return back();
    }

    # gallary
    public function updategallary(Request $request)
    {

        $request->validate([
            'edit_gallary_name'  => 'required|max:500',
            'edit_gallary_image' => 'mimes:jpeg,png,jpg,gif,svg',
        ]);



        $gallary = Company_gallary::findOrFail($request->edit_gallary_id);
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
        $company = Company::where('id',$gallary->company_id)->first();
        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  البوم '.$gallary->name.' لشركة '.$company->name);
        
        return back();
    }


    # delete gallary
    public function Deletegallary(Request $request)
    {

        $gallary = Company_gallary::where('id',$request->id)->first();
        $company = Company::where('id',$gallary->company_id)->first();
        MakeReport('بحذف  البوم '.$gallary->name.' لشركة '.$company->name);
        $gallary->delete();
        Session::flash('success','تم الحذف');
        return back();
    }	

    # gallary
    public function gallary($id)
    {
        $gallary = Company_gallary::with('CompanyAlboumImages')->where('id',$id)->first();
     
        return view('guide::companies.gallary',compact('gallary'));
    }
 
}
