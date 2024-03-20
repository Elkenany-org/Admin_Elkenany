<?php

namespace Modules\Consultants\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Consultants\Entities\Major;
use Modules\Consultants\Entities\Doctor;
use Modules\Consultants\Entities\Doctor_Majors;
use Modules\Consultants\Entities\Sub_Section;
use Modules\Consultants\Entities\Doctor_Services;
use Modules\Consultants\Entities\Doctor_Orders;
use Modules\Consultants\Entities\Doctor_Rating;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\Data_Analysis;
use Modules\SystemAds\Entities\System_Ads;
use Modules\Store\Entities\Customer;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Main;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;

class ApiMajorsController extends Controller
{

    # show SubSections
    public function showmajors(Request $request)
    {

        $type       = Input::get("type");
        $sort       = Input::get("sort");
        $search     = Input::get("search");

            # check section exist
            if(!$type)
            {
                $msg = 'you should send type of section';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);	
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

                        $main_keyword = Data_Analysis_Keywords::where('type',$type)->where('keyword','consultants')->first();
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

            $sections = Major::get();
            $section = Major::where('type',$type)->first();

            # check section exist
            if(!$section)
            {
                $msg = 'section type not correct';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);	
            }

            $subs = Sub_Section::with('Doctor')->where('major_id',$section->id);

            if(!is_null($search))
            {
                $subs = $subs->where('name' , 'like' , "%". $search ."%");
            }

            if(!is_null($sort))
            {
                if($sort == 0)
                {
                    $subs = $subs->orderby('name')->get();
                }elseif($sort == 1){
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

            $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','consultants')->pluck('ads_id')->toArray();

            $ads = System_Ads::where('main','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
            $logos = System_Ads::where('main','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


            if(count($ads) == 0){
                $list['banners'] = [];
            }else{
                foreach ($ads as $key => $ad)
                {
                    $list['banners'][$key]['id']          = $ad->id;
                    $list['banners'][$key]['link']        = $ad->link;
                    $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);
    
    
                }
    
            }
        
            
            if(count($logos) == 0){
                $list['logos'] = [];
            }else{
                foreach ($logos as $key => $logo)
                {
                    $list['logos'][$key]['id']          = $logo->id;
                    $list['logos'][$key]['link']        = $logo->link;
                    $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


                }
            }
        
            $loin = [];
            if(count($subs) == 0){
                $list['sub_sections'] = [];
            }else{
                foreach ($subs as $key => $section)
                {
                    $list['sub_sections'][$key]['id']          = $section->id;
                    $list['sub_sections'][$key]['name']        = $section->name;
                    $list['sub_sections'][$key]['image']       = URL::to('uploads/majors/avatar/'.$section->image);
                    $list['sub_sections'][$key]['Doctor_count']     = count($section->Doctor);

                    if(count($section->logooos()) == 0){
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

            }
        

            return response()->json([
                'message'  => null,
                'error'    => null,
                'data'     => $list
            ],200);

    }

    # show filter sections
    public function filterSections()
    {

        $type = Input::get("type");

        # check section exist
        if(!$type)
        {
            $msg = 'you should send type of section';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $sections = Major::get();
        
        $section = Major::where('type',$type)->first();
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
    
  
}
