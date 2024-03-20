<?php

namespace Modules\Guide\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Images_Home;
use App\Main;
use App\MainImages;
use App\Noty;
use App\Traits\ApiResponse;
use App\Traits\SearchReg;
use Auth;
use Carbon\Carbon;
use Date;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use Modules\Analysis\Entities\Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\FodderStock\Entities\Stock_Fodder_Section;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use Modules\Guide\Entities\Banners;
use Modules\Guide\Entities\Company;
use Modules\Guide\Entities\Guide_Section;
use Modules\Guide\Entities\Guide_Sub_Section;
use Modules\Guide\Entities\Services;
use Modules\Guide\Http\Services\GuideService;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\LocalStock\Entities\Local_Stock_Sub;
use Modules\LocalStock\Entities\Sec_All;
use Modules\Magazines\Entities\Mag_Section;
use Modules\Magazines\Entities\Magazine;
use Modules\Magazines\Entities\Magazine_Sec;
use Modules\News\Entities\News;
use Modules\News\Entities\News_Section;
use Modules\Shows\Entities\Show;
use Modules\Shows\Entities\Shows_Sec;
use Modules\Shows\Entities\Show_Section;
use Modules\Store\Entities\Customer;
use Modules\Store\Entities\Store_Ads;
use Modules\Store\Entities\Store_Section;
use Modules\SystemAds\Entities\System_Ads;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Transformers\LogoBannerResource;
use Modules\Tenders\Entities\Tender;
use Modules\Tenders\Entities\Tender_Section;
use Session;
use URL;
use Validator;
use View;
use Google_Client;
use Google_Service_Analytics;

class ApiSectionController extends Controller
{
    use SearchReg,ApiResponse;

    public function adsnot()
    {

        $list = [];

        $today = Carbon::now(); 

        $adsall = System_Ads::where('end_date', '=', $today->format('Y-m-d'))->where('type','notification')->where('not_time', '=', date("H:i", strtotime($today)))->where('status','1')->where('app','mop')->get();
        foreach($adsall as $key =>$val){
           
            # send notification fcm
            $title = trans($val->title);
            $body  =  $val->desc;
            $data  = ['foo'=>'bar'];
            $image = '';
            NotiForTopic($title,$body,$data,$image);


            $list['result'][$key]['id']        = $val->id;
            $list['result'][$key]['title']     = $val->title;
            $list['result'][$key]['desc']      = $val->desc;
        }

        
     
        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }
    # edit user
    public function profile(Request $request)
    {
        $list = [];
        $customer = Customer::where('id', session('customer')->id)->first();
         
        $list['id']         = $customer->id;
        $list['name']       = $customer->name;
        $list['phone']      = $customer->phone;
        $list['email']      = $customer->email;
        $list['image']      = URL::to('uploads/customers/avatar/'.$customer->avatar);
        if($customer->memb == 1){
            $list['state']      = 'premium';
        }else{
            $list['state']      = 'free';
        }

        if($customer->company_id){
            $list['user_type']      = "recruiter";

        }else{
            $list['user_type']      = "user";
        }

        if($request->hasHeader('device') && $customer->company_id){
            $list['verified']      = $customer->verified_company;
            $list['company_id']      = $customer->company_id;
            $list['company_name']      = $customer->Company->name;

        }
        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    
    }


    # update user
    public function UpdateCustomer(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
        ]);
        
        foreach ((array) $validator->errors() as $value)
		{
			if(isset($value['name']))
			{
				$msg = 'name is required';
				return response()->json([
					'message'  => null,
					'error'    => $msg,
                ],400);
            }
			
        }

      

        $customer = Customer::where('id',session('customer')->id)->first();
        if(!is_null($request->name))
        {
            $customer->name     = $request->name;
        }
        if(!is_null($request->email))
        {
            $customer->email    = $request->email;
        }

        if(!is_null($request->phone))
        {
            $customer->phone     = $request->phone;
        }

        # password 
//        if(!is_null($request->password))
//        {
//            $customer->password = bcrypt($request->password);
//        }

        # upload avatar
        if(!is_null($request->avatar))
        {
            # delete avatar
            if($customer->avatar != 'default.png')
            {
                    File::delete('uploads/customers/avatar/'.$customer->avatar);
            }

            # upload new avatar
            if($request->hasHeader('device')){
                $file      = $request->file('avatar');
                $name =date('d-m-y').time().rand().'.'.$file->extension();
                $file->move('uploads/customers/avatar', $name);
            }else if($request->hasHeader('android')){
                $data = json_decode($request->avatar);
                $name = $this->StoreImageBase64($data,"customers/avatar","android");

            }else{
                $photo=$request->avatar;
                $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
                Image::make($photo)->save('uploads/customers/avatar/'.$name);
            }

            $customer->avatar=$name;
        }

        $customer->save();

        $list['id']         = $customer->id;
        $list['name']       = $customer->name;
        $list['phone']      = $customer->phone;
        $list['email']      = $customer->email;
        $list['image']      = URL::to('uploads/customers/avatar/'.$customer->avatar);
        if($customer->memb == 1){
            $list['state']      = 'premium';
        }else{
            $list['state']      = 'free';
        }


        return response()->json([
            'message'  => 'تم تعديل بياناتك',
            'error'    => null,
            'data'     => $list
        ],200);
    
      
    }


    public function ser(Request $request)
    {

        $search = $request->input("search");

        $keyword = $this->searchQuery($search);
        $list = [];

        $guidesubs = Guide_Sub_Section::with('Section','Company')->where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $companies = Company::where('name' , 'REGEXP' , $keyword)->with('sections','SubSections')->take(50)->latest()->get();
        $localsubs = Local_Stock_Sub::where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $foddersubs = Stock_Fodder_Sub::with('Section')->where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $magazines = Magazine::with('sections')->where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $news = News::where('title' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $showes = Show::with('Section','City','Country')->where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $stores = Store_Ads::with('StoreAdsimages')->where('title' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $tenders_sections = Tender_Section::where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $tenders = Tender::with('Section')->where('title' , 'REGEXP' , $keyword)->take(50)->latest()->get();

        $list['result1']=[];
        $list['result2']=[];
        $list['result3']=[];
        $list['result4']=[];
        $list['result5']=[];
        $list['result6']=[];
        $list['result7']=[];
        $list['result8']=[];
        $list['result9']=[];
        $list['result10']=[];
        if($request->hasHeader('device')){
            foreach ($guidesubs as $key => $not)
            {
                $list['result1'][$key]['id']         = $not->id;
                $list['result1'][$key]['name']       = $not->name;
                $list['result1'][$key]['count']       =count($not->Company);
                $list['result1'][$key]['image']       = $not->image_url;
                $list['result1'][$key]['section_id']       = $not->section_id;
                $list['result1'][$key]['type']       = 'guide_sub_sections';
            }

            foreach ($companies as $key => $not)
            {
                $list['result2'][$key]['id']         = $not->id;
                $list['result2'][$key]['name']       = $not->name;
                $list['result2'][$key]['image']       = $not->image_url;
                $list['result2'][$key]['short_desc'] =\Illuminate\Support\Str::limit($not->short_desc, $limit = 50, $end = '...');
                $list['result2'][$key]['address']       = $not->address;
                $list['result2'][$key]['section_id']       = $not->sections[0]->id;

                $list['result2'][$key]['type']       = 'companies';
            }

            foreach ($localsubs as $key => $not)
            {
                $list['result3'][$key]['id']         = $not->id;
                $list['result3'][$key]['name']       = $not->name;
                $list['result3'][$key]['image']       = $not->image_url;
                $list['result3'][$key]['section_id']       = $not->section_id;
                $list['result3'][$key]['type']       = 'local_stock_sub';


            }

            foreach ($foddersubs as $key => $not)
            {
                $list['result4'][$key]['id']         = $not->id;
                $list['result4'][$key]['name']       = $not->name;
                $list['result4'][$key]['section']       = $not->Section->name;
                $list['result4'][$key]['image']       = $not->image_url;
                $list['result4'][$key]['section_id']       = $not->section_id;
                $list['result4'][$key]['type']       = 'fodder_stock_sub';



            }

            foreach ($magazines as $key => $not)
            {
                $list['result5'][$key]['id']         = $not->id;
                $list['result5'][$key]['name']       = $not->name;
                $list['result5'][$key]['image']       = $not->image_url;
                $list['result5'][$key]['short_desc'] =\Illuminate\Support\Str::limit($not->short_desc, $limit = 50, $end = '...');
                $list['result5'][$key]['address']       = $not->address;
                $list['result5'][$key]['type']       = 'magazines';



            }

            foreach ($news as $key => $not)
            {
                $list['result6'][$key]['id']         = $not->id;
                $list['result6'][$key]['name']       = $not->title;
                $list['result6'][$key]['image']       = $not->image_url;
                $list['result6'][$key]['created_at']  = date('d-m-Y H:i:s', strtotime($not->created_at));
                $list['result6'][$key]['section_id']       = $not->section_id;
                $list['result6'][$key]['type']       = 'news';
            }

            foreach ($stores as $key => $not)
            {
                $list['result7'][$key]['id']         = $not->id;
                $list['result7'][$key]['name']       = $not->title;
                $list['result7'][$key]['price']       = $not->salary;
                $list['result7'][$key]['address']       = $not->address;
                if(count($not->StoreAdsimages)>0){
                    $list['result7'][$key]['image']       = $not->StoreAdsimages[0]->image_url;
                }
                $list['result7'][$key]['section_id']       = $not->section_id;
                $list['result7'][$key]['type']       = 'stores';

            }


            foreach ($showes as $key => $not)
            {
                $list['result8'][$key]['id']         = $not->id;
                $list['result8'][$key]['name']       = $not->name;
                $list['result8'][$key]['image']       = $not->image_url;
                $list['result8'][$key]['date']       = $not->time();
                $list['result8'][$key]['count']       = $not->view_count;
                $list['result8'][$key]['short_desc'] =\Illuminate\Support\Str::limit($not->desc, $limit = 50, $end = '...');
                $list['result8'][$key]['address']    ="{$not->Country->name} - {$not->City->name}";
                $list['result8'][$key]['type']       = 'showes';


            }
            foreach ($tenders as $key => $not)
            {
                $list['result9'][$key]['id']         = $not->id;
                $list['result9'][$key]['name']       = $not->title;
                $list['result9'][$key]['image']       = $not->image;
                $list['result9'][$key]['count']       = $not->view_count;
                $list['result9'][$key]['created_at']  = date('d-m-Y H:i:s', strtotime($not->created_at));
                $list['result9'][$key]['section_id']       = $not->section_id;
                $list['result9'][$key]['type']       = 'tenders';


            }

            foreach ($tenders_sections as $key => $not)
            {
                $list['result10'][$key]['id']         = $not->id;
                $list['result10'][$key]['name']       = $not->name;
                $list['result10'][$key]['image']       = $not->image;
                $list['result10'][$key]['date']       = date('d-m-Y H:i:s', strtotime($not->created_at));
                $list['result10'][$key]['count']       = $not->view_count;
                $list['result10'][$key]['type']       = 'tenders_sections';


            }
        }
        else{
            foreach ($guidesubs as $key => $not)
            {
                $list['result1'][$key]['id']         = $not->id;
                $list['result1'][$key]['name']       = $not->name;
                $list['result1'][$key]['type']       = 'guide_sub_sections';
            }

            foreach ($companies as $key => $not)
            {
                $list['result2'][$key]['id']         = $not->id;
                $list['result2'][$key]['name']       = $not->name;
                $list['result2'][$key]['type']       = 'companies';
            }

            foreach ($localsubs as $key => $not)
            {
                $list['result3'][$key]['id']         = $not->id;
                $list['result3'][$key]['name']       = $not->name;
                $list['result3'][$key]['type']       = 'local_stock_sub';



            }

            foreach ($foddersubs as $key => $not)
            {
                $list['result4'][$key]['id']         = $not->id;
                $list['result4'][$key]['name']       = $not->name;
                $list['result4'][$key]['type']       = 'fodder_stock_sub';



            }

            foreach ($magazines as $key => $not)
            {
                $list['result5'][$key]['id']         = $not->id;
                $list['result5'][$key]['name']       = $not->name;
                $list['result5'][$key]['type']       = 'magazines';



            }

            foreach ($news as $key => $not)
            {
                $list['result6'][$key]['id']         = $not->id;
                $list['result6'][$key]['name']       = $not->title;
                $list['result6'][$key]['type']       = 'news';

            }

            foreach ($stores as $key => $not)
            {
                $list['result7'][$key]['id']         = $not->id;
                $list['result7'][$key]['name']       = $not->title;
                $list['result7'][$key]['type']       = 'stores';

            }


            foreach ($showes as $key => $not)
            {
                $list['result8'][$key]['id']         = $not->id;
                $list['result8'][$key]['name']       = $not->name;
                $list['result8'][$key]['type']       = 'showes';


            }

            foreach ($tenders as $key => $not)
            {
                $list['result9'][$key]['id']         = $not->id;
                $list['result9'][$key]['name']       = $not->title;
                $list['result9'][$key]['type']       = 'tenders';


            }
            foreach ($tenders_sections as $key => $not)
            {
                $list['result10'][$key]['id']         = $not->id;
                $list['result10'][$key]['name']       = $not->name;
                $list['result10'][$key]['type']       = 'tenders_sections';


            }

        }
        $list['result']=array_merge($list['result1'],$list['result2'],$list['result3'],$list['result4'],$list['result5'],$list['result6'],$list['result7'],$list['result8'],$list['result9'],$list['result10']);

        for ($i=0;$i<10;$i++){
            array_shift($list);
        }
        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    public function serCompanies(Request $request)
    {

        $search = $request->input("search");

        $keyword = $this->searchQuery($search);
        $list = [];

        $companies = Company::where('name' , 'REGEXP' , $keyword)->with('sections','SubSections')->get();
            foreach ($companies as $key => $not)
            {
                $list['result'][$key]['id']         = $not->id;
                $list['result'][$key]['name']       = $not->name;
            }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show noty
    public function nots(Request $request)
    {
        $list = [];
        $list['nots'] = [];
        if($request->header('Authorization'))
        {
            $token = explode(' ',$request->header('Authorization'))[1];
                $customer = Customer::where('api_token',$token)->first();
                if(!$customer){
                    return $this->ErrorMsg('TOKEN_IS_INVALID');
                }

            $nots = Noty::with('Company','Companyproduct')->where('created_at','>',$customer->created_at)->latest()->take(50)->get();
            foreach ($nots as $key => $not)
            {
                $list['nots'][$key]['id']          = $not->id;
                $list['nots'][$key]['title']       = $not->title;
                $list['nots'][$key]['desc']        = $not->desc;
                $list['nots'][$key]['image']       = isset($not->Company) ? $not->Company->image_url : $not->image;
                if($request->hasHeader('device')){
                    $list['nots'][$key]['created_at']       = $not->created_at;
                    $list['nots'][$key]['time'] = $not->created_at->diffForHumans();

                }
                if(!is_null($not->pro_id)){
                    $list['nots'][$key]['product_id']       = $not->Companyproduct->id;
                    $list['nots'][$key]['product_name']        = $not->Companyproduct->name;
                    $list['nots'][$key]['product_image']       = $not->Companyproduct->image_url;
                }else{
                    $list['nots'][$key]['product_id']       = null;
                    $list['nots'][$key]['product_name']        = null;
                    $list['nots'][$key]['product_image']       = null;
                }
            }
        }
        return $this->ReturnData($list);
    }


    # show noty
    public function poups()
    {
        $list = [];
        $poob  = [];
    
        $pop = System_Ads::where('type','popup')->where('status','1')->inRandomOrder()->first();

        if(!$pop){
            $list['popup'] = null;
        }else{
            $poob['id']          = $pop->id;
            $file = substr($pop->link, -3);
            if($file == 'mp4'){
                $poob['media']      = null;
                $poob['link']       = URL::to('uploads/full_images/'.$pop->link);
            }else{
                $poob['link']        = null;
                $poob['media']       = URL::to('uploads/full_images/'.$pop->link);
            }
            $list['popup'] = $poob;
        }

        return $this->ReturnData($list);
    }
    
    # show home sectors
    public function sectors(Request $request)
    {

        $today = Carbon::now();

        $adsall = System_Ads::where('end_date', '<', $today->format('Y-m-d'))->where('status','1')->get();
        foreach($adsall as $val){
            $val->status = '3';
            $val->save();
        }

        $sections = Main::get();
        $sections->map(function ($section){$section['image'] = $section->image_url; return $section;});
        $list = [];
        $last = [];
        $count = [];
        $ran = [];
        $lastl = [];
        $countl = [];
        $ranl = [];
        $lastf = [];
        $ranf = [];
        $lastn = [];
        $countn = [];
        $lasts = [];
        $counts = [];

        $stocka = [];
        $stockaf = [];
        $guidea = [];
        $storea = [];
        $newsa  = [];
        $logs  = [];
        $poob  = [];

        if($request->hasHeader('device')){
            $gallarys = Images_Home::inRandomOrder()->get();
            $gallarys->map(function($gallery){
                $gallery['image'] = URL::to('uploads/main/'.$gallery->image);
                return $gallery;
            });
            $list['banners'] = $gallarys;
        }

        $list['sectors'] = $sections;
        $page = System_Ads_Pages::with('SystemAds')->where('type','home')->pluck('ads_id')->toArray();

        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
        $list['logos']= LogoBannerResource::collection($logos);
        $pop = System_Ads::where('type','popup')->where('status','1')->inRandomOrder()->first();

        if(!$pop){
            $list['popup'] = null;
        }else{
            $poob['id']          = $pop->id;
            $poob['link']        = $pop->link;
            $poob['media']       = $pop->image_url;

            $list['popup'] = $poob;
        }



        # check recomndation system
        if(!is_null($request->header('Authorization')))
        {
            $token = $request->header('Authorization');
            $token = explode(' ',$token);
            if(count( $token) == 2)
            {

                $customer = Customer::where('api_token',$token[1])->first();
                if($customer)
                {

                    $user_data = User_Data_Analysis::where([['user_id',$customer->id]])->orderBy('use_count' , 'desc')->first();


                    if($user_data){

                        $main_keyword = Data_Analysis_Keywords::where([['id',$user_data->keyword_id]])->first();

                        $type = $main_keyword->type;

                        $list['type']        = $type;

                        # guide sub
                        $section = Guide_Section::where('id',$type)->first();
                        $sublast = Guide_Sub_Section::with('Company')->where('section_id',$section->id)->latest()->first();


                        $last['id']          = $sublast->id;
                        $last['name']        = $sublast->name;
                        $last['type']        = 'guide';
                        $last['image']       = URL::to('uploads/sections/avatar/'.$sublast->image);
                        $last['companies_count']     = count($sublast->Company);


                        $list['recomandtion'][]        = $last;

                        $subcount = Guide_Sub_Section::with('Company')->where('section_id',$section->id)->where('id', '<>',$sublast->id)->orderBy('view_count' , 'desc')->first();

                        $count['id']          = $subcount->id;
                        $count['name']        = $subcount->name;
                        $count['type']        = 'guide';
                        $count['image']       = URL::to('uploads/sections/avatar/'.$subcount->image);
                        $count['companies_count']     = count($subcount->Company);


                        $list['recomandtion'][]        = $count;

                        $subran = Guide_Sub_Section::with('Company')->where('section_id',$section->id)->where('id', '<>',$subcount->id)->where('id', '<>',$sublast->id)->inRandomOrder()->first();

                        $ran['id']          = $subran->id;
                        $ran['name']        = $subran->name;
                        $ran['type']        = 'guide';
                        $ran['image']       = URL::to('uploads/sections/avatar/'.$subran->image);
                        $ran['companies_count']     = count($subran->Company);


                        $list['recomandtion'][]        = $ran;


                        # guide rec

                        $guisubs = Guide_Sub_Section::with('Company')->where('section_id',$section->id)->take(10)->latest()->get();

                        foreach ($guisubs as $key => $gui)
                        {
                            $guidea['id']          = $gui->id;
                            $guidea['name']        = $gui->name;
                            $guidea['type']        = 'guide';
                            $guidea['image']       = URL::to('uploads/sections/avatar/'.$gui->image);
                            $guidea['companies_count']     = count($gui->Company);

                            $list['guide'][]        = $guidea;
                        }


                        # local sub
                        $sectionl = Local_Stock_Sections::where('id',$type)->first();

                        $majs = Sec_All::where('section_id' , $sectionl->id)->pluck('sub_id')->toArray();
                        if(count($majs) == 0)
                        {
                            $sublastl = Local_Stock_Sub::where('section_id',$sectionl->id)->with('LocalStockMembers')->latest()->first();
                        }else{
                            $sublastl = Local_Stock_Sub::where('section_id',$sectionl->id)->orWhereIn('id',$majs)->with('LocalStockMembers')->latest()->first();
                        }

                        $lastl['id']          = $sublastl->id;
                        $lastl['name']        = $sublastl->name;
                        $lastl['type']        = 'localstock';
                        $lastl['image']       = URL::to('uploads/sections/sub/'.$sublastl->image);
                        $lastl['members']     = count($sublastl->LocalStockMembers);


                        $list['recomandtion'][]        = $lastl;

                        if(count($majs) == 0)
                        {
                            $subcountl = Local_Stock_Sub::where('section_id',$sectionl->id)->where('id', '<>',$sublastl->id)->with('LocalStockMembers')->orderBy('view_count' , 'desc')->first();
                        }else{
                            $subcountl = Local_Stock_Sub::where('section_id',$sectionl->id)->orWhereIn('id',$majs)->where('id', '<>',$sublastl->id)->with('LocalStockMembers')->orderBy('view_count' , 'desc')->first();
                        }

                        $countl['id']          = $subcountl->id;
                        $countl['name']        = $subcountl->name;
                        $countl['type']        = 'local';
                        $countl['image']       = URL::to('uploads/sections/sub/'.$subcountl->image);
                        $countl['members']     = count($subcountl->LocalStockMembers);


                        $list['recomandtion'][]        = $countl;

                        if(count($majs) == 0)
                        {
                            $subranl = Local_Stock_Sub::where('section_id',$sectionl->id)->where('id', '<>',$subcountl->id)->where('id', '<>',$sublastl->id)->with('LocalStockMembers')->inRandomOrder()->first();
                        }else{
                            $subranl = Local_Stock_Sub::where('section_id',$sectionl->id)->where('id', '<>',$subcountl->id)->where('id', '<>',$sublastl->id)->orWhereIn('id',$majs)->with('LocalStockMembers')->inRandomOrder()->first();
                        }

                        $ranl['id']          = $subranl->id;
                        $ranl['name']        = $subranl->name;
                        $ranl['type']        = 'local';
                        $ranl['image']       = URL::to('uploads/sections/sub/'.$subranl->image);
                        $ranl['members']     = count($subranl->LocalStockMembers);


                        $list['recomandtion'][]        = $ranl;


                        # stock rec

                        $stockrec = Local_Stock_Sub::with('LocalStockMembers')->where('section_id',$sectionl->id)->take(5)->latest()->get();

                        foreach ($stockrec as $key => $sti)
                        {
                            $stocka['id']          = $sti->id;
                            $stocka['name']        = $sti->name;
                            $stocka['type']        = 'local';
                            $stocka['image']       = URL::to('uploads/sections/sub/'.$sti->image);
                            $stocka['members']     = count($sti->LocalStockMembers);

                            $list['stock'][]        = $stocka;
                        }


                        #fodder
                        $sectionsf = Stock_Fodder_Section::with('FodderStocks')->where('id',$type)->first();
                        $sublastf = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->latest()->first();

                        $lastf['id']          = $sublastf->id;
                        $lastf['name']        = $sublastf->name;
                        $lastf['type']        = 'fodder';
                        $lastf['image']       = URL::to('uploads/sections/avatar/'.$sublastf->image);
                        $lastf['members']     = count($sublastf->FodderStocks);


                        $list['recomandtion'][]        = $lastf;

                        $subranf = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->where('id', '<>',$sublastf->id)->inRandomOrder()->first();

                        $ranf['id']          = $subranf->id;
                        $ranf['name']        = $subranf->name;
                        $ranf['type']        = 'fodder';
                        $ranf['image']       = URL::to('uploads/sections/avatar/'.$subranf->image);
                        $ranf['members']     = count($subranf->FodderStocks);


                        $list['recomandtion'][]        = $ranf;

                        # stock rec

                        $stockrecf = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->take(5)->latest()->get();

                        foreach ($stockrecf as $key => $stf)
                        {
                            $stockaf['id']          = $stf->id;
                            $stockaf['name']        = $stf->name;
                            $stockaf['type']        = 'fodder';
                            $stockaf['image']       = URL::to('uploads/sections/avatar/'.$stf->image);
                            $stockaf['members']     = count($stf->FodderStocks);

                            $list['stock'][]        = $stockaf;
                        }


                        #news
                        $sectionn = News_Section::where('id',$type)->first();

                        $newsl = News::where('section_id',$sectionn->id)->latest()->first();

                        $lastn['id']          = $newsl->id;
                        $lastn['name']       = $newsl->title;
                        $lastn['type']        = 'news';
                        $lastn['image']       = URL::to('uploads/news/avatar/'.$newsl->image);

                        $list['recomandtion'][]        = $lastn;

                        $newsc = News::where('section_id',$sectionn->id)->where('id', '<>',$newsl->id)->orderBy('view_count' , 'desc')->first();

                        $countn['id']          = $newsc->id;
                        $countn['name']       = $newsc->title;
                        $countn['type']        = 'news';
                        $countn['image']       = URL::to('uploads/news/avatar/'.$newsc->image);

                        $list['recomandtion'][]        = $countn;

                        # news rec

                        $newsrec = News::where('section_id',$sectionn->id)->take(10)->latest()->get();

                        foreach ($newsrec as $key => $ner)
                        {
                            $newsa['id']          = $ner->id;
                            $newsa['name']       = $ner->title;
                            $newsa['type']        = 'news';
                            $newsa['image']       = URL::to('uploads/news/avatar/'.$ner->image);


                            $list['news'][]        = $newsa;
                        }

                        #store
                        $sectionss = Store_Section::where('id',$type)->first();

                        $storel = Store_Ads::where('section_id',$sectionss->id)->latest()->first();

                        $lasts['id']          = $storel->id;
                        $lasts['name']       = $storel->title;
                        $lasts['type']        = 'store';
                        if(count($storel->StoreAdsimages) > 0){
                            $lasts['image']       = URL::to('uploads/stores/alboum/'.$storel->StoreAdsimages->first()->image);
                        }



                        $list['recomandtion'][]        = $lasts;

                        $storec = Store_Ads::where('section_id',$sectionn->id)->where('id', '<>',$storel->id)->orderBy('view_count' , 'desc')->first();

                        $counts['id']          = $storec->id;
                        $counts['name']       = $storec->title;
                        $counts['type']        = 'store';
                        if(count($storec->StoreAdsimages) > 0){
                            $counts['image']       = URL::to('uploads/stores/alboum/'.$storec->StoreAdsimages->first()->image);
                        }



                        $list['recomandtion'][]        = $counts;

                        # store rec

                        $storerec = Store_Ads::where('section_id',$sectionss->id)->take(10)->latest()->get();

                        foreach ($storerec as $key => $stc)
                        {
                            $storea['id']          = $stc->id;
                            $storea['name']       = $stc->title;
                            $storea['type']        = 'store';
                            if(count($stc->StoreAdsimages) > 0){
                                $storea['image']       = URL::to('uploads/stores/alboum/'.$stc->StoreAdsimages->first()->image);
                            }



                            $list['store'][]        = $storea;
                        }

                    }else{
                        $list['type']        = 'poultry';

                        # guide sub
                        $sublast = Guide_Sub_Section::with('Company')->latest()->first();


                        $last['id']          = $sublast->id;
                        $last['name']        = $sublast->name;
                        $last['type']        = 'guide';
                        $last['image']       = URL::to('uploads/sections/avatar/'.$sublast->image);
                        $last['companies_count']     = count($sublast->Company);


                        $list['recomandtion'][]        = $last;

                        $subcount = Guide_Sub_Section::with('Company')->where('id', '<>',$sublast->id)->orderBy('view_count' , 'desc')->first();

                        $count['id']          = $subcount->id;
                        $count['name']        = $subcount->name;
                        $count['type']        = 'guide';
                        $count['image']       = URL::to('uploads/sections/avatar/'.$subcount->image);
                        $count['companies_count']     = count($subcount->Company);


                        $list['recomandtion'][]        = $count;

                        $subran = Guide_Sub_Section::with('Company')->where('id', '<>',$subcount->id)->where('id', '<>',$sublast->id)->inRandomOrder()->first();

                        $ran['id']          = $subran->id;
                        $ran['name']        = $subran->name;
                        $ran['type']        = 'guide';
                        $ran['image']       = URL::to('uploads/sections/avatar/'.$subran->image);
                        $ran['companies_count']     = count($subran->Company);


                        $list['recomandtion'][]        = $ran;

                        # guide rec

                        $guisubs = Guide_Sub_Section::with('Company')->take(10)->latest()->get();

                        foreach ($guisubs as $key => $gui)
                        {
                            $guidea['id']          = $gui->id;
                            $guidea['name']        = $gui->name;
                            $guidea['type']        = 'guide';
                            $guidea['image']       = URL::to('uploads/sections/avatar/'.$gui->image);
                            $guidea['companies_count']     = count($gui->Company);

                            $list['guide'][]        = $guidea;
                        }



                        # local sub

                        $sublastl = Local_Stock_Sub::with('LocalStockMembers')->latest()->first();

                        $lastl['id']          = $sublastl->id;
                        $lastl['name']        = $sublastl->name;
                        $lastl['type']        = 'local';
                        $lastl['image']       = URL::to('uploads/sections/sub/'.$sublastl->image);
                        $lastl['members']     = count($sublastl->LocalStockMembers);


                        $list['recomandtion'][]        = $lastl;


                        $subcountl = Local_Stock_Sub::where('id', '<>',$sublastl->id)->with('LocalStockMembers')->orderBy('view_count' , 'desc')->first();


                        $countl['id']          = $subcountl->id;
                        $countl['name']        = $subcountl->name;
                        $countl['type']        = 'local';
                        $countl['image']       = URL::to('uploads/sections/sub/'.$subcountl->image);
                        $countl['members']     = count($subcountl->LocalStockMembers);


                        $list['recomandtion'][]        = $countl;


                        $subranl = Local_Stock_Sub::where('id', '<>',$subcountl->id)->where('id', '<>',$sublastl->id)->with('LocalStockMembers')->inRandomOrder()->first();


                        $ranl['id']          = $subranl->id;
                        $ranl['name']        = $subranl->name;
                        $ranl['type']        = 'local';
                        $ranl['image']       = URL::to('uploads/sections/sub/'.$subranl->image);
                        $ranl['members']     = count($subranl->LocalStockMembers);


                        $list['recomandtion'][]        = $ranl;

                        # stock rec

                        $stockrec = Local_Stock_Sub::with('LocalStockMembers')->take(5)->latest()->get();

                        foreach ($stockrec as $key => $sti)
                        {
                            $stocka['id']          = $sti->id;
                            $stocka['name']        = $sti->name;
                            $stocka['type']        = 'local';
                            $stocka['image']       = URL::to('uploads/sections/sub/'.$sti->image);
                            $stocka['members']     = count($sti->LocalStockMembers);

                            $list['stock'][]        = $stocka;
                        }

                        #fodder
                        $sublastf = Stock_Fodder_Sub::with('FodderStocks')->latest()->first();

                        $lastf['id']          = $sublastf->id;
                        $lastf['name']        = $sublastf->name;
                        $lastf['type']        = 'fodder';
                        $lastf['image']       = URL::to('uploads/sections/avatar/'.$sublastf->image);
                        $lastf['members']     = count($sublastf->FodderStocks);


                        $list['recomandtion'][]        = $lastf;

                        $subranf = Stock_Fodder_Sub::with('FodderStocks')->where('id', '<>',$sublastf->id)->inRandomOrder()->first();

                        $ranf['id']          = $subranf->id;
                        $ranf['name']        = $subranf->name;
                        $ranf['type']        = 'fodder';
                        $ranf['image']       = URL::to('uploads/sections/avatar/'.$subranf->image);
                        $ranf['members']     = count($subranf->FodderStocks);


                        $list['recomandtion'][]        = $ranf;

                        # stock rec

                        $stockrecf = Stock_Fodder_Sub::with('FodderStocks')->take(5)->latest()->get();

                        foreach ($stockrecf as $key => $stf)
                        {
                            $stockaf['id']          = $stf->id;
                            $stockaf['name']        = $stf->name;
                            $stockaf['type']        = 'fodder';
                            $stockaf['image']       = URL::to('uploads/sections/avatar/'.$stf->image);
                            $stockaf['members']     = count($stf->FodderStocks);

                            $list['stock'][]        = $stockaf;
                        }

                        #news


                        $newsl = News::latest()->first();

                        $lastn['id']          = $newsl->id;
                        $lastn['name']       = $newsl->title;
                        $lastn['type']        = 'news';
                        $lastn['image']       = URL::to('uploads/news/avatar/'.$newsl->image);

                        $list['recomandtion'][]        = $lastn;

                        $newsc = News::where('id', '<>',$newsl->id)->orderBy('view_count' , 'desc')->first();

                        $countn['id']          = $newsc->id;
                        $countn['name']       = $newsc->title;
                        $countn['type']        = 'news';
                        $countn['image']       = URL::to('uploads/news/avatar/'.$newsc->image);

                        $list['recomandtion'][]        = $countn;

                        # news rec

                        $newsrec = News::take(10)->latest()->get();

                        foreach ($newsrec as $key => $ner)
                        {
                            $newsa['id']          = $ner->id;
                            $newsa['name']       = $ner->title;
                            $newsa['type']        = 'news';
                            $newsa['image']       = URL::to('uploads/news/avatar/'.$ner->image);


                            $list['news'][]        = $newsa;
                        }

                        #store

                        $storel = Store_Ads::latest()->first();

                        $lasts['id']          = $storel->id;
                        $lasts['name']       = $storel->title;
                        $lasts['type']        = 'store';
                        if(count($storel->StoreAdsimages) > 0){
                            $lasts['image']       = URL::to('uploads/stores/alboum/'.$storel->StoreAdsimages->first()->image);

                        }

                        $list['recomandtion'][]        = $lasts;

                        $storec = Store_Ads::where('id', '<>',$storel->id)->orderBy('view_count' , 'desc')->first();

                        $counts['id']          = $storec->id;
                        $counts['name']       = $storec->title;
                        $counts['type']        = 'store';
                        if(count($storec->StoreAdsimages) > 0){
                            $counts['image']       = URL::to('uploads/stores/alboum/'.$storec->StoreAdsimages->first()->image);

                        }


                        $list['recomandtion'][]        = $counts;

                        # store rec

                        $storerec = Store_Ads::take(10)->latest()->get();

                        foreach ($storerec as $key => $stc)
                        {
                            $storea['id']          = $stc->id;
                            $storea['name']       = $stc->title;
                            $storea['type']        = 'store';
                            if(count($stc->StoreAdsimages) > 0){
                                $storea['image']       = URL::to('uploads/stores/alboum/'.$stc->StoreAdsimages->first()->image);

                            }



                            $list['store'][]        = $storea;
                        }


                    }

                }else{
                    $list['type']        = 'poultry';
                    # guide sub
                    $sublast = Guide_Sub_Section::with('Company')->latest()->first();




                    $last['id']          = $sublast->id;
                    $last['name']        = $sublast->name;
                    $last['type']        = 'guide';
                    $last['image']       = URL::to('uploads/sections/avatar/'.$sublast->image);
                    $last['companies_count']     = count($sublast->Company);


                    $list['recomandtion'][]        = $last;

                    $subcount = Guide_Sub_Section::with('Company')->where('id', '<>',$sublast->id)->orderBy('view_count' , 'desc')->first();

                    $count['id']          = $subcount->id;
                    $count['name']        = $subcount->name;
                    $count['type']        = 'guide';
                    $count['image']       = URL::to('uploads/sections/avatar/'.$subcount->image);
                    $count['companies_count']     = count($subcount->Company);


                    $list['recomandtion'][]        = $count;

                    $subran = Guide_Sub_Section::with('Company')->where('id', '<>',$subcount->id)->where('id', '<>',$sublast->id)->inRandomOrder()->first();

                    $ran['id']          = $subran->id;
                    $ran['name']        = $subran->name;
                    $ran['type']        = 'guide';
                    $ran['image']       = URL::to('uploads/sections/avatar/'.$subran->image);
                    $ran['companies_count']     = count($subran->Company);


                    $list['recomandtion'][]        = $ran;

                    # guide rec

                    $guisubs = Guide_Sub_Section::with('Company')->take(10)->latest()->get();

                    foreach ($guisubs as $key => $gui)
                    {
                        $guidea['id']          = $gui->id;
                        $guidea['name']        = $gui->name;
                        $guidea['type']        = 'guide';
                        $guidea['image']       = URL::to('uploads/sections/avatar/'.$gui->image);
                        $guidea['companies_count']     = count($gui->Company);

                        $list['guide'][]        = $guidea;
                    }



                    # local sub

                    $sublastl = Local_Stock_Sub::with('LocalStockMembers')->latest()->first();

                    $lastl['id']          = $sublastl->id;
                    $lastl['name']        = $sublastl->name;
                    $lastl['type']        = 'local';
                    $lastl['image']       = URL::to('uploads/sections/sub/'.$sublastl->image);
                    $lastl['members']     = count($sublastl->LocalStockMembers);


                    $list['recomandtion'][]        = $lastl;


                    $subcountl = Local_Stock_Sub::where('id', '<>',$sublastl->id)->with('LocalStockMembers')->orderBy('view_count' , 'desc')->first();


                    $countl['id']          = $subcountl->id;
                    $countl['name']        = $subcountl->name;
                    $countl['type']        = 'local';
                    $countl['image']       = URL::to('uploads/sections/sub/'.$subcountl->image);
                    $countl['members']     = count($subcountl->LocalStockMembers);


                    $list['recomandtion'][]        = $countl;


                    $subranl = Local_Stock_Sub::where('id', '<>',$subcountl->id)->where('id', '<>',$sublastl->id)->with('LocalStockMembers')->inRandomOrder()->first();


                    $ranl['id']          = $subranl->id;
                    $ranl['name']        = $subranl->name;
                    $ranl['type']        = 'local';
                    $ranl['image']       = URL::to('uploads/sections/sub/'.$subranl->image);
                    $ranl['members']     = count($subranl->LocalStockMembers);


                    $list['recomandtion'][]        = $ranl;

                    # stock rec

                    $stockrec = Local_Stock_Sub::with('LocalStockMembers')->take(5)->latest()->get();

                    foreach ($stockrec as $key => $sti)
                    {
                        $stocka['id']          = $sti->id;
                        $stocka['name']        = $sti->name;
                        $stocka['type']        = 'local';
                        $stocka['image']       = URL::to('uploads/sections/sub/'.$sti->image);
                        $stocka['members']     = count($sti->LocalStockMembers);

                        $list['stock'][]        = $stocka;
                    }

                    #fodder
                    $sublastf = Stock_Fodder_Sub::with('FodderStocks')->latest()->first();

                    $lastf['id']          = $sublastf->id;
                    $lastf['name']        = $sublastf->name;
                    $lastf['type']        = 'fodder';
                    $lastf['image']       = URL::to('uploads/sections/avatar/'.$sublastf->image);
                    $lastf['members']     = count($sublastf->FodderStocks);


                    $list['recomandtion'][]        = $lastf;

                    $subranf = Stock_Fodder_Sub::with('FodderStocks')->where('id', '<>',$sublastf->id)->inRandomOrder()->first();

                    $ranf['id']          = $subranf->id;
                    $ranf['name']        = $subranf->name;
                    $ranf['type']        = 'fodder';
                    $ranf['image']       = URL::to('uploads/sections/avatar/'.$subranf->image);
                    $ranf['members']     = count($subranf->FodderStocks);


                    $list['recomandtion'][]        = $ranf;

                    # stock rec

                    $stockrecf = Stock_Fodder_Sub::with('FodderStocks')->take(5)->latest()->get();

                    foreach ($stockrecf as $key => $stf)
                    {
                        $stockaf['id']          = $stf->id;
                        $stockaf['name']        = $stf->name;
                        $stockaf['type']        = 'fodder';
                        $stockaf['image']       = URL::to('uploads/sections/avatar/'.$stf->image);
                        $stockaf['members']     = count($stf->FodderStocks);

                        $list['stock'][]        = $stockaf;
                    }

                    #news


                    $newsl = News::latest()->first();

                    $lastn['id']          = $newsl->id;
                    $lastn['name']       = $newsl->title;
                    $lastn['type']        = 'news';
                    $lastn['image']       = URL::to('uploads/news/avatar/'.$newsl->image);

                    $list['recomandtion'][]        = $lastn;

                    $newsc = News::where('id', '<>',$newsl->id)->orderBy('view_count' , 'desc')->first();

                    $countn['id']          = $newsc->id;
                    $countn['name']       = $newsc->title;
                    $countn['type']        = 'news';
                    $countn['image']       = URL::to('uploads/news/avatar/'.$newsc->image);

                    $list['recomandtion'][]        = $countn;

                    # news rec

                    $newsrec = News::take(10)->latest()->get();

                    foreach ($newsrec as $key => $ner)
                    {
                        $newsa['id']          = $ner->id;
                        $newsa['name']       = $ner->title;
                        $newsa['type']        = 'news';
                        $newsa['image']       = URL::to('uploads/news/avatar/'.$ner->image);


                        $list['news'][]        = $newsa;
                    }

                    #store

                    $storel = Store_Ads::latest()->first();

                    $lasts['id']          = $storel->id;
                    $lasts['name']       = $storel->title;
                    $lasts['type']        = 'store';
                    if(count($storel->StoreAdsimages) > 0){
                        $lasts['image']       = URL::to('uploads/stores/alboum/'.$storel->StoreAdsimages->first()->image);

                    }


                    $list['recomandtion'][]        = $lasts;

                    $storec = Store_Ads::where('id', '<>',$storel->id)->orderBy('view_count' , 'desc')->first();

                    $counts['id']          = $storec->id;
                    $counts['name']       = $storec->title;
                    $counts['type']        = 'store';
                    if(count($storec->StoreAdsimages) > 0){
                        $counts['image']       = URL::to('uploads/stores/alboum/'.$storec->StoreAdsimages->first()->image);

                    }


                    $list['recomandtion'][]        = $counts;

                    # store rec

                    $storerec = Store_Ads::take(10)->latest()->get();

                    foreach ($storerec as $key => $stc)
                    {
                        $storea['id']          = $stc->id;
                        $storea['name']       = $stc->title;
                        $storea['type']        = 'store';
                        if(count($stc->StoreAdsimages) > 0){
                            $storea['image']       = URL::to('uploads/stores/alboum/'.$stc->StoreAdsimages->first()->image);

                        }



                        $list['store'][]        = $storea;
                    }

                }
            }
            else{
                $list['type']        = 'poultry';
                # guide sub
                $sublast = Guide_Sub_Section::with('Company')->latest()->first();




                $last['id']          = $sublast->id;
                $last['name']        = $sublast->name;
                $last['type']        = 'guide';
                $last['image']       = URL::to('uploads/sections/avatar/'.$sublast->image);
                $last['companies_count']     = count($sublast->Company);


                $list['recomandtion'][]        = $last;

                $subcount = Guide_Sub_Section::with('Company')->where('id', '<>',$sublast->id)->orderBy('view_count' , 'desc')->first();

                $count['id']          = $subcount->id;
                $count['name']        = $subcount->name;
                $count['type']        = 'guide';
                $count['image']       = URL::to('uploads/sections/avatar/'.$subcount->image);
                $count['companies_count']     = count($subcount->Company);


                $list['recomandtion'][]        = $count;

                $subran = Guide_Sub_Section::with('Company')->where('id', '<>',$subcount->id)->where('id', '<>',$sublast->id)->inRandomOrder()->first();

                $ran['id']          = $subran->id;
                $ran['name']        = $subran->name;
                $ran['type']        = 'guide';
                $ran['image']       = URL::to('uploads/sections/avatar/'.$subran->image);
                $ran['companies_count']     = count($subran->Company);


                $list['recomandtion'][]        = $ran;

                # guide rec

                $guisubs = Guide_Sub_Section::with('Company','Section')->take(10)->latest()->get();

                foreach ($guisubs as $key => $gui)
                {
                    $guidea['id']          = $gui->id;
                    $guidea['name']        = $gui->name .' - '. $gui->Section->name;
                    $guidea['type']        = 'guide';
                    $guidea['image']       = URL::to('uploads/sections/avatar/'.$gui->image);
                    $guidea['companies_count']     = count($gui->Company);

                    $list['guide'][]        = $guidea;
                }



                # local sub

                $sublastl = Local_Stock_Sub::with('LocalStockMembers')->latest()->first();

                $lastl['id']          = $sublastl->id;
                $lastl['name']        = $sublastl->name;
                $lastl['type']        = 'local';
                $lastl['image']       = URL::to('uploads/sections/sub/'.$sublastl->image);
                $lastl['members']     = count($sublastl->LocalStockMembers);


                $list['recomandtion'][]        = $lastl;


                $subcountl = Local_Stock_Sub::where('id', '<>',$sublastl->id)->with('LocalStockMembers')->orderBy('view_count' , 'desc')->first();


                $countl['id']          = $subcountl->id;
                $countl['name']        = $subcountl->name;
                $countl['type']        = 'local';
                $countl['image']       = URL::to('uploads/sections/sub/'.$subcountl->image);
                $countl['members']     = count($subcountl->LocalStockMembers);


                $list['recomandtion'][]        = $countl;


                $subranl = Local_Stock_Sub::where('id', '<>',$subcountl->id)->where('id', '<>',$sublastl->id)->with('LocalStockMembers')->inRandomOrder()->first();


                $ranl['id']          = $subranl->id;
                $ranl['name']        = $subranl->name;
                $ranl['type']        = 'local';
                $ranl['image']       = URL::to('uploads/sections/sub/'.$subranl->image);
                $ranl['members']     = count($subranl->LocalStockMembers);


                $list['recomandtion'][]        = $ranl;

                # stock rec

                $stockrec = Local_Stock_Sub::with('LocalStockMembers','Section')->take(5)->latest()->get();

                foreach ($stockrec as $key => $sti)
                {
                    $name = $sti->section_id != null ? ' - ' .$sti->Section->name : '';

                    $stocka['id']          = $sti->id;
                    $stocka['name']        = $sti->name .$name;
                    $stocka['type']        = 'local';
                    $stocka['image']       = URL::to('uploads/sections/sub/'.$sti->image);
                    $stocka['members']     = count($sti->LocalStockMembers);

                    $list['stock'][]        = $stocka;
                }

                #fodder
                $sublastf = Stock_Fodder_Sub::with('FodderStocks')->latest()->first();

                $lastf['id']          = $sublastf->id;
                $lastf['name']        = $sublastf->name;
                $lastf['type']        = 'fodder';
                $lastf['image']       = URL::to('uploads/sections/avatar/'.$sublastf->image);
                $lastf['members']     = count($sublastf->FodderStocks);


                $list['recomandtion'][]        = $lastf;

                $subranf = Stock_Fodder_Sub::with('FodderStocks')->where('id', '<>',$sublastf->id)->inRandomOrder()->first();

                $ranf['id']          = $subranf->id;
                $ranf['name']        = $subranf->name;
                $ranf['type']        = 'fodder';
                $ranf['image']       = URL::to('uploads/sections/avatar/'.$subranf->image);
                $ranf['members']     = count($subranf->FodderStocks);


                $list['recomandtion'][]        = $ranf;

                # stock rec

                $stockrecf = Stock_Fodder_Sub::with('FodderStocks','Section')->take(5)->latest()->get();

                foreach ($stockrecf as $key => $stf)
                {
                    $name = $stf->section_id != null ? ' - '.$stf->Section->name : '';

                    $stockaf['id']          = $stf->id;
                    $stockaf['name']        = $stf->name . $name;
                    $stockaf['type']        = 'fodder';
                    $stockaf['image']       = URL::to('uploads/sections/avatar/'.$stf->image);
                    $stockaf['members']     = count($stf->FodderStocks);

                    $list['stock'][]        = $stockaf;
                }

                #news


                $newsl = News::latest()->first();

                $lastn['id']          = $newsl->id;
                $lastn['name']       = $newsl->title;
                $lastn['type']        = 'news';
                $lastn['image']       = URL::to('uploads/news/avatar/'.$newsl->image);

                $list['recomandtion'][]        = $lastn;

                $newsc = News::where('id', '<>',$newsl->id)->orderBy('view_count' , 'desc')->first();

                $countn['id']          = $newsc->id;
                $countn['name']       = $newsc->title;
                $countn['type']        = 'news';
                $countn['image']       = URL::to('uploads/news/avatar/'.$newsc->image);

                $list['recomandtion'][]        = $countn;

                # news rec

                $newsrec = News::take(10)->latest()->get();

                foreach ($newsrec as $key => $ner)
                {
                    $newsa['id']          = $ner->id;
                    $newsa['name']       = $ner->title;
                    $newsa['type']        = 'news';
                    $newsa['image']       = URL::to('uploads/news/avatar/'.$ner->image);


                    $list['news'][]        = $newsa;
                }

                #store

                $storel = Store_Ads::latest()->first();

                $lasts['id']          = $storel->id;
                $lasts['name']       = $storel->title;
                $lasts['type']        = 'store';
                if(count($storel->StoreAdsimages) > 0){
                    $lasts['image']       = URL::to('uploads/stores/alboum/'.$storel->StoreAdsimages->first()->image);
                }


                $list['recomandtion'][]        = $lasts;

                $storec = Store_Ads::where('id', '<>',$storel->id)->orderBy('view_count' , 'desc')->first();

                $counts['id']          = $storec->id;
                $counts['name']       = $storec->title;
                $counts['type']        = 'store';
                if(count($storec->StoreAdsimages) > 0){
                    $counts['image']       = URL::to('uploads/stores/alboum/'.$storec->StoreAdsimages->first()->image);
                }


                $list['recomandtion'][]        = $counts;

                # store rec

                $storerec = Store_Ads::take(10)->latest()->get();

                foreach ($storerec as $key => $stc)
                {
                    $storea['id']          = $stc->id;
                    $storea['name']       = $stc->title;
                    $storea['type']        = 'store';
                    if(count($stc->StoreAdsimages) > 0){
                        $storea['image']       = URL::to('uploads/stores/alboum/'.$stc->StoreAdsimages->first()->image);
                    }



                    $list['store'][]        = $storea;
                }

            }
        }
        else{
            $list['type']        = 'poultry';
            # guide sub
            $sublast = Guide_Sub_Section::with('Company')->latest()->first();




            $last['id']          = $sublast->id;
            $last['name']        = $sublast->name;
            $last['type']        = 'guide';
            $last['image']       = URL::to('uploads/sections/avatar/'.$sublast->image);
            $last['companies_count']     = count($sublast->Company);


            $list['recomandtion'][]        = $last;

            $subcount = Guide_Sub_Section::with('Company')->where('id', '<>',$sublast->id)->orderBy('view_count' , 'desc')->first();

            $count['id']          = $subcount->id;
            $count['name']        = $subcount->name;
            $count['type']        = 'guide';
            $count['image']       = URL::to('uploads/sections/avatar/'.$subcount->image);
            $count['companies_count']     = count($subcount->Company);


            $list['recomandtion'][]        = $count;

            $subran = Guide_Sub_Section::with('Company')->where('id', '<>',$subcount->id)->where('id', '<>',$sublast->id)->inRandomOrder()->first();

            $ran['id']          = $subran->id;
            $ran['name']        = $subran->name;
            $ran['type']        = 'guide';
            $ran['image']       = URL::to('uploads/sections/avatar/'.$subran->image);
            $ran['companies_count']     = count($subran->Company);


            $list['recomandtion'][]        = $ran;

            # guide rec

            $guisubs = Guide_Sub_Section::with('Company','Section')->take(10)->latest()->get();

            foreach ($guisubs as $key => $gui)
            {
                $name = $gui->section_id != null ? ' - ' .$gui->Section->name : '';

                $guidea['id']          = $gui->id;
                $guidea['name']        = $gui->name .$name;
                $guidea['type']        = 'guide';
                $guidea['image']       = URL::to('uploads/sections/avatar/'.$gui->image);
                $guidea['companies_count']     = count($gui->Company);

                $list['guide'][]        = $guidea;
            }



            # local sub

            $sublastl = Local_Stock_Sub::with('LocalStockMembers')->latest()->first();

            $lastl['id']          = $sublastl->id;
            $lastl['name']        = $sublastl->name;
            $lastl['type']        = 'local';
            $lastl['image']       = URL::to('uploads/sections/sub/'.$sublastl->image);
            $lastl['members']     = count($sublastl->LocalStockMembers);


            $list['recomandtion'][]        = $lastl;


            $subcountl = Local_Stock_Sub::where('id', '<>',$sublastl->id)->with('LocalStockMembers')->orderBy('view_count' , 'desc')->first();


            $countl['id']          = $subcountl->id;
            $countl['name']        = $subcountl->name;
            $countl['type']        = 'local';
            $countl['image']       = URL::to('uploads/sections/sub/'.$subcountl->image);
            $countl['members']     = count($subcountl->LocalStockMembers);


            $list['recomandtion'][]        = $countl;


            $subranl = Local_Stock_Sub::where('id', '<>',$subcountl->id)->where('id', '<>',$sublastl->id)->with('LocalStockMembers')->inRandomOrder()->first();


            $ranl['id']          = $subranl->id;
            $ranl['name']        = $subranl->name;
            $ranl['type']        = 'local';
            $ranl['image']       = URL::to('uploads/sections/sub/'.$subranl->image);
            $ranl['members']     = count($subranl->LocalStockMembers);


            $list['recomandtion'][]        = $ranl;

            # stock rec

            $stockrec = Local_Stock_Sub::with('LocalStockMembers','Section')->take(5)->latest()->get();

            foreach ($stockrec as $key => $sti)
            {
                $name = $sti->section_id != null ? ' - ' .$sti->Section->name : '';

                $stocka['id']          = $sti->id;
                $stocka['name']        = $sti->name . $name;
                $stocka['type']        = 'local';
                $stocka['image']       = URL::to('uploads/sections/sub/'.$sti->image);
                $stocka['members']     = count($sti->LocalStockMembers);

                $list['stock'][]        = $stocka;
            }

            #fodder
            $sublastf = Stock_Fodder_Sub::with('FodderStocks')->latest()->first();

            $lastf['id']          = $sublastf->id;
            $lastf['name']        = $sublastf->name;
            $lastf['type']        = 'fodder';
            $lastf['image']       = URL::to('uploads/sections/avatar/'.$sublastf->image);
            $lastf['members']     = count($sublastf->FodderStocks);


            $list['recomandtion'][]        = $lastf;

            $subranf = Stock_Fodder_Sub::with('FodderStocks')->where('id', '<>',$sublastf->id)->inRandomOrder()->first();

            $ranf['id']          = $subranf->id;
            $ranf['name']        = $subranf->name;
            $ranf['type']        = 'fodder';
            $ranf['image']       = URL::to('uploads/sections/avatar/'.$subranf->image);
            $ranf['members']     = count($subranf->FodderStocks);


            $list['recomandtion'][]        = $ranf;

            # stock rec

            $stockrecf = Stock_Fodder_Sub::with('FodderStocks','Section')->take(5)->latest()->get();

            foreach ($stockrecf as $key => $stf)
            {
                $name = $sti->section_id != null ? ' - ' .$sti->Section->name : '';

                $stockaf['id']          = $stf->id;
                $stockaf['name']        = $stf->name . $name;
                $stockaf['type']        = 'fodder';
                $stockaf['image']       = URL::to('uploads/sections/avatar/'.$stf->image);
                $stockaf['members']     = count($stf->FodderStocks);

                $list['stock'][]        = $stockaf;
            }

            #news


            $newsl = News::latest()->first();

            $lastn['id']          = $newsl->id;
            $lastn['name']       = $newsl->title;
            $lastn['type']        = 'news';
            $lastn['image']       = URL::to('uploads/news/avatar/'.$newsl->image);

            $list['recomandtion'][]        = $lastn;

            $newsc = News::where('id', '<>',$newsl->id)->orderBy('view_count' , 'desc')->first();

            $countn['id']          = $newsc->id;
            $countn['name']       = $newsc->title;
            $countn['type']        = 'news';
            $countn['image']       = URL::to('uploads/news/avatar/'.$newsc->image);

            $list['recomandtion'][]        = $countn;

            # news rec

            $newsrec = News::take(10)->latest()->get();

            foreach ($newsrec as $key => $ner)
            {
                $newsa['id']          = $ner->id;
                $newsa['name']       = $ner->title;
                $newsa['type']        = 'news';
                $newsa['image']       = URL::to('uploads/news/avatar/'.$ner->image);


                $list['news'][]        = $newsa;
            }

            #store

            $storel = Store_Ads::latest()->first();

            $lasts['id']          = $storel->id;
            $lasts['name']       = $storel->title;
            $lasts['type']        = 'store';
            if(count($storel->StoreAdsimages) > 0){
                $lasts['image']       = URL::to('uploads/stores/alboum/'.$storel->StoreAdsimages->first()->image);

            }


            $list['recomandtion'][]        = $lasts;

            $storec = Store_Ads::where('id', '<>',$storel->id)->orderBy('view_count' , 'desc')->first();

            $counts['id']          = $storec->id;
            $counts['name']       = $storec->title;
            $counts['type']        = 'store';
            if(count($storec->StoreAdsimages) > 0){
                $counts['image']       = URL::to('uploads/stores/alboum/'.$storec->StoreAdsimages->first()->image);

            }


            $list['recomandtion'][]        = $counts;

            # store rec

            $storerec = Store_Ads::take(10)->latest()->get();

            foreach ($storerec as $key => $stc)
            {
                $storea['id']          = $stc->id;
                $storea['name']       = $stc->title;
                $storea['type']        = 'store';
                if(count($stc->StoreAdsimages) > 0){
                    $storea['image']       = URL::to('uploads/stores/alboum/'.$stc->StoreAdsimages->first()->image);

                }




                $list['store'][]        = $storea;
            }

        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    # show SubSections
    public function showSubSections(Request $request)
    {

        $type       = $request->input("type");
        $sort       = $request->input("sort");
        $search     = $request->input("search");
        $section_id = $request->input("section_id");
            # check section exist
        if(!$section_id)
        {
            $section = Guide_Section::where('selected','1')->first();
        }else{
            $section = Guide_Section::where('id',$section_id)->first();
        }
        # check section exist
        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $sections = Guide_Section::get();

        # check recomndation system
            if(!is_null($request->header('Authorization')))
            {
                $token = $request->header('Authorization'); 
                $token = explode(' ',$token);
                if(count( $token) == 2)
                {
    
                    $customer = Customer::where('api_token',$token[1])->first();
                    if($customer)
                    {

                        $main_keyword = Data_Analysis_Keywords::where('keyword','guide')->where('type',$section->id)->first();
                        $main_keyword->use_count = $main_keyword->use_count + 1;
                        $main_keyword->update();

                        $user_data = User_Data_Analysis::where([['keyword_id',$main_keyword->id],['user_id',$customer->id]])->first();
   

                        if($user_data)
                        {
                            # increment user analysis count
                            $user_data->use_count = $user_data->use_count + 1; 
                            $user_data->update();
                        }else{
                            # create new record for user with keyword
                            $user_data = new User_Data_Analysis;
                            $user_data->user_id    = $customer->id;
                            $user_data->keyword_id = $main_keyword->id;
                            $user_data->use_count  = 1;
                            $user_data->save();
                        }

                        
                        # crate new record for data analysis
                        $data_analysis = new Data_Analysis;
                        $data_analysis->user_id    = $customer->id;
                        $data_analysis->keyword_id = $main_keyword->id;
                        $data_analysis->save();



                    }
                }
            }




            $subs = Guide_Sub_Section::with('Company')->where('section_id',$section->id);

            if(!is_null($search))
            {
                $keyword = $this->searchQuery($search);
                $subs = $subs->where('name' , 'REGEXP' , $keyword); //->where('name' , 'like' , "%". $search ."%");
            }

            if(!is_null($sort))
            {
                if($sort == 0)
                {
                    $subs = $subs->orderby('name')->get();
                }else{
                    $subs = $subs->orderBy('view_count' , 'desc')->get();
                }
            }else{
                $subs = $subs->get();
            }
            
        

       
            $list = [];

            foreach ($sections as $key => $sec)
            {
                $list['sectors'][$key]['id']          = $sec->id;
                $list['sectors'][$key]['name']        = $sec->name;
                $list['sectors'][$key]['type']        = $sec->type;
                if($sec->id == $section->id){
                    $list['sectors'][$key]['selected']        = 1;
                }else{
                    $list['sectors'][$key]['selected']        = 0;
                }
            }

            $page = System_Ads_Pages::with('SystemAds')->where('type','guide')->pluck('ads_id')->toArray();

            $ads = System_Ads::where('main','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
            $logos = System_Ads::where('main','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        //refactor response of banners to use resource instead of for loop
        $list['banners'] = count($ads) == 0 ? [] : LogoBannerResource::collection($ads);
        $list['logos'] = count($logos) == 0 ? [] : LogoBannerResource::collection($logos);
           
            $loin = [];
            if(count($subs) == 0){
                $list['sub_sections'] = [];
            }else{

                foreach ($subs as $key => $sub)
                {
                    if($section->id == 6 && $sub->id == 180){
                        continue;
                        }

                    $list['sub_sections'][$key]['id']          = $sub->id;
                    $list['sub_sections'][$key]['name']        = $sub->name;
                    $list['sub_sections'][$key]['image']       = URL::to('uploads/sections/avatar/'.$sub->image);
                    $list['sub_sections'][$key]['companies_count']     = count($sub->Company);
                    if(!is_null($request->header('device'))){
                        $list['sub_sections'][$key]['type']     = $sub->type;
                    }
                    if(count($sub->logooos()) == 0){
                        $list['sub_sections'][$key]['logo_in'] = [];
                    }else{
                        foreach ($section->logooos() as $looo)
                        {
                            $loin['id']         = $looo->id;
                            $loin['link']       = $looo->link;
                            $loin['image']      = URL::to('uploads/full_images/'.$looo->image);
                            $list['sub_sections'][$key]['logo_in'][] = $loin;
                        
                        }
                    }
            
                       

              

                }
                if($section->id == 6){
                    $list['sub_sections']=array_values($list['sub_sections']);
                }

            }
        

            return response()->json([
                'message'  => null,
                'error'    => null,
                'data'     => $list
            ],200);

    }

    # show filter sections
    public function filterSections(Request $request)
    {

        $type = $request->input("type");
        $section_id = $request->input("section_id");

        # check section exist
        if(!$section_id)
        {
            $section = Guide_Section::where('selected','1')->first();
        }else{
            $section = Guide_Section::where('id',$section_id)->first();
        }

        $sections = Guide_Section::get();
         
        # check section exist
        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }
 
        $list = [];
    
 
        foreach ($sections as $key => $sec)
        {
            $list['sectors'][$key]['id']          = $sec->id;
            $list['sectors'][$key]['name']        = $sec->name;
            $list['sectors'][$key]['type']        = $sec->type;
            if($sec->id == $section->id){
                $list['sectors'][$key]['selected']        = 1;
            }else{
                $list['sectors'][$key]['selected']        = 0;
            }
    


        }

        $arr = [];
        $arrr = [];

        $arr['id']        = 1;
        $arr['name']       = 'الابجدي ';
        $arr['value ']        = 0;

        $list['sort'][]       = $arr;

        
        $arrr['id']        = 2;
        $arrr['name']       = 'الاكثر تداولا';
        $arrr['value']        = 1;

        $list['sort'][]       = $arrr;

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
 
    }

    public function searchIos(Request $request)
    {

        $search = $request->input("search");

        $keyword = $this->searchQuery($search);
        $list = [];

        $guidesubs = Guide_Sub_Section::with('Section','Company')->where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $companies = Company::where('name' , 'REGEXP' , $keyword)->with('sections','SubSections')->take(50)->latest()->get();
        $localsubs = Local_Stock_Sub::where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $foddersubs = Stock_Fodder_Sub::where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
//        $magazines = Magazine::with('sections')->where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $news = News::where('title' , 'REGEXP' , $keyword)->take(50)->latest()->get();
        $showes = Show::with('Section','City')->where('name' , 'REGEXP' , $keyword)->take(50)->latest()->get();
//        $stores = Store_Ads::with('StoreAdsimages')->where('title' , 'REGEXP' , $keyword)->take(50)->latest()->get();



        foreach ($guidesubs as $key => $not)
        {
            $list['result'][$key]['id']         = $not->id;
            $list['result'][$key]['name']       = $not->name;
            $list['result'][$key]['type']       = 'guide_sub_sections';
        }

        foreach ($companies as $key => $not)
        {
            $list['result'][$key]['id']         = $not->id;
            $list['result'][$key]['name']       = $not->name;
            $list['result'][$key]['type']       = 'companies';
        }

        foreach ($localsubs as $key => $not)
        {
            $list['result'][$key]['id']         = $not->id;
            $list['result'][$key]['name']       = $not->name;
            $list['result'][$key]['type']       = 'local_stock_sub';
        }

        foreach ($foddersubs as $key => $not)
        {
            $list['result'][$key]['id']         = $not->id;
            $list['result'][$key]['name']       = $not->name;
            $list['result'][$key]['type']       = 'fodder_stock_sub';

        }

//        foreach ($magazines as $key => $not)
//        {
//            $list['result'][$key]['id']         = $not->id;
//            $list['result'][$key]['name']       = $not->name;
//            $list['result'][$key]['type']       = 'magazines';
//
//
//
//        }

        foreach ($news as $key => $not)
        {
            $list['result'][$key]['id']         = $not->id;
            $list['result'][$key]['name']       = $not->title;
            $list['result'][$key]['type']       = 'news';

        }

//        foreach ($stores as $key => $not)
//        {
//            $list['result'][$key]['id']         = $not->id;
//            $list['result'][$key]['name']       = $not->title;
//            $list['result'][$key]['type']       = 'stores';
//
//
//
//        }


        foreach ($showes as $key => $not)
        {
            $list['result'][$key]['id']         = $not->id;
            $list['result'][$key]['name']       = $not->name;
            $list['result'][$key]['type']       = 'showes';
        }

        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show home service
    public function service(Request $request , GuideService $guideService)
    {
        $list = [];
        $last = [];
        $count = [];
        $ran = [];
        $lastl = [];
        $countl = [];
        $ranl = [];
        $magar = [];
        $showr = [];

        $page = System_Ads_Pages::with('SystemAds')->where('type','home')->pluck('ads_id')->toArray();

        $logos = System_Ads::select('id','link','image','company_id')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $logos=LogoBannerResource::collection($logos);
        $list['logos'] = count($logos) == 0 ? [] : $logos;



        $banners = Banners::select('id','image')->inRandomOrder()->get();
        $list['banners'] = count($banners) == 0 ? [] : LogoBannerResource::collection($banners);

        $types = ['cta', 'claim', 'app', 'questions', 'howtouse'];
        $images = MainImages::whereIn('type', $types)->get();
        foreach ($images as $key => $img) {
            $list[$img->type][]=[
                'id' => $img->id,
                'desc' => $img->description,
                'link' => $img->link,
                'image' => URL::to('uploads/main/home/'.$img->image)
            ];
        }

        $list['services']=[];
        $images = MainImages::where('services','!=','null')->get();
        foreach ($images as $key => $img) {
            $list['services'][]=[
                'id' => $img->Service->id,
                'name' => $img->Service->name,
                'type' => $img->Service->type,
                'link' => $img->link,
                'image' => URL::to('uploads/main/home/'.$img->image)
            ];
        }

        $images = MainImages::where('most_visited','!=','null')->get();
        foreach ($images as $key => $img) {
            $list['most_visited'][]=[
                'id' => $img->id,
                'service_id' => $img->Visited->id,
                'service_name' => $img->Visited->name,
                'desc' => $img->description,
                'link' => $img->link,
                'image' => URL::to('uploads/main/home/'.$img->image)
            ];
        }

        $images = MainImages::where('newest','!=','null')->get();
        foreach ($images as $key => $img) {
            $list['newest'][]=[
                'id' => $img->id,
                'service_id' => $img->Newest->id,
                'service_name' => $img->Newest->name,
                'desc' => $img->description,
                'link' => $img->link,
                'image' => URL::to('uploads/main/home/'.$img->image)
            ];
        }

        $list['recomandtion']=[];
        # check recomndation system
        if(!is_null($request->header('Authorization')))
        {
            $token = $request->header('Authorization');
            $token = explode(' ',$token);

            if(count( $token) == 2)
            {
                $customer = Customer::where('api_token',$token[1])->first();
                if($customer)
                {

                    $user_data = User_Data_Analysis::where([['user_id',$customer->id]])->orderBy('use_count' , 'desc')->first();


                    if($user_data){

                        $main_keyword = Data_Analysis_Keywords::where([['id',$user_data->keyword_id]])->first();

                        $type = $main_keyword->type;


                        $list['type']        = $type;


                        # guide sub
                        $section = Guide_Section::where('id',$type)->first();

                        $sublast = Guide_Sub_Section::with('Company')->where('section_id',$section->id)->latest()->first();


                        $last['id']          = $sublast->id;
                        $last['name']        = $sublast->name;
                        $last['type']        = 'guide';
                        $last['image']       = URL::to('uploads/sections/avatar/'.$sublast->image);
//                        $last['companies_count']     = count($sublast->Company);


                        $list['recomandtion'][]        = $last;

                        $subcount = Guide_Sub_Section::with('Company')->where('section_id',$section->id)->where('id', '<>',$sublast->id)->orderBy('view_count' , 'desc')->first();

                        $count['id']          = $subcount->id;
                        $count['name']        = $subcount->name;
                        $count['type']        = 'guide';
                        $count['image']       = URL::to('uploads/sections/avatar/'.$subcount->image);
//                        $count['companies_count']     = count($subcount->Company);


                        $list['recomandtion'][]        = $count;

                        $subran = Guide_Sub_Section::with('Company')->where('section_id',$section->id)->where('id', '<>',$subcount->id)->where('id', '<>',$sublast->id)->inRandomOrder()->first();

                        $ran['id']          = $subran->id;
                        $ran['name']        = $subran->name;
                        $ran['type']        = 'guide';
                        $ran['image']       = URL::to('uploads/sections/avatar/'.$subran->image);
//                        $ran['companies_count']     = count($subran->Company);


                        $list['recomandtion'][]        = $ran;


                        # guide rec

                        $guisubs = Guide_Sub_Section::with('Company')->where('section_id',$section->id)->take(10)->latest()->get();

                        foreach ($guisubs as $key => $gui)
                        {
                            $guidea['id']          = $gui->id;
                            $guidea['name']        = $gui->name;
                            $guidea['type']        = 'guide';
                            $guidea['image']       = URL::to('uploads/sections/avatar/'.$gui->image);
                            $guidea['companies_count']     = count($gui->Company);

                            $list['guide'][]        = $guidea;
                        }


                        # local sub
                        $sectionl = Local_Stock_Sections::where('id',$type)->first();

                        $majs = Sec_All::where('section_id' , $sectionl->id)->pluck('sub_id')->toArray();
                        if(count($majs) == 0)
                        {
                            $sublastl = Local_Stock_Sub::where('section_id',$sectionl->id)->with('LocalStockMembers')->latest()->first();
                        }else{
                            $sublastl = Local_Stock_Sub::where('section_id',$sectionl->id)->orWhereIn('id',$majs)->with('LocalStockMembers')->latest()->first();
                        }

                        $lastl['id']          = $sublastl->id;
                        $lastl['name']        = $sublastl->name;
//                        $lastl['type']        = 'localstock';
                        $lastl['type']        = 'local';
                        $lastl['image']       = URL::to('uploads/sections/sub/'.$sublastl->image);
//                        $lastl['members']     = count($sublastl->LocalStockMembers);


                        $list['recomandtion'][]        = $lastl;

                        if(count($majs) == 0)
                        {
                            $subcountl = Local_Stock_Sub::where('section_id',$sectionl->id)->where('id', '<>',$sublastl->id)->with('LocalStockMembers')->orderBy('view_count' , 'desc')->first();
                        }else{
                            $subcountl = Local_Stock_Sub::where('section_id',$sectionl->id)->orWhereIn('id',$majs)->where('id', '<>',$sublastl->id)->with('LocalStockMembers')->orderBy('view_count' , 'desc')->first();
                        }

                        $countl['id']          = $subcountl->id;
                        $countl['name']        = $subcountl->name;
                        $countl['type']        = 'local';
                        $countl['image']       = URL::to('uploads/sections/sub/'.$subcountl->image);
//                        $countl['members']     = count($subcountl->LocalStockMembers);


                        $list['recomandtion'][]        = $countl;

                        if(count($majs) == 0)
                        {
                            $subranl = Local_Stock_Sub::where('section_id',$sectionl->id)->where('id', '<>',$subcountl->id)->where('id', '<>',$sublastl->id)->with('LocalStockMembers')->inRandomOrder()->first();
                        }else{
                            $subranl = Local_Stock_Sub::where('section_id',$sectionl->id)->where('id', '<>',$subcountl->id)->where('id', '<>',$sublastl->id)->orWhereIn('id',$majs)->with('LocalStockMembers')->inRandomOrder()->first();
                        }

                        $ranl['id']          = $subranl->id;
                        $ranl['name']        = $subranl->name;
                        $ranl['type']        = 'local';
                        $ranl['image']       = URL::to('uploads/sections/sub/'.$subranl->image);
//                        $ranl['members']     = count($subranl->LocalStockMembers);


                        $list['recomandtion'][]        = $ranl;


                        # stock rec

                        $stockrec = Local_Stock_Sub::with('LocalStockMembers')->where('section_id',$sectionl->id)->take(5)->latest()->get();

                        foreach ($stockrec as $key => $sti)
                        {
                            $stocka['id']          = $sti->id;
                            $stocka['name']        = $sti->name;
                            $stocka['type']        = 'local';
                            $stocka['image']       = URL::to('uploads/sections/sub/'.$sti->image);
                            $stocka['members']     = count($sti->LocalStockMembers);

                            $list['stock'][]        = $stocka;
                        }


                        #fodder
                        $sectionsf = Stock_Fodder_Section::with('FodderStocks')->where('id',$type)->first();
                        $sublastf = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->latest()->first();

                        $lastf['id']          = $sublastf->id;
                        $lastf['name']        = $sublastf->name;
                        $lastf['type']        = 'fodder';
                        $lastf['image']       = URL::to('uploads/sections/avatar/'.$sublastf->image);
//                        $lastf['members']     = count($sublastf->FodderStocks);


                        $list['recomandtion'][]        = $lastf;

                        $subranf = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->where('id', '<>',$sublastf->id)->inRandomOrder()->first();

                        $ranf['id']          = $subranf->id;
                        $ranf['name']        = $subranf->name;
                        $ranf['type']        = 'fodder';
                        $ranf['image']       = URL::to('uploads/sections/avatar/'.$subranf->image);
//                        $ranf['members']     = count($subranf->FodderStocks);


                        $list['recomandtion'][]        = $ranf;

                        # stock rec

                        $stockrecf = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->take(5)->latest()->get();

                        foreach ($stockrecf as $key => $stf)
                        {
                            $stockaf['id']          = $stf->id;
                            $stockaf['name']        = $stf->name;
                            $stockaf['type']        = 'fodder';
                            $stockaf['image']       = URL::to('uploads/sections/avatar/'.$stf->image);
                            $stockaf['members']     = count($stf->FodderStocks);

                            $list['stock'][]        = $stockaf;
                        }


                        #news
                        $sectionn = News_Section::where('id',$type)->first();

                        $newsl = News::where('section_id',$sectionn->id)->latest()->first();

                        $lastn['id']          = $newsl->id;
                        $lastn['name']       = $newsl->title;
                        $lastn['type']        = 'news';
                        $lastn['image']       = URL::to('uploads/news/avatar/'.$newsl->image);

                        $list['recomandtion'][]        = $lastn;

                        $newsc = News::where('section_id',$sectionn->id)->where('id', '<>',$newsl->id)->orderBy('view_count' , 'desc')->first();

                        $countn['id']          = $newsc->id;
                        $countn['name']       = $newsc->title;
                        $countn['type']        = 'news';
                        $countn['image']       = URL::to('uploads/news/avatar/'.$newsc->image);

                        $list['recomandtion'][]        = $countn;

                        # news rec

                        $newsrec = News::where('section_id',$sectionn->id)->take(10)->latest()->get();

                        foreach ($newsrec as $key => $ner)
                        {
                            $newsa['id']          = $ner->id;
                            $newsa['name']       = $ner->title;
                            $newsa['type']        = 'news';
                            $newsa['image']       = URL::to('uploads/news/avatar/'.$ner->image);


                            $list['news'][]        = $newsa;
                        }

                        #store
                        $sectionss = Store_Section::where('id',$type)->first();

                        $storel = Store_Ads::where('section_id',$sectionss->id)->latest()->first();

                        $lasts['id']          = $storel->id;
                        $lasts['name']       = $storel->title;
                        $lasts['type']        = 'store';
                        if(count($storel->StoreAdsimages) > 0){
                            $lasts['image']       = URL::to('uploads/stores/alboum/'.$storel->StoreAdsimages->first()->image);
                        }



                        $list['recomandtion'][]        = $lasts;

                        $storec = Store_Ads::where('section_id',$sectionn->id)->where('id', '<>',$storel->id)->orderBy('view_count' , 'desc')->first();

                        $counts['id']          = $storec->id;
                        $counts['name']       = $storec->title;
                        $counts['type']        = 'store';
                        if(count($storec->StoreAdsimages) > 0){
                            $counts['image']       = URL::to('uploads/stores/alboum/'.$storec->StoreAdsimages->first()->image);
                        }



                        $list['recomandtion'][]        = $counts;

                        # store rec

                        $storerec = Store_Ads::where('section_id',$sectionss->id)->take(10)->latest()->get();

                        foreach ($storerec as $key => $stc)
                        {
                            $storea['id']          = $stc->id;
                            $storea['name']       = $stc->title;
                            $storea['type']        = 'store';
                            if(count($stc->StoreAdsimages) > 0){
                                $storea['image']       = URL::to('uploads/stores/alboum/'.$stc->StoreAdsimages->first()->image);
                            }



                            $list['store'][]        = $storea;
                        }

                        # show sub
                        $section = Show_Section::with('Shows')->where('id',$type)->first();


                        $majs = Shows_Sec::where('section_id' , $section->id)->pluck('show_id')->toArray();
                        $showsl = Show::whereIn('id',$majs)->latest()->first();


                        $last['id']          = $showsl->id;
                        $last['name']        = $showsl->name;
                        $last['type']        = 'show';
                        $last['image']       = URL::to('uploads/show/images/'.$showsl->image);


                        $list['recomandtion'][]        = $last;

                        $majs = Shows_Sec::where('section_id' , $section->id)->pluck('show_id')->toArray();
                        $showsc = Show::whereIn('id',$majs)->where('id', '<>',$showsl->id)->orderBy('view_count' , 'desc')->first();

                        $count['id']          = $showsc->id;
                        $count['name']        = $showsc->name;
                        $count['type']        = 'show';
                        $count['image']       = URL::to('uploads/show/images/'.$showsc->image);


                        $list['recomandtion'][]        = $count;

                        $majs = Shows_Sec::where('section_id' , $section->id)->pluck('show_id')->toArray();
                        $showsr = Show::whereIn('id',$majs)->where('id', '<>',$showsc->id)->where('id', '<>',$showsl->id)->inRandomOrder()->first();

                        $ran['id']          = $showsr->id;
                        $ran['name']        = $showsr->name;
                        $ran['type']        = 'show';
                        $ran['image']       = URL::to('uploads/show/images/'.$showsr->image);


                        $list['recomandtion'][]        = $ran;


                        # show rec

                        $majs = Shows_Sec::where('section_id' , $section->id)->pluck('show_id')->toArray();
                        $showrec = Show::whereIn('id',$majs)->take(10)->latest()->get();

                        foreach ($showrec as $key => $gui)
                        {
                            $showr['id']          = $gui->id;
                            $showr['name']        = $gui->name;
                            $showr['type']        = 'show';
                            $showr['image']       = URL::to('uploads/show/images/'.$gui->image);

                            $list['show'][]        = $showr;
                        }


                        # magazine sub
                        $sectionm = Mag_Section::with('Magazine')->where('id',$type)->first();


                        $megas = Magazine_Sec::where('section_id' , $sectionm->id)->pluck('maga_id')->toArray();
                        $magazinesl = Magazine::whereIn('id',$megas)->latest()->first();


                        $lastl['id']          = $magazinesl->id;
                        $lastl['name']        = $magazinesl->name;
                        $lastl['type']        = 'magazines';
                        $lastl['image']       = URL::to('uploads/magazine/images/'.$magazinesl->image);


                        $list['recomandtion'][]        = $lastl;

                        $magazinesc = Magazine::whereIn('id',$megas)->where('id', '<>',$magazinesl->id)->orderBy('rate' , 'desc')->first();


                        $countl['id']          = $magazinesc->id;
                        $countl['name']        = $magazinesc->name;
                        $countl['type']        = 'magazines';
                        $countl['image']       = URL::to('uploads/magazine/images/'.$magazinesc->image);


                        $list['recomandtion'][]        = $countl;


                        $magazinesr = Magazine::whereIn('id',$megas)->where('id', '<>',$magazinesl->id)->where('id', '<>',$magazinesc->id)->inRandomOrder()->first();


                        $ranl['id']          = $magazinesr->id;
                        $ranl['name']        = $magazinesr->name;
                        $ranl['type']        = 'magazines';
                        $ranl['image']       = URL::to('uploads/magazine/images/'.$magazinesr->image);


                        $list['recomandtion'][]        = $ranl;


                        # magazines rec

                        $magazinesrec = Magazine::whereIn('id',$megas)->take(5)->latest()->get();

                        foreach ($magazinesrec as $key => $sti)
                        {
                            $magar['id']          = $sti->id;
                            $magar['name']        = $sti->name;
                            $magar['type']        = 'magazines';
                            $magar['image']       = URL::to('uploads/magazine/images/'.$sti->image);

                            $list['magazine'][]        = $magar;
                        }



                    }

                }
            }

        }
        return $this->ReturnData($list);

    }

    Public function sponsers(){
        $page = System_Ads_Pages::with('SystemAds')->where('type','home')->pluck('ads_id')->toArray();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();
        $list['logos']= LogoBannerResource::collection($logos);
        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

}
