<?php

namespace Modules\LocalStock\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\SearchReg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\FodderStock\Http\Services\FodderService;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\LocalStock\Entities\Local_Stock_Sub;
use Modules\LocalStock\Entities\Local_Stock_Detials;
use Modules\LocalStock\Entities\Local_Stock_Member;
use Modules\LocalStock\Entities\Local_Stock_Movement;
use Modules\LocalStock\Entities\Local_Stock_Columns;
use Modules\LocalStock\Entities\Sec_All;
use Modules\FodderStock\Entities\Fodder_Stock;
use Modules\FodderStock\Entities\Fodder_Stock_Move;
use Modules\FodderStock\Entities\Stock_Feeds;
use Modules\FodderStock\Entities\Stock_Fodder_Section;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use Modules\LocalStock\Http\Services\GuideService;
use Modules\LocalStock\Http\Services\LocalService;
use Modules\LocalStock\Transformers\StockFeedsResource;
use Modules\LocalStock\Transformers\TableLocalResource;
use Modules\LocalStock\Transformers\TableMemberLocalResource;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\Data_Analysis;
use Modules\SystemAds\Entities\System_Ads;
use Modules\Store\Entities\Customer;
use Modules\Guide\Entities\Company;
use Modules\FodderStock\Entities\Mini_Sub;
use Carbon\Carbon;
use Modules\SystemAds\Transformers\LogoBannerResource;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;

class ApiStockController extends Controller
{
    use SearchReg,ApiResponse;
    # show all sections
    public function ShowSections(Request $request)
    {

        $type =$request->input("type");

        $search = $request->input("search");

        $sort = $request->input("sort"); //new
        $section_id = $request->input("section_id");



        # check section exist
        if(!$section_id)
        {
            $section = Local_Stock_Sections::where('selected','1')->first();
            $sectionsf = Stock_Fodder_Section::where('selected','1')->first();

        }else{
            $section = Local_Stock_Sections::where('id',$section_id)->first();
            $sectionsf = Stock_Fodder_Section::with('FodderStocks')->where('id',$section_id)->first();

        }

        # check section exist
        if(!$section || !$sectionsf)
        {
            $msg = 'section not found';
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

                    $main_keyword = Data_Analysis_Keywords::where('keyword','stock')->where('type',$section->id)->first();
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

//        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','localstock')->pluck('ads_id')->toArray();
        $page = System_Ads_Pages::with('SystemAds')->where('type','localstock')->pluck('ads_id')->toArray();

        $ads = System_Ads::where('main','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::where('main','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();



        $sections = Local_Stock_Sections::latest()->get();

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

        $list['banners'] = count($ads) == 0 ? [] : LogoBannerResource::collection($ads);
        $list['logos'] = count($logos) == 0 ? [] : LogoBannerResource::collection($logos);


        $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
        if(!is_null($search)){
            $keyword = $this->searchQuery($search);
        }
        if(count($majs) == 0)
        { 

            if(!is_null($search)){
                // $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->where('name' , 'like' , "%". $search ."%")->get();
                $subs = Local_Stock_Sub::where('name' , 'REGEXP' , $keyword)->get();
                //new
                if(isset($sort)){
                    if($sort == 0){
                        $subs = Local_Stock_Sub::where('name' , 'REGEXP' , $keyword)->orderby('name', 'ASC')->get();
                    }else{
                        $subs = Local_Stock_Sub::where('name' , 'REGEXP' , $keyword)->orderBy('view_count' , 'desc')->get();
                    }
                }
            }else{
                $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->get();
                // $subs = [];

                // new
                if(isset($sort)){
                    if($sort == 0){
                        $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->orderby('name', 'ASC')->get();
                    }else{
                        $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->orderBy('view_count' , 'desc')->get();
                    }
                }
            }


          
        }else{

           
            if(!is_null($search)){
                // $subs = Local_Stock_Sub::with('LocalStockMembers')->whereIn('id',$majs)->orWhere('section_id',$section->id)->where('name' , 'like' , "%". $search ."%")->get();
//                $subs = Local_Stock_Sub::with('LocalStockMembers')->where('section_id' , $section->id)->Where('name' , 'REGEXP' , $keyword)->get();
                // new
                if(isset($sort)){
                    if($sort == 0){
                        $subs = Local_Stock_Sub::with('LocalStockMembers')->where('section_id' , $section->id)->Where('name' , 'REGEXP' , $keyword)->orderby('name', 'ASC')->get();
                    }else{
                        $subs = Local_Stock_Sub::with('LocalStockMembers')->where('section_id' , $section->id)->Where('name' , 'REGEXP' , $keyword)->orderBy('view_count' , 'desc')->get();
                    }
                }else{
                    $subs = Local_Stock_Sub::with('LocalStockMembers')->where('section_id' , $section->id)->Where('name' , 'REGEXP' , $keyword)->orderBy('sort')->get();

                }
               
            }else{
       
//                $subs = Local_Stock_Sub::with('LocalStockMembers')->whereIn('id',$majs)->orWhere('section_id',$section->id)->get();
                // $subs = [];
                //new
                if(isset($sort)){
                    if($sort == 0){
                        $subs = Local_Stock_Sub::with('LocalStockMembers')->whereIn('id',$majs)->orWhere('section_id',$section->id)->orderby('name', 'ASC')->get();
                    }else{
                        $subs = Local_Stock_Sub::with('LocalStockMembers')->whereIn('id',$majs)->orWhere('section_id',$section->id)->orderBy('view_count' , 'desc')->get();
                    }
                }else{
                    $subs = Local_Stock_Sub::with('LocalStockMembers')->whereIn('id',$majs)->orWhere('section_id',$section->id)->orderBy('sort')->get();
                }
            }
      
        }
        $loin = [];
        $foin = [];
        if(count($subs) == 0){
            $list['sub_sections'] = [];
        }else{
            foreach ($subs as $key => $sub)
            {
                $list['sub_sections'][$key]['id']          = $sub->id;
                $list['sub_sections'][$key]['name']        = $sub->name;
                $list['sub_sections'][$key]['image']       = URL::to('uploads/sections/sub/'.$sub->image);
                $list['sub_sections'][$key]['members']     = count($sub->LocalStockMembers);
                $list['sub_sections'][$key]['type']        = 'local';

                if(count($sub->logooos()) == 0){
                    $list['sub_sections'][$key]['logo_in'] = [];
                }else{
                    foreach ($sub->logooos() as $looo)
                    {
                        $loin['id']         = $looo->id;
                        $loin['link']       = $looo->link;
                        $loin['image']      = URL::to('uploads/full_images/'.$looo->image);
    
                        $list['sub_sections'][$key]['logo_in'][] = $loin;
                    
                    }
        
                }
                
            }
        }
        
        

        
        if($sectionsf)
        {

            if(!is_null($search)){
               
                $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->where('name' , 'REGEXP' , $keyword)->get();
            }else{
       
                $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->orderby('name')->get();

            }
          
            if(count($subss) == 0){
                $list['fod_sections'] = [];
            }else{
                foreach ($subss as $key => $subf)
                {
                    $list['fod_sections'][$key]['id']          = $subf->id;
                    $list['fod_sections'][$key]['name']        = $subf->name;
                    $list['fod_sections'][$key]['image']       = URL::to('uploads/sections/avatar/'.$subf->image);
                    $list['fod_sections'][$key]['members']     = count($subf->FodderStocks);
                    $list['fod_sections'][$key]['type']        = 'fodder';

                    if(count($subf->logooss()) == 0){
                        $list['fod_sections'][$key]['logo_in'] = [];
                    }else{
                        foreach ($subf->logooss() as $lfooo)
                        {
                            $foin['id']         = $lfooo->id;
                            $foin['link']       = $lfooo->link;
                            $foin['image']      = URL::to('uploads/full_images/'.$lfooo->image);

                            $list['fod_sections'][$key]['logo_in'][] = $foin;
                        
                        }
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
    public function filterSections(Request $request)
    {

        $type = $request->input("type");
        $section_id = $request->input("section_id");

        $sections = Local_Stock_Sections::get(['id','name','type']);

        # check section exist
        if(!$section_id)
        {
            $section = Local_Stock_Sections::where('selected','1')->first();
        }else{
            $section = Local_Stock_Sections::where('id',$section_id)->first();
        }
        # check section exist
        if(!$section) { return $this->ErrorMsg('section not found'); }

        $sections->map(function($sec) use($section){
            $sec['selected'] = $sec->id == $section->id ?  1 : 0;
            return $sec;
        });

        return $this->ReturnData(['sectors'=>$sections,'sort'=>$this->SortRecentAlpha()]);
    }


 
    # sub sections filter
    public function SubFilter(Request $request)
    {

        $type = $request->type;
        $section_id = $request->section_id;

        if(!is_null($request->search)){
            $keyword = $this->searchQuery($request->search);

        }

        if(!$section_id)
        {
            $section = Local_Stock_Sections::where('selected','1')->first();
            $sectionsf = Stock_Fodder_Section::with('FodderStocks')->where('selected','1')->first();
        }else{
            $section = Local_Stock_Sections::where('id',$section_id)->first();
            $sectionsf = Stock_Fodder_Section::with('FodderStocks')->where('id',$section_id)->first();
        }
        if(!is_null($request->sort)){
            if($request->sort == 0){
                if(!is_null($request->search)){

                    $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
                    if(count($majs) == 0)
                    {
                        $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->where('name' , 'REGEXP' , $keyword)->orderby('name')->get();
                    }else{
                        $subs = Local_Stock_Sub::whereIn('id',$majs)->orWhere('section_id',$section->id)->with('LocalStockMembers')->where('name' , 'REGEXP' , $keyword)->orderby('name')->get();

                    }
                    if($sectionsf)
                    {
                        $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->where('name' , 'REGEXP' , $keyword)->orderby('name')->get();

                    }
                }else{

                    $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
                    if(count($majs) == 0)
                    {
                        $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->orderby('name')->get();
                    }else{

                        $subs = Local_Stock_Sub::whereIn('id',$majs)->orWhere('section_id',$section->id)->with('LocalStockMembers')->orderby('name')->get();
                    }
                    if($sectionsf)
                    {
                        $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->orderby('name')->get();

                    }
                }

            }else{
                if(!is_null($request->search)){
                    $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
                    if(count($majs) == 0)
                    {
                        $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->where('name' , 'REGEXP' , $keyword)->orderby('view_count' , 'desc')->get();
                    }else{

                        $subs = Local_Stock_Sub::where('name' , 'REGEXP' , $keyword)->whereIn('id',$majs)->orWhere('section_id',$section->id)->with('LocalStockMembers')->orderby('view_count' , 'desc')->get();
                    }
                    if($sectionsf)
                    {
                        $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->where('name' , 'REGEXP' , $keyword)->orderby('name')->get();

                    }

                }else{

                    $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
                    if(count($majs) == 0)
                    {
                        $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->orderby('view_count' , 'desc')->get();
                    }else{

                        $subs = Local_Stock_Sub::whereIn('id',$majs)->orWhere('section_id',$section->id)->with('LocalStockMembers')->orderby('view_count' , 'desc')->get();
                    }
                    if($sectionsf)
                    {
                        $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->orderby('name')->get();

                    }

                }

            }
        }else{
            if(!is_null($request->search)){

                $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
                if(count($majs) == 0)
                {
                    $subs = Local_Stock_Sub::where('name' , 'REGEXP' , $keyword)->where('section_id',$section->id)->with('LocalStockMembers')->get();
                }else{

                    $subs = Local_Stock_Sub::with('LocalStockMembers')->where('name' , 'REGEXP' , $keyword)->whereIn('id',$majs)->orWhere('section_id',$section->id)->get();
                }
                if($sectionsf)
                {
                    $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->where('name' , 'REGEXP' , $keyword)->get();

                }
            }else{

                $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
                if(count($majs) == 0)
                {
                    $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->orderby('name')->get();
                }else{

                    $subs = Local_Stock_Sub::whereIn('id',$majs)->orWhere('section_id',$section->id)->with('LocalStockMembers')->orderby('name')->get();
                }
                if($sectionsf)
                {
                    $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->orderby('name')->get();

                }

            }

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



        $sections = Local_Stock_Sections::latest()->get();

        $list = [];


        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$section->type)->where('type','localstock')->pluck('ads_id')->toArray();

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


        $lolo = [];

        $foin = [];

        foreach ($subs as $key => $sub)
        {
            $list['sub_sections'][$key]['id']          = $sub->id;
            $list['sub_sections'][$key]['name']        = $sub->name;
            $list['sub_sections'][$key]['image']       = URL::to('uploads/sections/sub/'.$sub->image);
            $list['sub_sections'][$key]['members']     = count($sub->LocalStockMembers);
            $list['sub_sections'][$key]['type']        = 'local';

            if(count($sub->logooos()) == 0){
                $list['sub_sections'][$key]['logo_in'] = [];
            }else{
                foreach ($sub->logooos() as $looo)
                {
                    $lolo['id']         = $looo->id;
                    $lolo['link']       = $looo->link;
                    $lolo['image']      = URL::to('uploads/full_images/'.$looo->image);

                    $list['sub_sections'][$key]['logo_in'][] = $lolo;

                }
            }


        }



        if($sectionsf)
        {


            foreach ($subss as $key => $subf)
            {
                $list['fod_sections'][$key]['id']          = $subf->id;
                $list['fod_sections'][$key]['name']        = $subf->name;
                $list['fod_sections'][$key]['image']       = URL::to('uploads/sections/avatar/'.$subf->image);
                $list['fod_sections'][$key]['members']     = count($subf->FodderStocks);
                $list['fod_sections'][$key]['type']        = 'fodder';

                if(count($subf->logooss()) == 0){
                    $list['fod_sections'][$key]['logo_in'] = [];
                }else{
                    foreach ($subf->logooss() as $lfooo)
                    {
                        $foin['id']         = $lfooo->id;
                        $foin['link']       = $lfooo->link;
                        $foin['image']      = URL::to('uploads/full_images/'.$lfooo->image);

                        $list['fod_sections'][$key]['logo_in'][] = $foin;

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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    # statistics sections
//    public function statisticsSections(Request $request)
//    {
//
//        $type = $request->input()"type");
//        $from  = $request->input()"from");
//        $to    = $request->input()"to");
//        $id    = $request->input()"id");
//
//        $section = Local_Stock_Sections::where('type',$type)->first();
//
//        # check section exist
//        if(!$section) { return response()->json(['message'  => null, 'error'    => 'section not found',],400); }
//
//        $list = [];
//
//        $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id');
//        $list_subs = Local_Stock_Sub::select('id','name')->where('section_id',$section->id)->orWhereIn('id',$majs)->get();
//
//        $sections = Local_Stock_Sub::with(['LocalStockCounts'=>function($q) use ($from,$to){
//            $q->where('result','!=','0')->where('change','!=','0');
//            if($from && $to){
//                $q->whereBetween('created_at', [$from, $to]);
//            }
//        }]);
//        if($id){
//            $sections->where('id',$id);
//        }else{
//            $sections->where('section_id',$section->id)->orWhereIn('id',$majs);
//        }
//        $sections = $sections->get();
//
//            $list['changes_subs'] = [];
//
//            if(count($sections) > 0){
//                foreach ($sections as $key => $sec)
//                {
//                    $list['changes_subs'][$key]['id']         = $sec->id;
//                    $list['changes_subs'][$key]['name']       = $sec->name;
//                    $list['changes_subs'][$key]['change']     = (string) $sec->laust();
//                    $list['changes_subs'][$key]['counts']     = $sec->counts();
//
//                    if($request->hasHeader('device')) {
//                     foreach ($sec->LocalStockCounts as $ke => $cha)
//                         {
//                             $list['changes_subs'][$key]['changes'][$ke]['date']          = date('Y-m-d',strtotime($cha->created_at));
//                             $list['changes_subs'][$key]['changes'][$ke]['change']        = $cha->change;
//                         }
//                    }
//                }
//            }
//
//
//
//        return response()->json([
//            'message'  => null,
//            'error'    => null,
//            'data'     => ['Section'=>$section->name,'list_subs'=>$list_subs,'changes_subs'=>$list['changes_subs']]
//        ],200);
//
//    }

    public function statisticsSections(Request $request)
    {

        $type = $request->input("type");
        $from  = $request->input("from");
        $to    = $request->input("to");
        $id    = $request->input("id");
        $section_id = $request->input("section_id");

        if(!$section_id)
        {
            $section = Local_Stock_Sections::where('selected','1')->first();
        }else{
            $section = Local_Stock_Sections::where('id',$section_id)->first();
        }
        # check section exist
        if(!$section) { return response()->json(['message'  => null, 'error'    => 'section not found',],400); }

        $list = [];

        $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id');
        $list_subs = Local_Stock_Sub::select('id','name')->where('section_id',$section->id)->orWhereIn('id',$majs)->get();

        $sections = Local_Stock_Sub::with(['LocalStockCounts'=>function($q) use ($from,$to){
            $q->where('result','!=','0')->where('change','!=','0');
            if($from && $to){
                $q->whereBetween('created_at', [$from, $to]);
            }
        }]);
        if($id){
            $sections->where('id',$id);
        }else{
            $sections->where('section_id',$section->id)->orWhereIn('id',$majs);
        }
        $sections = $sections->get();

        $list['changes_subs'] = [];

        $arr=array();
        if(count($sections) > 0){
            foreach ($sections as $key => $sec)
            {
                $list['changes_subs'][$key]['id']         = $sec->id;
                $list['changes_subs'][$key]['name']       = $sec->name;
                $list['changes_subs'][$key]['change']     = (string) $sec->laust($request);
                $list['changes_subs'][$key]['counts']     = $sec->counts($request);

                foreach ($sec->LocalStockCounts as $ke => $cha)
                {
                    $list['changes_subs'][$key]['changes'][$ke]['date']          = date('Y-m-d',strtotime($cha->created_at));
                    $list['changes_subs'][$key]['changes'][$ke]['change']        = $cha->change;

                }
                if(isset($list['changes_subs'][$key]['changes'][0])){

                    array_push($arr,(object)['date'=>$list['changes_subs'][$key]['changes'][0]['date'],'change'=>$list['changes_subs'][$key]['changes'][0]['change']]);

                foreach ($list['changes_subs'][$key]['changes'] as $k =>$value) {

                    if (isset($list['changes_subs'][$key]['changes'][$k + 1])) {

                        if ($list['changes_subs'][$key]['changes'][$k]['date'] == $list['changes_subs'][$key]['changes'][$k + 1]['date']) {
                            array_pop($arr);
                            array_push($arr, (object)['date' => $list['changes_subs'][$key]['changes'][$k + 1]['date'], 'change' => $list['changes_subs'][$key]['changes'][$k + 1]['change']]);
                        } else {
                            array_push($arr, (object)['date' => $list['changes_subs'][$key]['changes'][$k + 1]['date'], 'change' => $list['changes_subs'][$key]['changes'][$k + 1]['change']]);
                        }
                    }

                }
                }
                $list['changes_subs'][$key]['changes']=$arr;
                $arr=[];

            }

        }


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => ['Section'=>$section->name,'list_subs'=>$list_subs,'changes_subs'=>$list['changes_subs']]
//            'data'     => $list['changes_subs'],
//            'sum'=>$arr

        ],200);

    }

    # show all member to subsection
    public function showmembers(Request $request, FodderService $service, LocalService $localService)
    {
        
        $id = $request->input("id");
        $type = $request->input("type");
        $date = $request->input("date");
        $fod_id = $request->input("fod_id");
        $comp_id = $request->input("comp_id");


        $list = [];

        $list['columns'] = [];

        if($date){
            $date = $request->input("date");
        }else{
            $date = date('Y-m-d');
        }

        if(Auth::guard('customer-api')->user() && Auth::guard('customer-api')->user()->memb != 1){

            if($date && $date < Carbon::now()->subDays(7)) {
                return $this->ErrorMsg('not membership');
            }
        }else{

            if($date && $date < Carbon::now()->subDays(7) && !Auth::guard('customer-api')->user()){
                return $this->ErrorMsg('not membership');
            }
        }


        if($type == 'fodder'){

            $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();

            # check sub exist
            if(!$sub) { return $this->ErrorMsgWithStatus('sub not found');}

            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();



            $list['banners'] =  LogoBannerResource::collection($adss);
            $list['logos'] =  LogoBannerResource::collection($logos);

            $feeds = Stock_Feeds::select('id','name')->where('section_id',$id)->orderBy('fixed' , 'desc');
            if($fod_id){
                $feeds->where('id','!=',$fod_id);
            }
            $feeds = $feeds->get();


            foreach ($feeds as $kf => $fed)
            {
                $list['feeds'][] = [
                    'name'=>$fed->name,
                    'id'=>$fed->id,
                ];
            }

            if($fod_id){
                $selected_feeds = Stock_Feeds::select('id','name')->where('section_id',$id)->where('id',$fod_id)->first();
                array_unshift($list['feeds'],$selected_feeds);
            }

            $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$id)->latest();
            if($comp_id){
                $fodss->where('company_id','!=',$comp_id);
            }
            $fodss = $fodss->get()->unique('company_id');


            // companis
            foreach ($fodss as $kcf => $fecd)
            {
                $list['companies'][] = [
                    'name'=>$fecd->Company->name,
                    'id'=>$fecd->Company->id
                ];
            }

            if($comp_id){
                $selected_company = Company::select('id','name')->where('id',$comp_id)->first();
                array_unshift($list['companies'],$selected_company);
            }


            $members =  $service->FodderTableProccess($ads,$date);

            while(count($members) == 0){
                $date = date("Y-m-d", strtotime($date ."-1 day"));
                $day = date('l', strtotime($date));

                if($day == 'Friday'){
                    $date = date( 'Y-m-d', strtotime( $date . ' -1 day' ) );
                }
                $members =  $service->FodderTableProccess($ads,$date);
            }

            $list['members'] = $members;

            $list['section_type']   =  $sub->Section->type;

            $list['columns'][]["title"]   = ' الأسم';
            $list['columns'][]["title"]   = 'الصنف';
            $list['columns'][]["title"]   = 'السعر';
            $list['columns'][]["title"]   = 'مقدار التغير';
            $list['columns'][]["title"]   = 'إتجاه السعر';

        }elseif($type == 'local'){
                $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();

                # check sub exist
                if(!$sub) { return $this->ErrorMsgWithStatus('sub not found');}

                $sub->view_count = $sub->view_count + 1;

                $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

                $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                $list['banners'] =  LogoBannerResource::collection($adss);
                $list['logos'] =  LogoBannerResource::collection($logos);


                // columns
                $list['columns'][]["title"]   = "الأسم";
                foreach ($sub->LocalStockColumns as $key => $col)
                {
                    if($col->type == 'price' ){
                        $list['columns'][]["title"]   = $col->name;
                    }

                    if($col->type == 'change' ){
                        $list['columns'][]["title"]   = $col->name;
                    }

                    if($col->type == null ){
                        $list['columns'][]["title"]   = $col->name;
                    }
                }
                $list['columns'][]["title"]   = 'إتجاه السعر';

                $members = $localService->TableLikeweb($request,$ads,$date);

                    while(count($members) == 0){
                        $date = date("Y-m-d", strtotime($date ."-1 day"));
                        $day = date('l', strtotime($date));

                        if($day == 'Friday'){
                            $date = date( 'Y-m-d', strtotime( $date . ' -1 day' ) );
                        }
                        $members = $localService->TableLikeweb($request,$ads,$date);
                    }
                $list['members']               = $members;
            }

        return $this->ReturnData($list);
    }

    # show filter sections
    public function GetSectionsInFilter(Request $request)
    {

        $type = $request->input("type");
        $id = $request->input("id");
        $type_stock = $request->input("type_stock");
        $sections = Local_Stock_Sections::get();
        $section_id = $request->input("section_id");

        $sectionsf = Stock_Fodder_Section::with('FodderStocks')->where('id',$section_id)->first();
        # check section exist
        if(!$section_id)
        {
            $section = Local_Stock_Sections::where('selected','1')->first();
            $sectionsf = Stock_Fodder_Section::with('FodderStocks')->where('selected','1')->first();
        }else{
            $section = Local_Stock_Sections::where('id',$section_id)->first();
            $sectionsf = Stock_Fodder_Section::with('FodderStocks')->where('id',$section_id)->first();

        }
        if(!$section)
        {
            $msg = 'section not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $list = [];

        if(is_null($type_stock)){

            $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
            if(count($majs) == 0)
            {
                $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->orderby('name')->get();
            }else{
                $subs = Local_Stock_Sub::where('section_id',$section->id)->orWhereIn('id',$majs)->with('LocalStockMembers')->orderby('name')->get();
            }
            if($sectionsf)
            {
                $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->orderby('name')->get();

            }

            if(count($sections) > 0) {
                foreach ($sections as $key => $sec) {
                    $list['sectors'][$key]['id'] = $sec->id;
                    $list['sectors'][$key]['name'] = $sec->name;
                    $list['sectors'][$key]['type_cate'] = $sec->type;
                    if ($sec->id == $section->id) {
                        $list['sectors'][$key]['selected'] = 1;
                    } else {
                        $list['sectors'][$key]['selected'] = 0;
                    }

                }
            }else{
                $list['sectors'] = [];
            }

            if(count($subs) > 0) {
                foreach ($subs as $key => $sub) {
                    $list['sub_sections'][$key]['id'] = $sub->id;
                    $list['sub_sections'][$key]['name'] = $sub->name;
                    $list['sub_sections'][$key]['type_cate'] = 'local';
                    $list['sub_sections'][$key]['type'] = $sub->id;


                }
            }else{
                $list['sub_sections'] = [];
            }

            if($sectionsf)
            {

                if(count($subss) > 0) {
                    foreach ($subss as $key => $subf) {
                        $list['fodder_sub_sections'][$key]['id'] = $subf->id;
                        $list['fodder_sub_sections'][$key]['name'] = $subf->name;
                        $list['fodder_sub_sections'][$key]['type_cate'] = 'fodder';
                        $list['fodder_sub_sections'][$key]['type'] = $subf->id;
                    }
                }else{
                    $list['fodder_sub_sections'] = [];
                }
            }

        }else{
            if($type_stock == 'local'){

                $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
                if(count($majs) == 0)
                {
                    $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->orderby('name')->get();
                }else{
                    $subs = Local_Stock_Sub::where('section_id',$section->id)->orWhereIn('id',$majs)->with('LocalStockMembers')->orderby('name')->get();
                }
                if($sectionsf)
                {
                    $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->orderby('name')->get();

                }

                if(count($sections) > 0) {
                    foreach ($sections as $key => $sec) {
                        $list['sections'][$key]['id'] = $sec->id;
                        $list['sections'][$key]['name'] = $sec->name;
                        $list['sections'][$key]['type'] = $sec->type;

                        if ($sec->id == $section->id) {
                            $list['sections'][$key]['selected'] = '1';
                        } else {
                            $list['sections'][$key]['selected'] = '0';
                        }
                    }
                }else{
                    $list['sections'] = [];
                }

                if(count($subs) > 0) {
                    foreach ($subs as $key => $sub) {
                        $list['sub_sections'][$key]['id'] = $sub->id;
                        $list['sub_sections'][$key]['name'] = $sub->name;
                        $list['sub_sections'][$key]['type_cate'] = 'local';
                        $list['sub_sections'][$key]['type'] = $sub->id;


                        if ($sub->id == $id) {
                            $list['sub_sections'][$key]['selected'] = '1';
                        } else {
                            $list['sub_sections'][$key]['selected'] = '0';
                        }
                    }
                }else{
                    $list['sub_sections'] = [];
                }

                if($sectionsf)
                {

                    if(count($subss) > 0) {
                        foreach ($subss as $key => $subf) {
                            $list['fodder_sub_sections'][$key]['id'] = $subf->id;
                            $list['fodder_sub_sections'][$key]['name'] = $subf->name;
                            $list['fodder_sub_sections'][$key]['type_cate'] = 'fodder';
                            $list['fodder_sub_sections'][$key]['type']     = $subf->id;

                        }
                    }else{
                        $list['fodder_sub_sections'] = [];
                    }
                }


            }elseif($type_stock == 'fodder'){

                $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
                if(count($majs) == 0)
                {
                    $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->orderby('name')->get();
                }else{
                    $subs = Local_Stock_Sub::where('section_id',$section->id)->orWhereIn('id',$majs)->with('LocalStockMembers')->orderby('name')->get();
                }
                if($sectionsf)
                {
                    $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->orderby('name')->get();

                }

                if(count($sections) > 0) {
                    foreach ($sections as $key => $sec) {
                        $list['sections'][$key]['id'] = $sec->id;
                        $list['sections'][$key]['name'] = $sec->name;
                        $list['sections'][$key]['type'] = $sec->type;
                        if ($sec->id == $section->id) {
                            $list['sections'][$key]['selected'] = '1';
                        } else {
                            $list['sections'][$key]['selected'] = '0';
                        }

                    }
                }else{
                    $list['sections'] = [];
                }
                if(count($subs) > 0){
                    foreach ($subs as $key => $sub)
                    {
                        $list['sub_sections'][$key]['id']          = $sub->id;
                        $list['sub_sections'][$key]['name']        = $sub->name;
                        $list['sub_sections'][$key]['type_cate']   = 'local';
                        $list['sub_sections'][$key]['type']        = $sub->id;

                    }
                }else{
                    $list['sub_sections'] = [];
                }


                if($sectionsf)
                {

                    if(count($subss) > 0) {
                        foreach ($subss as $key => $subf)
                        {
                            $list['fodder_sub_sections'][$key]['id'] = $subf->id;
                            $list['fodder_sub_sections'][$key]['name'] = $subf->name;
                            $list['fodder_sub_sections'][$key]['type_cate'] = 'fodder';
                            $list['fodder_sub_sections'][$key]['type'] = $subf->id;

                            if ($subf->id == $id) {
                                $list['fodder_sub_sections'][$key]['selected'] = '1';
                            } else {
                                $list['fodder_sub_sections'][$key]['selected'] = '0';
                            }
                        }
                    }else{
                        $list['fodder_sub_sections'] = [];
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

//    public function GetSectionsInFilter()
//    {
//
//        $type = $request->input()"type");
//        $id = $request->input()"id");
//        $type_stock = $request->input()"type_stock");
//        $sections = Local_Stock_Sections::get();
//
//        $section = Local_Stock_Sections::where('type',$type)->first();
//        $sectionsf = Stock_Fodder_Section::with('FodderStocks')->where('type',$section->type)->first();
//        # check section exist
//        if(!$section)
//        {
//            $msg = 'section not found';
//            return response()->json([
//                'message'  => null,
//                'error'    => $msg,
//            ],400);
//        }
//
//        $list = [];
//
//        if(is_null($type_stock)){
//
//            $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
//            if(count($majs) == 0)
//            {
//                $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->orderby('name')->get();
//            }else{
//                $subs = Local_Stock_Sub::where('section_id',$section->id)->orWhereIn('id',$majs)->with('LocalStockMembers')->orderby('name')->get();
//            }
//            if($sectionsf)
//            {
//                $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->orderby('name')->get();
//
//            }
//
//            if(count($sections) > 0) {
//                foreach ($sections as $key => $sec) {
//                    $list['sectors'][$key]['id'] = $sec->id;
//                    $list['sectors'][$key]['name'] = $sec->name;
//                    $list['sectors'][$key]['type'] = $sec->type;
//                    if ($sec->id == $section->id) {
//                        $list['sectors'][$key]['selected'] = 1;
//                    } else {
//                        $list['sectors'][$key]['selected'] = 0;
//                    }
//
//                }
//            }else{
//                $list['sectors'] = [];
//            }
//
//            if(count($subs) > 0) {
//                foreach ($subs as $key => $sub) {
//                    $list['sub_sections'][$key]['id'] = $sub->id;
//                    $list['sub_sections'][$key]['name'] = $sub->name;
//                    $list['sub_sections'][$key]['type'] = 'local';
//                }
//            }else{
//                $list['sub_sections'] = [];
//            }
//
//            if($sectionsf)
//            {
//
//                if(count($subss) > 0) {
//                    foreach ($subss as $key => $subf) {
//                        $list['fodder_sub_sections'][$key]['id'] = $subf->id;
//                        $list['fodder_sub_sections'][$key]['name'] = $subf->name;
//                        $list['fodder_sub_sections'][$key]['type'] = 'fodder';
//                    }
//                }else{
//                    $list['fodder_sub_sections'] = [];
//                }
//            }
//
//        }else{
//            if($type_stock == 'local'){
//
//                $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
//                if(count($majs) == 0)
//                {
//                    $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->orderby('name')->get();
//                }else{
//                    $subs = Local_Stock_Sub::where('section_id',$section->id)->orWhereIn('id',$majs)->with('LocalStockMembers')->orderby('name')->get();
//                }
//                if($sectionsf)
//                {
//                    $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->orderby('name')->get();
//
//                }
//
//                if(count($sections) > 0) {
//                    foreach ($sections as $key => $sec) {
//                        $list['sections'][$key]['id'] = $sec->id;
//                        $list['sections'][$key]['name'] = $sec->name;
//                        $list['sections'][$key]['type'] = $sec->type;
//                        if ($sec->id == $section->id) {
//                            $list['sections'][$key]['selected'] = '1';
//                        } else {
//                            $list['sections'][$key]['selected'] = '0';
//                        }
//                    }
//                }else{
//                    $list['sections'] = [];
//                }
//
//                if(count($subs) > 0) {
//                    foreach ($subs as $key => $sub) {
//                        $list['sub_sections'][$key]['id'] = $sub->id;
//                        $list['sub_sections'][$key]['name'] = $sub->name;
//                        $list['sub_sections'][$key]['type'] = 'local';
//                        if ($sub->id == $id) {
//                            $list['sub_sections'][$key]['selected'] = '1';
//                        } else {
//                            $list['sub_sections'][$key]['selected'] = '0';
//                        }
//                    }
//                }else{
//                    $list['sub_sections'] = [];
//                }
//
//                if($sectionsf)
//                {
//
//                    if(count($subss) > 0) {
//                        foreach ($subss as $key => $subf) {
//                            $list['fodder_sub_sections'][$key]['id'] = $subf->id;
//                            $list['fodder_sub_sections'][$key]['name'] = $subf->name;
//                            $list['fodder_sub_sections'][$key]['type'] = 'fodder';
//                        }
//                    }else{
//                        $list['fodder_sub_sections'] = [];
//                    }
//                }
//
//
//            }elseif($type_stock == 'fodder'){
//
//                $majs = Sec_All::where('section_id' , $section->id)->pluck('sub_id')->toArray();
//                if(count($majs) == 0)
//                {
//                    $subs = Local_Stock_Sub::where('section_id',$section->id)->with('LocalStockMembers')->orderby('name')->get();
//                }else{
//                    $subs = Local_Stock_Sub::where('section_id',$section->id)->orWhereIn('id',$majs)->with('LocalStockMembers')->orderby('name')->get();
//                }
//                if($sectionsf)
//                {
//                    $subss = Stock_Fodder_Sub::with('FodderStocks')->where('section_id',$sectionsf->id)->orderby('name')->get();
//
//                }
//
//                if(count($sections) > 0) {
//                    foreach ($sections as $key => $sec) {
//                        $list['sections'][$key]['id'] = $sec->id;
//                        $list['sections'][$key]['name'] = $sec->name;
//                        $list['sections'][$key]['type'] = $sec->type;
//                        if ($sec->id == $section->id) {
//                            $list['sections'][$key]['selected'] = '1';
//                        } else {
//                            $list['sections'][$key]['selected'] = '0';
//                        }
//
//                    }
//                }else{
//                    $list['sections'] = [];
//                }
//                if(count($subs) > 0){
//                    foreach ($subs as $key => $sub)
//                    {
//                        $list['sub_sections'][$key]['id']          = $sub->id;
//                        $list['sub_sections'][$key]['name']        = $sub->name;
//                        $list['sub_sections'][$key]['type']        = 'local';
//                    }
//                }else{
//                    $list['sub_sections'] = [];
//                }
//
//
//                if($sectionsf)
//                {
//
//                    if(count($subss) > 0) {
//                        foreach ($subss as $key => $subf)
//                        {
//                            $list['fodder_sub_sections'][$key]['id'] = $subf->id;
//                            $list['fodder_sub_sections'][$key]['name'] = $subf->name;
//                            $list['fodder_sub_sections'][$key]['type'] = 'fodder';
//                            if ($subf->id == $id) {
//                                $list['fodder_sub_sections'][$key]['selected'] = '1';
//                            } else {
//                                $list['fodder_sub_sections'][$key]['selected'] = '0';
//                            }
//                        }
//                    }else{
//                        $list['fodder_sub_sections'] = [];
//                    }
//                }
//
//            }
//        }
//
//
//
//
//        return response()->json([
//            'message'  => null,
//            'error'    => null,
//            'data'     => $list
//        ],200);
//
//    }


    //new api for local statistics members instead of statistics members
    public function statisticsLocalmembers(Request $request,FodderService $service){
        $id   = $request->input("id");
        $from = $request->input("from");
        $to   = $request->input("to");

        $mem_id    = $request->input("mem_id");
        $arr1=array();
        $arr2=array();
        $list = [];

        if(is_null($mem_id))
        {

            if(is_null($from) || is_null($to))
            {
                $section = Local_Stock_Sub::where('id',$id)->first();
                $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$section->id)->get();

            }else{
                $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
                $mov = Local_Stock_Movement::whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts','LocalStockDetials')->where('section_id',$section->id)->whereIn('id',$mov)->get();

            }


//                $arr1=array();
            foreach ($members as $key => $sec)
            {
                if(is_null($from) || is_null($to))
                {
                    $moves = Local_Stock_Movement::with('LocalStockDetials')->where('member_id', $sec->id)->get();

                }else{
                    $moves = Local_Stock_Movement::with('LocalStockDetials')->where('member_id', $sec->id)->whereBetween( 'created_at', [$from, $to])->get();
                }
                $list['list_members'][$key]['id']         = $sec->id;
                $list['changes_members'][$key]['id']         = $sec->id;
                if($sec->Company != null){
                    $list['list_members'][$key]['name']   = $sec->Company->name;
                    $list['changes_members'][$key]['name']   = $sec->Company->name;
                }
                // if product
                if($sec->LocalStockproducts != null){
                    $list['list_members'][$key]['name']   = $sec->LocalStockproducts->name;
                    $list['changes_members'][$key]['name']   = $sec->LocalStockproducts->name;

                }

                foreach($moves as $kk => $v){
                    $list['changes_members'][$key]['changes'][$kk]['price']         = (int)$v->LocalStockDetials[0]->value;
                    $list['changes_members'][$key]['changes'][$kk]['date']         = date('Y-m-d',strtotime($v->created_at));
                }

                $list['changes_members'][$key]['changes']=$service->unique_price_date_for_statistics($list['changes_members'][$key]['changes']);



                $list['changes_members'][$key]['change']     = $service->change_rate($list['changes_members'][$key]['changes']);
                $list['changes_members'][$key]['counts'] = $service->counts($list['changes_members'][$key]['changes'],$key);


            }

        }else {

            if (is_null($from) || is_null($to)) {

                $section = Local_Stock_Sub::where('id', $id)->first();
                $membo = Local_Stock_Member::with('MemberCounts', 'Company', 'LocalStockproducts')->where('id', $mem_id)->first();
                $members = Local_Stock_Member::with('MemberCounts', 'Company', 'LocalStockproducts')->where('section_id', $section->id)->get();

            } else {

                $section = Local_Stock_Sub::with('LocalStockColumns', 'LocalStockCounts', 'LocalStockMembers.Company', 'LocalStockMembers.LocalStockproducts')->where('id', $id)->first();
                $mov = Local_Stock_Movement::whereBetween('created_at', [$from, $to])->pluck('member_id')->toArray();
                $membo = Local_Stock_Member::with('MemberCounts', 'Company', 'LocalStockproducts')->where('id', $mem_id)->whereIn('id', $mov)->first();
                $members = Local_Stock_Member::with('MemberCounts', 'Company', 'LocalStockproducts')->where('section_id', $section->id)->get();
            }


            foreach ($members as $key => $sec) {

                $list['list_members'][$key]['id'] = $sec->id;
                if ($sec->Company != null) {
                    $list['list_members'][$key]['name'] = $sec->Company->name;
                }
                // if product
                if ($sec->LocalStockproducts != null) {
                    $list['list_members'][$key]['name'] = $sec->LocalStockproducts->name;
                }

            }

            # check section exist
            if (!$membo) {
                $msg = 'member not found';
                return response()->json([
                    'message' => null,
                    'error' => $msg,
                ], 400);
            }

            if ($membo) {
                if (is_null($from) || is_null($to)) {
                    $moves = Local_Stock_Movement::with('LocalStockDetials')->where('member_id', $membo->id)->get();

                } else {
                    $moves = Local_Stock_Movement::with('LocalStockDetials')->where('member_id', $membo->id)->whereBetween('created_at', [$from, $to])->get();
                }
                $list['changes_members']['id'] = $membo->id;
                if ($membo->Company != null) {
                    $list['changes_members']['name'] = $membo->Company->name;
                }
                // if product
                if ($membo->LocalStockproducts != null) {
                    $list['changes_members']['name'] = $membo->LocalStockproducts->name;
                }


                foreach ($moves as $kk => $v) {
                    $list['changes_members']['changes'][$kk]['price'] = (int)$v->LocalStockDetials[0]->value;
                    $list['changes_members']['changes'][$kk]['date'] = date('Y-m-d', strtotime($v->created_at));
                }


                $arr2=$service->unique_price_date_for_statistics($list['changes_members']['changes']);

                $list['changes_members']['change']     = $service->change_rate($arr2);
                if($arr2 == []){
                    $list['changes_members']['counts']     = count($arr2);
                }else{
                    $list['changes_members']['counts']     = count($arr2)-1;
                }
                $list['changes_members']['changes']=$arr2;



            }
            $temp=$list['changes_members'];
            $list['changes_members']=[$temp];
        }






        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }

    # statistics members
    public function statisticsmembers(Request $request)
    {

        $id   = $request->input("id");
        $type = $request->input("type");
        $from = $request->input("from");
        $to   = $request->input("to");
        
        $mem_id    = $request->input("mem_id");

        $list = [];
        $change = [];
      
      

        if($type == 'local'){

            if(is_null($mem_id))
            {

                if(is_null($from) || is_null($to))
                {
                    
                    $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
                    $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$section->id)->get();
                
                }else{
    
                     # check recomndation system
                    if(!is_null($request->header('Authorization')))
                    {
                        $token = $request->header('Authorization'); 
                        $token = explode(' ',$token);
                        if(count( $token) == 2)
                        {
    
                            $customer = Customer::where('api_token',$token[1])->first();
    
                                if($customer->memb == '1')
                                {
                                    $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
                                    $mov = Local_Stock_Movement::whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                                    $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$section->id)->whereIn('id',$mov)->get();
                    
                                }else{
                    
                                    if($from < Carbon::now()->subDays(7)){
                                        $msg = ' not membership';
                                        return response()->json([
                                            'message'  => null,
                                            'error'    => $msg,
                                        ],400);	
                                    }else{
                                        $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
                                        $mov = Local_Stock_Movement::whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                                        $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$section->id)->whereIn('id',$mov)->get();
                                    }
                                  
                    
                                }
    
                        }else{
                            if($from < Carbon::now()->subDays(7)){
                                $msg = ' not membership';
                                return response()->json([
                                    'message'  => null,
                                    'error'    => $msg,
                                ],400);	
                            }else{
                                $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
                                $mov = Local_Stock_Movement::whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                                $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$section->id)->whereIn('id',$mov)->get();
                            }
                        }
                    }else{
                        if($from < Carbon::now()->subDays(7)){
                            $msg = ' not membership';
                            return response()->json([
                                'message'  => null,
                                'error'    => $msg,
                            ],400);	
                        }else{
                            $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
                            $mov = Local_Stock_Movement::whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                            $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$section->id)->whereIn('id',$mov)->get();
                        }
                    }
    
              
                }

                foreach ($members as $key => $sec)
                {
                    $list['list_members'][$key]['id']         = $sec->id;
                    if($sec->Company != null){
                        $list['list_members'][$key]['name']   = $sec->Company->name;
                    }
                    // if product
                    if($sec->LocalStockproducts != null){
                        $list['list_members'][$key]['name']   = $sec->LocalStockproducts->name;
                    }
                   
        
           
                }
        
                foreach ($members as $key => $sec)
                {
                    $list['changes_members'][$key]['id']         = $sec->id;
                    if($sec->Company != null){
                        $list['changes_members'][$key]['name']   = $sec->Company->name;
                    }
                    // if product
                    if($sec->LocalStockproducts != null){
                        $list['changes_members'][$key]['name']   = $sec->LocalStockproducts->name;
                    }
                    $list['changes_members'][$key]['change']     =(string) $sec->oldprice();
                    $list['changes_members'][$key]['counts']     = $sec->counts();
             
        
                    // foreach ($sec->LocalStockCounts as $ke => $cha)
                    // {
                    //     $list['changes_subs'][$key]['changes'][$ke]['date']          = $cha->created_at;
                    //     $list['changes_subs'][$key]['changes'][$ke]['change']        = $cha->change;
                 
            
            
                    // }
           
                }

            }else{

                if(is_null($from) || is_null($to))
                {
                    
                    $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
                    $membo = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('id',$mem_id)->first();
                    $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$section->id)->get();
                
                }else{
    
                     # check recomndation system
                    if(!is_null($request->header('Authorization')))
                    {
                        $token = $request->header('Authorization'); 
                        $token = explode(' ',$token);
                        if(count( $token) == 2)
                        {
    
                            $customer = Customer::where('api_token',$token[1])->first();
    
                                if($customer->memb == '1')
                                {
                                    $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
                                    $mov = Local_Stock_Movement::whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                                    $membo = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('id',$mem_id)->whereIn('id',$mov)->first();
                                    $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$section->id)->get();
                    
                                }else{
                    
                                    if($from < Carbon::now()->subDays(7)){
                                        $msg = ' not membership';
                                        return response()->json([
                                            'message'  => null,
                                            'error'    => $msg,
                                        ],400);	
                                    }else{
                                        $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
                                        $mov = Local_Stock_Movement::whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                                        $membo = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('id',$mem_id)->whereIn('id',$mov)->first();
                                        $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$section->id)->get();
                                    }
                                  
                    
                                }
    
                        }else{
                            if($from < Carbon::now()->subDays(7)){
                                $msg = ' not membership';
                                return response()->json([
                                    'message'  => null,
                                    'error'    => $msg,
                                ],400);	
                            }else{
                                $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
                                $mov = Local_Stock_Movement::whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                                $membo = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('id',$mem_id)->whereIn('id',$mov)->first();
                                $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$section->id)->get();
                            }
                        }
                    }else{
                        if($from < Carbon::now()->subDays(7)){
                            $msg = ' not membership';
                            return response()->json([
                                'message'  => null,
                                'error'    => $msg,
                            ],400);	
                        }else{
                            $section = Local_Stock_Sub::with('LocalStockColumns','LocalStockCounts','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
                            $mov = Local_Stock_Movement::whereBetween( 'created_at', [$from, $to])->pluck('member_id')->toArray();
                            $membo = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('id',$mem_id)->whereIn('id',$mov)->first();
                            $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$section->id)->get();
                        }
                    }
    
              
                }
        
                if(!$membo)
                {
                    $msg = 'member not found';
                    return response()->json([
                        'message'  => null,
                        'error'    => $msg,
                    ],400);	
                }
    

                foreach ($members as $key => $sec)
                {
                    $list['list_members'][$key]['id']         = $sec->id;
                    if($sec->Company != null){
                        $list['list_members'][$key]['name']   = $sec->Company->name;
                    }
                    // if product
                    if($sec->LocalStockproducts != null){
                        $list['list_members'][$key]['name']   = $sec->LocalStockproducts->name;
                    }
                  
           
                }

                $atttt = [];
                $atttt['id']         = $membo->id;
                if($membo->Company != null){
                    $atttt['name']   = $membo->Company->name;
                }

                // if product
                if($membo->LocalStockproducts != null){
                    $atttt['name']   = $membo->LocalStockproducts->name;
                }
                $atttt['change']     = (string) $membo->oldprice();
                $atttt['counts']     = $membo->counts();

                $list['changes_members'][]     = $atttt;
    
            }

          
    

        }elseif($type == 'fodder'){

            if(is_null($mem_id))
            {

                if(is_null($from) || is_null($to))
                {
                    
                    $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
                    $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$id)->get();
                }else{
    
                      # check recomndation system
                      if(!is_null($request->header('Authorization')))
                      {
                          $token = $request->header('Authorization'); 
                          $token = explode(' ',$token);
                          if(count( $token) == 2)
                          {
      
                              $customer = Customer::where('api_token',$token[1])->first();
      
                                  if($customer->memb == '1')
                                  {
                                    $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
                                    $mov = Fodder_Stock_Move::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('stock_id')->toArray();
                                    $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$id)->whereIn('id',$mov)->get();
                      
                                  }else{
                      
                                      if($from < Carbon::now()->subDays(7)){
                                          $msg = ' not membership';
                                          return response()->json([
                                              'message'  => null,
                                              'error'    => $msg,
                                          ],400);	
                                      }else{
                                        $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
                                        $mov = Fodder_Stock_Move::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('stock_id')->toArray();
                                        $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$id)->whereIn('id',$mov)->get();
                                      }
                                    
                      
                                  }
      
                          }else{
                            if($from < Carbon::now()->subDays(7)){
                                $msg = ' not membership';
                                return response()->json([
                                    'message'  => null,
                                    'error'    => $msg,
                                ],400);	
                            }else{
                              $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
                              $mov = Fodder_Stock_Move::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('stock_id')->toArray();
                              $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$id)->whereIn('id',$mov)->get();
                            }
                          }
                      }else{
    
                        if($from < Carbon::now()->subDays(7)){
                            $msg = ' not membership';
                            return response()->json([
                                'message'  => null,
                                'error'    => $msg,
                            ],400);	
                        }else{
                          $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
                          $mov = Fodder_Stock_Move::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('stock_id')->toArray();
                          $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$id)->whereIn('id',$mov)->get();
                        }
    
                      }
                   
                }

                foreach ($feeds as $key => $sec)
                {
                    $list['list_members'][$key]['id']         = $sec->id;
                    $list['list_members'][$key]['name']   = $sec->Company->name;
            
                }
        
                foreach ($feeds as $key => $sec)
                {
                    $list['changes_members'][$key]['id']         = $sec->id;
                    $list['changes_members'][$key]['name']   = $sec->Company->name;
                   
                    $list['changes_members'][$key]['change']     = $request->hasHeader('app') ? (string) $sec->oldprice() : $sec->oldprice();
                    $list['changes_members'][$key]['counts']     = $sec->counts();
             
        
                    // foreach ($sec->LocalStockCounts as $ke => $cha)
                    // {
                    //     $list['changes_subs'][$key]['changes'][$ke]['date']          = $cha->created_at;
                    //     $list['changes_subs'][$key]['changes'][$ke]['change']        = $cha->change;
                 
            
            
                    // }
           
                }

            }else{

                if(is_null($from) || is_null($to))
                {
                    
                    $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
                    $feeo = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('id',$mem_id)->first();
                    $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$id)->get();
                }else{
    
                      # check recomndation system
                      if(!is_null($request->header('Authorization')))
                      {
                          $token = $request->header('Authorization'); 
                          $token = explode(' ',$token);
                          if(count( $token) == 2)
                          {
      
                              $customer = Customer::where('api_token',$token[1])->first();
      
                                  if($customer->memb == '1')
                                  {
                                    $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
                                    $mov = Fodder_Stock_Move::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('stock_id')->toArray();
                                    $feeo = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('id',$mem_id)->whereIn('id',$mov)->first();
                                    $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$id)->get();
                      
                                  }else{
                      
                                      if($from < Carbon::now()->subDays(7)){
                                          $msg = ' not membership';
                                          return response()->json([
                                              'message'  => null,
                                              'error'    => $msg,
                                          ],400);	
                                      }else{
                                        $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
                                        $mov = Fodder_Stock_Move::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('stock_id')->toArray();
                                        $feeo = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('id',$mem_id)->whereIn('id',$mov)->first();
                                        $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$id)->get();
                                      }
                                    
                      
                                  }
      
                          }else{
                            if($from < Carbon::now()->subDays(7)){
                                $msg = ' not membership';
                                return response()->json([
                                    'message'  => null,
                                    'error'    => $msg,
                                ],400);	
                            }else{
                              $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
                              $mov = Fodder_Stock_Move::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('stock_id')->toArray();
                              $feeo = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('id',$mem_id)->whereIn('id',$mov)->first();
                              $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$id)->get();
                            }
                          }
                      }else{
    
                        if($from < Carbon::now()->subDays(7)){
                            $msg = ' not membership';
                            return response()->json([
                                'message'  => null,
                                'error'    => $msg,
                            ],400);	
                        }else{
                          $section = Stock_Fodder_Sub::where('id',$id)->latest()->first();
                          $mov = Fodder_Stock_Move::with('LocalStockDetials.LocalStockColumns')->whereBetween( 'created_at', [$from, $to])->pluck('stock_id')->toArray();
                          $feeo = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('id',$mem_id)->whereIn('id',$mov)->first();
                          $feeds = Fodder_Stock::with('Section','FodderStockMoves','Company','StockFeed')->where('sub_id',$id)->get();
                        }
    
                      }
                   
                }

                 # check section exist
                    if(!$feeo)
                    {
                        $msg = 'member not found';
                        return response()->json([
                            'message'  => null,
                            'error'    => $msg,
                        ],400);	
                    }
        
                foreach ($feeds as $key => $sec)
                {
                    $list['list_members'][$key]['id']         = $sec->id;
                    $list['list_members'][$key]['name']   = $sec->Company->name;
                   
             
        
             
                }

                $atttt = [];
                $atttt['id']     = $feeo->id;
                $atttt['name']   = $feeo->Company->name;
               
                $atttt['change']     = (string) $feeo->oldprice();
                $atttt['counts']     = $feeo->counts();

                $list['changes_members'][]     = $atttt;
         
    
    
            }
           
          
    
        }

        
        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }


    # show statistic detials
    public function detials(Request $request)
    {
        $from  = $request->input("from");
        $to    = $request->input("to");
        $id    = $request->input("id");
        $type    = $request->input("type");

        if($type == 'local'){

            $member = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('id',$id)->first();

            # check member exist
            if(!$member)
            {
                $msg = 'member not found';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);	
            }
        }elseif($type == 'fodder'){

            $member = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('id',$id)->first();

            # check member exist
            if(!$member)
            {
                $msg = 'member not found';
                return response()->json([
                    'message'  => null,
                    'error'    => $msg,
                ],400);	
            }
        }

        $list = [];

        $list['id']   = $member->id;
        if($member->company_id == null){
            $list['name'] = $member->LocalStockproducts->name;
        }else{
            $list['name'] = $member->Company->name;
        }
        $list['counts']   = $member->counts();
        $list['days']     = (string) $member->days();
        $list['week']     = (string) $member->week();
        $list['oldprice'] = (string) $member->oldprice();
    
        
     
        
        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }

    # show statistic detials
    public function detialslocal(Request $request)
    {
        $from  = $request->input("from");
        $to    = $request->input("to");
        $id    = $request->input("id");

        $section = Local_Stock_Sub::where('id',$id)->first();
        $members = Local_Stock_Member::with('MemberCounts','Company','LocalStockproducts')->where('section_id',$id)->get();

        # check member exist
        if(!$section)
        {
            $msg = 'member not found';
            return response()->json([
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $list = [];

        foreach($members as $key => $member){

            $list['members'][$key]['id']   = $member->id;
            if($member->company_id == null){
                $list['members'][$key]['name'] = $member->LocalStockproducts->name;
            }else{
                $list['members'][$key]['name'] = $member->Company->name;
            }
            $list['members'][$key]['counts']   = $member->counts();
            $list['members'][$key]['days']     = (string) $member->days();
            $list['members'][$key]['week']     = (string) $member->week();
            $list['members'][$key]['oldprice'] = (string) $member->oldprice();
        }
       
    
        
     
        
        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }

    # show members
    public function comprisonfodder(Request $request)
    {
        $id   = $request->input("id");

        $section = Stock_Fodder_Sub::with('StockFeeds','FodderStocks.Company')->where('id',$id)->first();

        $sections = Fodder_Stock::with('Section:id,name','Company:id,name')->where('sub_id',$section->id)->get()->unique('company_id');
        $list = [];
        $comps = [];
        $foooo = [];

        if($request->hasHeader('device')){
            $list['stock_name'] = $section->name;
        }

        foreach ($sections as $key => $sec)
        {
            $comps['id']   = $sec->Company->id;
            $comps['name'] = $sec->Company->name;
        
            $list['companies'][] = $comps;
        }

        foreach ($section->StockFeeds as $kkk => $sec)
        {
            $foooo['id']   = $sec->id;
            $foooo['name'] = $sec->name;

            $list['feeds'][] =  $foooo;
        }

        return response()->json(['message'  => null, 'error'    => null, 'data' => $list],200);
    }

     # get companies
     public function Getcompaniesfodder(Request $request)
     {
         
        $comps  = Company::whereIn('id',$request->companies_id)->get();
        $datas  = Fodder_Stock::with('Section','StockFeed','Company','FodderStockMoves')->whereIn('fodder_id',$request->fodder_items_id)->whereIn('company_id',$request->companies_id)->get();
 
        $list = [];
        
        
        foreach ($comps as $key => $sec)
        {
            $list['companies'][$key]['id']   = $sec->id;
            $list['companies'][$key]['name'] = $sec->name;
            $list['companies'][$key]['image']= URL::to('uploads/company/images/'.$sec->image);

            foreach ($datas as $ker => $fed)
            {
                if($fed->company_id == $sec->id){
                    $ffff = [];
                    $ffff['id']   = $fed->StockFeed->id;
                    $ffff['name'] = $fed->StockFeed->name;
                    $ffff['price']= $fed->LastMovement()->price;
                    $ffff['created_at'] = $fed->LastMovement()->created_at;
                    $list['companies'][$key]['feed'][]  = $ffff;
                   
                }
               
               
          
               
            }
            
         
          
        }

        if(count($comps) == 0 ){
            $list['companies'] = [];
        }
      


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }


    /**
     * @param Request $request
     * food_id Optional
     * stock_id Required
     * mini_id Required
     * @return \Illuminate\Http\JsonResponse
     * array of two keys [fooder_list ,fooder_categories]
     *
     */
    public function feeds_items(Request $request)
    {
        $stock_id = $request->query('stock_id');
        $mini_id = $request->query('mini_id');
        $food_id = $request->query('food_id');
        $device = $request->query('device');


        if($device=="web"){
            $fod = Stock_Feeds::where('section_id',$stock_id)->where('fixed','1')->first();
            $companies= Fodder_Stock::where('sub_id',$stock_id)->orderBy('company_id', 'DESC')->get();

            foreach ($companies as $key => $sec) {
                $list['fodder_list'][$key]['id'] = $sec->stockFeed->id;
                $list['fodder_list'][$key]['name'] = $sec->stockFeed->name;
                if($fod->id == $sec->stockFeed->id){
                    $list['fodder_list'][$key]['selected']=1;
                }else{
                    $list['fodder_list'][$key]['selected']=0;
                }
            }
            foreach ($companies as $key => $sec) {
                $list['fodder_list'][$key]['id'] = $sec->stockFeed->id;
                $list['fodder_list'][$key]['name'] = $sec->stockFeed->name;
            }
            $tempArray = array_unique(array_column($list['fodder_list'], "id"));
            $moreUniqueArray = array_values(array_intersect_key($list['fodder_list'], $tempArray));
            return response()->json([
                'message'  => null,
                'error'    => null,
                'data'     => ['fodder_list'=>$moreUniqueArray]
            ]);
        }
        else{
            $mini_subs = Mini_Sub::select('id','name')->where('section_id',$stock_id)->get();

            if(!$mini_id || $mini_id == 0){
                if(count($mini_subs) > 0){
                    $mini_id = $mini_subs[0]['id'];
                }
            }

            $mini_subs->map(function ($mini_sub) use($mini_id){
                $mini_sub['selected'] = $mini_id == $mini_sub->id ? 1 : 0;
                return $mini_sub;
            });



            $stock_feeds = Stock_Feeds::select('id','name')->where('section_id',$stock_id)->where('mini_id',$mini_id)->orderBy('sorter','DESC')->latest()->get();
            $stock_feeds->map(function ($stock_feed) use($food_id){
                $stock_feed['selected'] = $food_id == $stock_feed->id ? 1 : 0;
                return $stock_feed;
            });

            return response()->json([
                'message'  => null,
                'error'    => null,
                'data'     => ['fodder_categories'=>$mini_subs,'fodder_list'=>$stock_feeds]
            ]);
        }


    }

   public function companies_items(Request $request)
   {
       $stock_id = $request->query('stock_id');
       $company_id = $request->query('company_id');

       $companies = Company::select('id','name')->whereHas('FodderStocks',function ($q) use($stock_id){
           $q->where('sub_id',$stock_id);
       })->orderByDesc('sort')->get()->unique('id');

       $companies->map(function($company) use($company_id){
           if($company_id == $company->id){
               $company['selected'] = 1;
           }else{
               $company['selected'] = 0;
           }
           return $company;
       });
       return response()->json([
           'message'  => null,
           'error'    => null,
           'data'     => $companies
       ]);
   }


    public function newshowmembers(Request $request, FodderService $service, LocalService $localService)
    {

        $id = $request->input("id");
        $type = $request->input("type");
        $date = $request->input("date");
        $fod_id = $request->input("fod_id");
        $comp_id = $request->input("comp_id");


        $list = [];

        $list['columns'] = [];

        if($date){
            $date = $request->input("date");
        }else{
            $date = date('Y-m-d');
        }

        if(Auth::guard('customer-api')->user() && Auth::guard('customer-api')->user()->memb != 1){

            if($date && $date < Carbon::now()->subDays(7)) {
                return $this->ErrorMsg('not membership');
            }
        }else{

            if($date && $date < Carbon::now()->subDays(7) && !Auth::guard('customer-api')->user()){
                return $this->ErrorMsg('not membership');
            }
        }


        if($type == 'fodder'){

            $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();

            # check sub exist
            if(!$sub) { return $this->ErrorMsgWithStatus('sub not found');}

            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$id)->where('type','fodderstock')->pluck('ads_id')->toArray();

            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();


            $list['banners'] =  LogoBannerResource::collection($adss);
            $list['logos'] =  LogoBannerResource::collection($logos);

            $list['company_name'] = 'الكل';
            $list['feed_name'] = 'الكل';
            if($comp_id){
                $list['company_name'] = Company::where('id',$comp_id)->first()->name;
            }
            $fod = '';
            if($fod_id){
                $fod = Stock_Feeds::where('id',$fod_id)->where('section_id',$id)->first();
                $list['feed_name'] = $fod->name;
            }else{
                if(!$comp_id){
                    $fod = Stock_Feeds::where('section_id',$id)->where('fixed','1')->first();
                    $list['feed_name'] = $fod->name;
                }
            }

            $members =  $service->FodderTableProccess($ads,$date,$fod);

            while(count($members) == 0){
                $date = date("Y-m-d", strtotime($date ."-1 day"));
                $day = date('l', strtotime($date));

                if($day == 'Friday'){
                    $date = date( 'Y-m-d', strtotime( $date . ' -1 day' ) );
                }
                $members =  $service->FodderTableProccess($ads,$date,$fod);
            }

            $list['members'] = $members;

            $list['section_type']   =  $sub->Section->type;

            $list['columns'][]["title"]   = 'الأسم';
            $list['columns'][]["title"]   = 'الصنف';
            $list['columns'][]["title"]   = 'السعر';
            $list['columns'][]["title"]   = 'مقدار التغير';
            $list['columns'][]["title"]   = 'إتجاه السعر';

        }elseif($type == 'local'){
            $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();

            # check sub exist
            if(!$sub) { return $this->ErrorMsgWithStatus('sub not found');}

            $sub->view_count = $sub->view_count + 1;

            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

            $list['banners'] =  LogoBannerResource::collection($adss);
            $list['logos'] =  LogoBannerResource::collection($logos);


            // columns
            $list['columns'][]["title"]   = "الأسم";
            foreach ($sub->LocalStockColumns as $key => $col)
            {
                if($col->type == 'price' ){
                    $list['columns'][]["title"]   = $col->name;
                }

                if($col->type == 'change' ){
                    $list['columns'][]["title"]   = $col->name;
                }

                if($col->type == null ){
                    $list['columns'][]["title"]   = $col->name;
                }
            }
            $list['columns'][]["title"]   = 'إتجاه السعر';

            $members = $localService->TableLikeweb($request,$ads,$date);

            while(count($members) == 0){
                $date = date("Y-m-d", strtotime($date ."-1 day"));
                $day = date('l', strtotime($date));

                if($day == 'Friday'){
                    $date = date( 'Y-m-d', strtotime( $date . ' -1 day' ) );
                }
                $members = $localService->TableLikeweb($request,$ads,$date);
            }
            $list['members']               = $members;
        }

        return $this->ReturnData($list);
    }




    public function showmembersInIOS(Request $request)
    {

        $id = $request->input("id");
        $type = $request->input("type");
        $date = $request->input("date");
        $fod_id = $request->input("fod_id");
        $comp_id = $request->input("comp_id");



        $list = [];
        $ro = [];
        $ros = [];
        $losss = [];
        $loss = [];
        $list['columns'] = [];

        $nowm =date('Y-m-d'); //Carbon::today();
        if($date){
            $date = $request->input("date");
        }else{
            $date = date('Y-m-d');
        }
        if(is_null($date)){

            if($type == 'local'){

                $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();

                $sub->view_count = $sub->view_count + 1;
                # check sub exist
                if(!$sub)
                {
                    $msg = 'sub not found';
                    return response()->json([
                        'status'   => '0',
                        'message'  => null,
                        'error'    => $msg,
                    ],400);
                }

                $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

                $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


                $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

                $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->get();


                $list['banners'] = count($adss) == 0 ? [] : LogoBannerResource::collection($adss);
                $list['logos'] = count($logos) == 0 ? [] : LogoBannerResource::collection($logos);

                // columns
                $list['columns'][]["title"]   = "الاسم";
                foreach ($sub->LocalStockColumns as $key => $col)
                {
                    if($col->type == 'price' ){
                        $list['columns'][]["title"]   = $col->name;
                    }

                    if($col->type == 'change' ){
                        $list['columns'][]["title"]   = $col->name;
                    }

                    if($col->type == null ){
                        $list['columns'][]["title"]   = $col->name;
                    }
                }
                $list['columns'][]["title"]   = 'اتجاه السعر';

                // memberssort
                foreach ($memberssort as $kn => $member)
                {
                    // if company
                    if($member->Company != null){
                        $loss[$kn]['name']               = $member->Company->name;
                        $loss[$kn]['mem_id']               = $member->Company->id;
                        $loss[$kn]['kind']               = 'company';
                    }
                    // if product
                    if($member->LocalStockproducts != null){
                        $loss[$kn]['name']              = $member->LocalStockproducts->name;
                        $loss[$kn]['mem_id']              = $member->LocalStockproducts->id;
                        $loss[$kn]['kind']               = 'product';
                    }
                    // last movement
                    foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
                    {
                        // price
                        if($value->LocalStockColumns->type == 'price' ){
                            $loss[$kn]["price"]         = (string) $value->value;
                        }
                        // change
                        if($value->LocalStockColumns->type == 'change' ){
                            $loss[$kn]["change"]         = (string) round($value->value, 2);
                            $loss[$kn]["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                        }
                        // if  null
                        // if  null
                        if($value->LocalStockColumns->type == null ){
                            $loss[$kn]["new_columns"][]                 = $value->value;
                        }

                        // state
                        if($value->LocalStockColumns->type == 'state' ){
                            if($value->value === 'up' ){
                                $loss[$kn]["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                            }
                            if($value->value === 'down' ){
                                $loss[$kn]["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                            }
                            if($value->value === 'equal' ){
                                $loss[$kn]["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                            }
                        }

                    }

                    $loss[$kn]["type"]                 = 1;
                }

                // members
                foreach ($members as $kk => $member)
                {
                    // if company
                    if($member->Company != null){
                        $loss[$kk + count($memberssort)]['name']               = $member->Company->name;
                        $loss[$kk + count($memberssort)]['mem_id']               = $member->Company->id;
                        $loss[$kk + count($memberssort)]['kind']               = 'company';
                    }
                    // if product
                    if($member->LocalStockproducts != null){
                        $loss[$kk + count($memberssort)]['name']              = $member->LocalStockproducts->name;

                        $loss[$kk + count($memberssort)]['mem_id']              = $member->LocalStockproducts->id;
                        $loss[$kk + count($memberssort)]['kind']               = 'product';
                    }

                    // last movement
                    foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
                    {
                        // price
                        if($value->LocalStockColumns->type == 'price' ){
                            $loss[$kk + count($memberssort)]["price"]         = (string) $value->value;
                        }
                        // change
                        if($value->LocalStockColumns->type == 'change' ){
                            // $loss[$kk + count($memberssort)]["change"]         = round($value->value, 2) . Date::parse($value->created_at)->format('H:i / Y-m-d');
                            $loss[$kk + count($memberssort)]["change"]         =(string) round($value->value, 2);
                            $loss[$kk + count($memberssort)]["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                        }
                        // if  null
                        // if  null
                        if($value->LocalStockColumns->type == null ){
                            $loss[$kk + count($memberssort)]["new_columns"][]                 = $value->value;

                        }

                        // state
                        if($value->LocalStockColumns->type == 'state' ){
                            if($value->value === 'up' ){
                                $loss[$kk + count($memberssort)]["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                            }
                            if($value->value === 'down' ){
                                $loss[$kk + count($memberssort)]["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                            }
                            if($value->value === 'equal' ){
                                $loss[$kk + count($memberssort)]["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                            }
                        }
                    }
                    $loss[$kk + count($memberssort)]["type"]                 = 0;
                }

                $list['members']                 = $loss;

                if(count($members) == 0 && count($memberssort) == 0){
                    $list['members'] = [];
                }

            }elseif($type == 'fodder'){

                $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();

                # check sub exist
                if(!$sub)
                {
                    return $this->ErrorMsgWithStatus('sub not found');
                }

                $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

                $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();

                $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
                $selected_feeds = Stock_Feeds::where('section_id',$sub->id)->where('id',$fod_id)->first();


                $foooooo = [];
                // $fooooo = [];
                # feeds
                if($selected_feeds)
                {
                    $foooooo['name']     = $selected_feeds->name;
                    $foooooo['id']       = $selected_feeds->id;
                    $list['feeds'][]     = $foooooo;
                }
                // feeds
                foreach ($feeds as $kf => $fed)
                {
                    $foooooo['name']               = $fed->name;
                    $foooooo['id']              = $fed->id;
                    $list['feeds'][] =$foooooo;
                }

                $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');
                $selected_company = Company::select('name','id')->where('id',$comp_id)->first();

                $cooooo = [];
                if($selected_company)
                {
                    $cooooo['name']    = $selected_company->name;
                    $cooooo['id']      = $selected_company->id;
                    $list['companies'][] =$cooooo;
                }
                // feeds
                foreach ($fodss as $kcf => $fecd)
                {
                    if($comp_id != $fecd->Company->id)
                    {
                        $cooooo['name']           = $fecd->Company->name;
                        $cooooo['id']              = $fecd->Company->id;
                        $list['companies'][] =$cooooo;
                    }
                }

                if(is_null($fod_id)){
                    $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
                }else{
                    $fod = Stock_Feeds::where('id',$fod_id)->where('section_id',$sub->id)->first();
                }

                $ads[] = $comp_id;

                if(is_null($comp_id))
                {
                    $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
                    if($fod_id){
                        $memberssort->where('fodder_id',$fod_id);
                    }
                    $memberssort = $memberssort->latest()->get()->unique('stock_id');
                    $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->latest()->get()->unique('stock_id');
                }else{
                    $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
                    if($fod_id){
                        $memberssort->where('fodder_id',$fod_id);
                    }
                    $memberssort = $memberssort->latest()->get()->unique('stock_id');
                    $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('company_id',$comp_id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->latest()->get()->unique('stock_id');
                }



                if(count($adss) == 0){
                    $list['banners'] = [];
                }else{
                    foreach ($adss as $key => $ad)
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


                $list['section_type']   =  $sub->Section->type;


                $list['columns'][]["title"]   = ' الاسم';
                $list['columns'][]["title"]   = 'الصنف';
                $list['columns'][]["title"]   = 'السعر';
                $list['columns'][]["title"]   = 'مقدار التغير';
                $list['columns'][]["title"]   = 'اتجاه السعر';

                // members
                foreach ($memberssort as $kfds => $member)
                {

                    $ros['name']               = $member->Company->name;
                    $ros['mem_id']               = $member->Company->id;

                    $ros['feed']              = $member->StockFeed->name;

                    // last movement


                    $ros["price"]         = (string) $member->price;

                    $ros["change"]         = (string) round($member->change, 2);
                    $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                    if($member->status === 'up' ){
                        $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($member->status === 'down' ){
                        $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($member->status === 'equal' ){
                        $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');

                    }
                    $ros['type']              = 1;
                    $list['members'][]                = $ros;

                }
                // return $ros;
                // members
                foreach ($members as $kfd => $mem)
                {

                    $ro['name']               = $mem->Company->name;
                    $ro['mem_id']               = $mem->Company->id;
                    $ro['feed']              = $mem->StockFeed->name;

                    // last movement


                    $ro["price"]         = (string) $mem->price;

                    $ro["change"]         = (string) round($mem->change, 2);
                    $ro["change_date"]         = Date::parse($mem->created_at)->format('H:i / Y-m-d');


                    if($mem->status === 'up' ){
                        $ro["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($mem->status === 'down' ){
                        $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($mem->status === 'equal' ){
                        $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                    }
                    $ro['type']              = 0;
                    $list['members'][]                = $ro;
                }
                // return $ro;
                if(count($members) == 0 && count($memberssort) == 0){
                    $list['members'] = [];
                }
            }


        }else{

            # check recomndation system
            if(!is_null($request->header('Authorization')))
            {

                $token = $request->header('Authorization');
                $token = explode(' ',$token);
                if(count( $token) == 2)
                {


                    // return 'not null';
                    $customer = Customer::where('api_token',$token[1])->first();

                    if(!$customer)
                    {
                        $msg = 'unauthorization';
                        return response()->json([
                            'message'  => null,
                            'error'    => $msg,
                        ],401);
                    }

                    if($customer->memb == '1')
                    {
                        # start
                        if($type == 'local'){

                            $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();




                            # check sub exist
                            if(!$sub)
                            {
                                $msg = 'sub not found';
                                return response()->json([
                                    'status'   => '0',
                                    'message'  => null,
                                    'error'    => $msg,
                                ],400);
                            }

                            $sub->view_count = $sub->view_count + 1;

                            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

                            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                            $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


                            $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

                            $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->where('status',1)->pluck('id')->toArray();

                            $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members)->whereDate('created_at',$date)->latest()->get()->unique('member_id');

                            if(count($adss) == 0){
                                $list['banners'] = [];
                            }else{
                                foreach ($adss as $key => $ad)
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



                            // columns
                            $list['columns'][]["title"]   = "الاسم";
                            foreach ($sub->LocalStockColumns as $key => $col)
                            {
                                if($col->type == 'price' ){
                                    $list['columns'][]["title"]   = $col->name;
                                }

                                if($col->type == 'change' ){
                                    $list['columns'][]["title"]   = $col->name;
                                }

                                if($col->type == null ){
                                    $list['columns'][]["title"]   = $col->name;
                                }



                            }
                            $list['columns'][]["title"]   = 'اتجاه السعر';

                            // memberssort
                            $push = [];
                            foreach ($memberssort as $kno => $member)
                            {
                                // if company
                                if($member->Company != null){
                                    $loss['name']               = $member->Company->name;
                                    $loss['mem_id']               = $member->Company->id;
                                    $loss['kind']               = 'company';

                                }
                                // if product
                                if($member->LocalStockproducts != null){
                                    $loss['name']              = $member->LocalStockproducts->name;
                                    $loss['mem_id']               = $member->LocalStockproducts->id;
                                    $loss['kind']               = 'product';
                                }

                                // last movement
                                foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
                                {
                                    // price
                                    if($value->LocalStockColumns->type == 'price' ){
                                        $loss["price"]         = (string) $value->value;
                                    }
                                    // change
                                    if($value->LocalStockColumns->type == 'change' ){
                                        $loss["change"]         = (string) round($value->value, 2);
                                        $loss["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                                    }
                                    // if  null
                                    if($value->LocalStockColumns->type == null ){
                                        $loss["new_columns"][]                 = $value->value;

                                    }

                                    // state
                                    if($value->LocalStockColumns->type == 'state' ){
                                        if($value->value === 'up' ){
                                            $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                        }
                                        if($value->value === 'down' ){
                                            $loss["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                        }
                                        if($value->value === 'equal' ){
                                            $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                        }
                                    }


                                }

                                $loss["type"]                 = 1;
                                $push[] = $loss;


                            }



                            // members
                            foreach ($moves as $kko => $member)
                            {
                                // if company
                                if($member->LocalStockMember->Company != null){
                                    $loss['name']               = $member->LocalStockMember->Company->name;
                                    $loss['mem_id']               = $member->LocalStockMember->Company->id;
                                    $loss['kind']               = 'company';
                                }
                                // if product
                                if($member->LocalStockMember->LocalStockproducts != null){
                                    $loss['name']              = $member->LocalStockMember->LocalStockproducts->name;
                                    $loss['mem_id']               = $member->LocalStockMember->LocalStockproducts->id;
                                    $loss['kind']               = 'product';
                                }

                                // last movement
                                foreach ($member->LocalStockDetials as $koo => $value)
                                {
                                    // price
                                    if($value->LocalStockColumns->type == 'price' ){
                                        $loss["price"]         = (string) $value->value;
                                    }
                                    // change
                                    if($value->LocalStockColumns->type == 'change' ){
                                        // $loss["change"]         = round($value->value, 2)  . "  /". Date::parse($value->created_at)->format('H:i / Y-m-d');
                                        $loss["change"]         = (string) round($value->value, 2);
                                        $loss["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');
                                    }
                                    // if  null
                                    if($value->LocalStockColumns->type == null ){
                                        $loss["new_columns"]                 = [$value->value];

                                    }

                                    // state
                                    if($value->LocalStockColumns->type == 'state' ){
                                        if($value->value === 'up' ){
                                            $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                        }
                                        if($value->value === 'down' ){
                                            $loss["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                        }
                                        if($value->value === 'equal' ){
                                            $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                        }
                                    }



                                }

                                $loss["type"]                 = 0;


                                $push[] = $loss;
                            }

                            $list['members']               = $push;
                            if(count($moves) == 0 && count($memberssort) == 0){
                                $list['members'] = [];
                            }

                        }elseif($type == 'fodder'){

                            $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();


                            # check sub exist
                            if(!$sub)
                            {
                                return $this->ErrorMsgWithStatus('sub not found');
                            }

                            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

                            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                            $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();



                            $feeds = Stock_Feeds::select('id','name')->where('section_id',$sub->id)->orderBy('fixed' , 'desc');
                            if($fod_id){
                                $feeds->where('id','!=',$fod_id);
                            }
                            $feeds = $feeds->get();


                            foreach ($feeds as $kf => $fed)
                            {
                                $list['feeds'][] = [
                                    'name'=>$fed->name,
                                    'id'=>$fed->name,
                                ];
                            }

                            if($fod_id){
                                $selected_feeds = Stock_Feeds::select('id','name')->where('section_id',$sub->id)->where('id',$fod_id)->first();
                                array_unshift($list['feeds'],$selected_feeds);
                            }

                            $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');
                            $selected_company = Company::where('id',$comp_id)->first();

                            $cooooo = [];
                            if($selected_company)
                            {
                                $cooooo['name']    = $selected_company->name;
                                $cooooo['id']      = $selected_company->id;
                                $list['companies'][] =$cooooo;
                            }
                            // feeds
                            foreach ($fodss as $kcf => $fecd)
                            {

                                $cooooo['name']               = $fecd->Company->name;


                                $cooooo['id']              = $fecd->Company->id;
                                $list['companies'][] =$cooooo;

                            }

                            if(is_null($fod_id)){
                                $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
                            }else{
                                $fod = Stock_Feeds::where('id',$fod_id)->first();
                            }

//                                $ads[] = $comp_id;
                            if(is_null($comp_id)){
                                $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->whereDate('created_at',$date)->with('Section','StockFeed','Company','FodderStock');
                                if($fod_id){
                                    $memberssort->where('fodder_id',$fod_id);
                                }

                                $memberssort = $memberssort->latest()->get()->unique('stock_id');

                                $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');


                            }else{
                                $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->whereDate('created_at',$date)->with('Section','StockFeed','Company','FodderStock');

                                $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('company_id',$comp_id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date);

                                if($fod_id){
                                    $memberssort->where('fodder_id',$fod_id);
                                    $members->where('fodder_id',$fod_id);
                                }
                                $memberssort = $memberssort->latest()->get()->unique('stock_id');

                                $members = $members->latest()->get()->unique('stock_id');
                            }

                            $ms = [];
                            foreach ($memberssort as $k => $v){
                                $ms[] = $v->id;
                            }
                            if(count($adss) == 0){
                                $list['banners'] = [];
                            }else{
                                foreach ($adss as $key => $ad)
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


                            $list['section_type']   =  $sub->Section->type;

                            $list['columns'][]["title"]   = ' الاسم';
                            $list['columns'][]["title"]   = 'الصنف';
                            $list['columns'][]["title"]   = 'السعر';
                            $list['columns'][]["title"]   = 'مقدار التغير';
                            $list['columns'][]["title"]   = 'اتجاه السعر';

                            // members
                            foreach ($memberssort as $kfds => $member)
                            {

                                $ros['name']               = $member->Company->name;
                                $ros['mem_id']               = $member->Company->id;
                                $ros['feed']              = $member->StockFeed->name;

                                // last movement

                                $ros["price"]         = (string)$member->price;
                                $ros["change"]         = (string) round($member->change, 2);
                                $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');


                                if($member->status === 'up' ){
                                    $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                }
                                if($member->status === 'down' ){
                                    $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                }
                                if($member->status === 'equal' ){
                                    $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                }
                                $ros['type']              = 1;
                                $list['members'][]                = $ros;
                            }

                            // members
                            foreach ($members as $kfd => $member)
                            {
                                if(!in_array($member->id ,$ms)) {
                                    $ro['name'] = $member->Company->name;
                                    $ro['mem_id'] = $member->Company->id;
                                    $ro['feed'] = $member->StockFeed->name;

                                    // last movement
                                    $ro["price"] = (string) $member->price;
                                    $ro["change"] = (string)round($member->change, 2);
                                    $ro["change_date"] = Date::parse($member->created_at)->format('H:i / Y-m-d');


                                    if ($member->status === 'up') {
                                        $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                    }
                                    if ($member->status === 'down') {
                                        $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                    }
                                    if ($member->status === 'equal') {
                                        $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                    }
                                    $ro['type'] = 0;
                                    $list['members'][] = $ro;
                                }
                            }

                            if(count($members) == 0 && count($memberssort) == 0){
                                $list['members'] = [];
                            }
                        }

                    }else{
                        // return 1;
                        if($date < Carbon::now()->subDays(7)){
                            $msg = ' not membership';
                            return response()->json([
                                'message'  => null,
                                'error'    => $msg,
                            ],400);
                        }else{
                            # start

                            if($type == 'local'){

                                $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();

                                $sub->view_count = $sub->view_count + 1;
                                # check sub exist
                                if(!$sub)
                                {
                                    $msg = 'sub not found';
                                    return response()->json([
                                        'status'   => '0',
                                        'message'  => null,
                                        'error'    => $msg,
                                    ],400);
                                }

                                $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

                                $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                                $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                                $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                                $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


                                $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

                                $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->where('status',1)->pluck('id')->toArray();

                                $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members)->whereDate('created_at',$date)->latest()->get()->unique('member_id');

                                if(count($adss) == 0){
                                    $list['banners'] = [];
                                }else{
                                    foreach ($adss as $key => $ad)
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



                                // columns
                                $list['columns'][]["title"]   = "الاسم";
                                foreach ($sub->LocalStockColumns as $key => $col)
                                {
                                    if($col->type == 'price' ){
                                        $list['columns'][]["title"]   = $col->name;
                                    }

                                    if($col->type == 'change' ){
                                        $list['columns'][]["title"]   = $col->name;
                                    }

                                    if($col->type == null ){
                                        $list['columns'][]["title"]   = $col->name;
                                    }
                                }
                                $list['columns'][]["title"]   = 'اتجاه السعر';

                                // memberssort
                                // memberssort
                                foreach ($memberssort as $knt => $member)
                                {
                                    // if company
                                    if($member->Company != null){
                                        $loss[$knt]['name']               = $member->Company->name;
                                        $loss[$knt]['mem_id']               = $member->Company->id;
                                        $loss[$knt]['kind']               = 'company';
                                    }
                                    // if product
                                    if($member->LocalStockproducts != null){
                                        $loss[$knt]['name']              = $member->LocalStockproducts->name;
                                        $loss[$knt]['mem_id']               = $member->LocalStockproducts->id;
                                        $loss[$knt]['kind']               = 'product';
                                    }

                                    // last movement
                                    foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
                                    {
                                        // price
                                        if($value->LocalStockColumns->type == 'price' ){
                                            $loss[$knt]["price"]         = (string) $value->value;
                                        }
                                        // change
                                        if($value->LocalStockColumns->type == 'change' ){
                                            //  $loss[$knt]["change"]         = round($value->value, 2)  . "  /".  Date::parse($value->created_at)->format('H:i / Y-m-d');
                                            $loss[$knt]["change"]         = (string) round($value->value, 2);
                                            $loss[$knt]["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');
                                        }
                                        // if  null
                                        if($value->LocalStockColumns->type == null ){
                                            $loss[$knt]["new_columns"][]                 = $value->value;

                                        }

                                        // state
                                        if($value->LocalStockColumns->type == 'state' ){
                                            if($value->value === 'up' ){
                                                $loss[$knt]["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                            }
                                            if($value->value === 'down' ){
                                                $loss[$knt]["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                            }
                                            if($value->value === 'equal' ){
                                                $loss[$knt]["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                            }
                                        }
                                    }
                                    $loss[$knt]["type"]                 = 1;
                                }


                                $push = [];
                                // members
                                foreach ($moves as $kkt => $member)
                                {
                                    // if company
                                    if($member->LocalStockMember->Company != null){
                                        $loss['name']               = $member->LocalStockMember->Company->name;
                                        $loss['mem_id']               = $member->LocalStockMember->Company->id;
                                        $loss['kind']               = 'company';
                                    }
                                    // if product
                                    if($member->LocalStockMember->LocalStockproducts != null){
                                        $loss['name']              = $member->LocalStockMember->LocalStockproducts->name;
                                        $loss['mem_id']               = $member->LocalStockMember->LocalStockproducts->id;
                                        $loss['kind']               = 'product';
                                    }

                                    // last movement
                                    foreach ($member->LocalStockDetials as $k => $value)
                                    {
                                        // price
                                        if($value->LocalStockColumns->type == 'price' ){
                                            $loss["price"]         = (string) $value->value;
                                        }
                                        // change
                                        if($value->LocalStockColumns->type == 'change' ){
                                            $loss["change"]         = (string) round($value->value, 2);

                                            $loss["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                                        }
                                        // if  null
                                        if($value->LocalStockColumns->type == null ){
                                            $loss["new_columns"]             = [$value->value];

                                        }

                                        // state
                                        if($value->LocalStockColumns->type == 'state' ){
                                            if($value->value === 'up' ){
                                                $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                            }
                                            if($value->value === 'down' ){
                                                $loss["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                            }
                                            if($value->value === 'equal' ){
                                                $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                            }
                                        }
                                    }
                                    $loss["type"]                 = 0;

                                    $push[] =  $loss;

                                }

                                $list['members']                = $push;


                                if(count($moves) == 0 && count($memberssort) == 0){
                                    $list['members'] = [];
                                }

                            }elseif($type == 'fodder'){

                                $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();


                                # check sub exist
                                if(!$sub)
                                {
                                    $msg = 'sub not found';
                                    return response()->json([
                                        'status'   => '0',
                                        'message'  => null,
                                        'error'    => $msg,
                                    ],400);
                                }

                                $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

                                $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                                $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                                $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                                $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();



                                $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
                                $selected_feeds = Stock_Feeds::where('section_id',$sub->id)->where('id',$fod_id)->first();



                                $foooooo = [];
                                // $fooooo = [];
                                # feeds
                                if($selected_feeds)
                                {
                                    $foooooo['name']     = $selected_feeds->name;
                                    $foooooo['id']       = $selected_feeds->id;
                                    $list['feeds'][]     = $foooooo;
                                }
                                // feeds
                                foreach ($feeds as $kf => $fed)
                                {

                                    $foooooo['name']               = $fed->name;

                                    $foooooo['id']              = $fed->id;
                                    $list['feeds'][] =$foooooo;

                                }

                                $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');

                                $selected_company = Company::where('id',$comp_id)->first();

                                $cooooo = [];
                                if($selected_company)
                                {
                                    $cooooo['name']    = $selected_company->name;
                                    $cooooo['id']      = $selected_company->id;
                                    $list['companies'][] =$cooooo;
                                }
                                // feeds
                                foreach ($fodss as $kcf => $fecd)
                                {

                                    $cooooo['name']               = $fecd->Company->name;
                                    $cooooo['id']              = $fecd->Company->id;
                                    $list['companies'][] =$cooooo;

                                }

                                if(is_null($fod_id)){
                                    $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
                                }else{
                                    $fod = Stock_Feeds::where('id',$fod_id)->first();
                                }

                                if(is_null($comp_id)){
                                    $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->whereDate('created_at',$date)->with('Section','StockFeed','Company','FodderStock');
                                    if($fod_id){
                                        $memberssort->where('fodder_id',$fod_id);
                                    }
                                    $memberssort = $memberssort->latest()->get()->unique('stock_id');

                                    $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');


                                }else{
                                    $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
                                    if($fod_id){
                                        $memberssort->where('fodder_id',$fod_id);
                                    }
                                    $memberssort = $memberssort->latest()->get()->unique('stock_id');

                                    $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->where('company_id',$comp_id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');


                                }


                                if(count($adss) == 0){
                                    $list['banners'] = [];
                                }else{
                                    foreach ($adss as $key => $ad)
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


                                $list['section_type']   =  $sub->Section->type;


                                $list['columns'][]["title"]   = ' الاسم';
                                $list['columns'][]["title"]   = 'الصنف';
                                $list['columns'][]["title"]   = 'السعر';
                                $list['columns'][]["title"]   = 'مقدار التغير';
                                $list['columns'][]["title"]   = 'اتجاه السعر';

                                // members
                                foreach ($memberssort as $kfds => $member)
                                {

                                    $ros['name']               = $member->Company->name;
                                    $ros['mem_id']               = $member->Company->id;

                                    $ros['feed']              = $member->StockFeed->name;

                                    // last movement


                                    $ros["price"]         = (string) $member->price;

                                    $ros["change"]         = (string) round($member->change, 2);
                                    $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                                    if($member->status === 'up' ){
                                        $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                    }
                                    if($member->status === 'down' ){
                                        $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                    }
                                    if($member->status === 'equal' ){
                                        $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                    }

                                    $ros['type']              = 1;

                                    $list['members'][]                = $ros;
                                }

                                // members
                                foreach ($members as $kfd => $member)
                                {

                                    $ro['name']               = $member->Company->name;
                                    $ro['mem_id']               = $member->Company->id;

                                    $ro['feed']              = $member->StockFeed->name;

                                    // last movement


                                    $ro["price"]         = (string) $member->price;

                                    $ro["change"]         = (string) round($member->change, 2);
                                    $ro["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                                    if($member->status === 'up' ){
                                        $ro["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                    }
                                    if($member->status === 'down' ){
                                        $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                    }
                                    if($member->status === 'equal' ){
                                        $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                    }

                                    $ro['type']              = 0;
                                    $list['members'][]                = $ro;
                                }

                                if(count($members) == 0 && count($memberssort) == 0){
                                    $list['members'] = [];
                                }
                            }
                        }


                    }

                }else{/////////

                    if($date < Carbon::now()->subDays(7)){

                        $msg = ' not membership';
                        return response()->json([
                            'message'  => null,
                            'error'    => $msg,
                        ],400);
                    }else{

                        # start

                        if($type == 'local'){
                            // return 0;
                            $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();



                            $sub->view_count = $sub->view_count + 1;
                            # check sub exist
                            if(!$sub)
                            {
                                $msg = 'sub not found';
                                return response()->json([
                                    'status'   => '0',
                                    'message'  => null,
                                    'error'    => $msg,
                                ],400);
                            }

                            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

                            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                            $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


                            $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->whereDate('created_at',$date)->get();

                            $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->where('status',1)->pluck('id')->toArray();

                            $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members)->whereDate('created_at',$date)->latest()->get()->unique('member_id');

                            if(count($adss) == 0){
                                $list['banners'] = [];
                            }else{
                                foreach ($adss as $key => $ad)
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



                            // columns
                            $list['columns'][]["title"]   = "الاسم";
                            foreach ($sub->LocalStockColumns as $key => $col)
                            {
                                if($col->type == 'price' ){
                                    $list['columns'][]["title"]   = $col->name;
                                }

                                if($col->type == 'change' ){
                                    $list['columns'][]["title"]   = $col->name;
                                }

                                if($col->type == null ){
                                    $list['columns'][]["title"]   = $col->name;
                                }



                            }
                            $list['columns'][]["title"]   = 'اتجاه السعر';

                            // memberssort
                            $push = [];
                            foreach ($memberssort as $knr => $member)
                            {

                                // if company
                                if($member->Company != null){
                                    $loss['name']               = $member->Company->name;
                                    $loss['mem_id']               = $member->Company->id;
                                    $loss['kind']               = 'company';
                                }
                                // if product
                                if($member->LocalStockproducts != null){
                                    $loss['name']              = $member->LocalStockproducts->name;
                                    $loss['mem_id']               = $member->LocalStockproducts->id;
                                    $loss['kind']               = 'product';
                                }

                                // last movement
                                foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
                                {
                                    // price
                                    if($value->LocalStockColumns->type == 'price' ){
                                        $loss["price"]         = (string) $value->value;
                                    }
                                    // change
                                    if($value->LocalStockColumns->type == 'change' ){
                                        $loss["change"]         = (string) round($value->value, 2);
                                        $loss["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                                    }
                                    // if  null
                                    if($value->LocalStockColumns->type == null ){
                                        $loss["new_columns"][]                 = $value->value;

                                    }

                                    // state
                                    if($value->LocalStockColumns->type == 'state' ){
                                        if($value->value === 'up' ){
                                            $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                        }
                                        if($value->value === 'down' ){
                                            $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                        }
                                        if($value->value === 'equal' ){
                                            $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                        }
                                    }



                                }

                                $loss["type"]                 = 1;
                                $push[] = $loss;
                            }



                            // members

                            foreach ($moves as $kkr => $member)
                            {
                                // if company
                                if($member->LocalStockMember->Company != null){
                                    $loss['name']               = $member->LocalStockMember->Company->name;
                                    $loss['mem_id']               = $member->LocalStockMember->Company->id;
                                    $loss['kind']               = 'company';
                                }
                                // if product
                                if($member->LocalStockMember->LocalStockproducts != null){
                                    $loss['name']              = $member->LocalStockMember->LocalStockproducts->name;
                                    $loss['mem_id']               = $member->LocalStockMember->LocalStockproducts->id;
                                    $loss['kind']               = 'product';
                                }

                                // last movement
                                foreach ($member->LocalStockDetials as $k => $value)
                                {
                                    // price
                                    if($value->LocalStockColumns->type == 'price' ){
                                        $loss["price"]         = (string) $value->value;
                                    }
                                    // change
                                    if($value->LocalStockColumns->type == 'change' ){

                                        // $loss["change"]         = round($value->value, 2)  . "  /". Date::parse($value->created_at)->format('H:i / Y-m-d');
                                        $loss["change"]         = (string) round($value->value, 2);
                                        $loss["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                                    }
                                    // if  null
                                    if($value->LocalStockColumns->type == null ){////////////////////////
                                        $loss["new_columns"]              = [$value->value];

                                    }



                                    // state
                                    if($value->LocalStockColumns->type == 'state' ){
                                        if($value->value === 'up' ){
                                            $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                        }
                                        if($value->value === 'down' ){
                                            $loss["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                        }
                                        if($value->value === 'equal' ){
                                            $loss["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                        }
                                    }

                                }
                                $loss["type"]                 = 0;
                                $push[] = $loss;


                            }
                            $list['members']              = $push;
                            // return  $push;

                            if(count($moves) == 0 && count($memberssort) == 0){
                                $list['members'] = [];
                            }


                        }elseif($type == 'fodder'){

                            $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();


                            # check sub exist
                            if(!$sub)
                            {
                                return $this->ErrorMsgWithStatus('sub not found');
                            }

                            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

                            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                            $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();



                            $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
                            $foooooo = [];
                            // feeds
                            foreach ($feeds as $kf => $fed)
                            {

                                $foooooo['name']               = $fed->name;

                                $foooooo['id']              = $fed->id;
                                $list['feeds'][] =$foooooo;

                            }

                            $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');

                            $cooooo = [];
                            // feeds
                            foreach ($fodss as $kcf => $fecd)
                            {

                                $cooooo['name']               = $fecd->Company->name;


                                $cooooo['id']              = $fecd->Company->id;
                                $list['companies'][] =$cooooo;

                            }

                            if(is_null($fod_id)){
                                $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
                            }else{
                                $fod = Stock_Feeds::where('id',$fod_id)->first();
                            }
//                            $ads[] = $comp_id;
                            if(is_null($comp_id)){
                                $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date);
                                if($fod_id){
                                    $memberssort->where('fodder_id',$fod_id);
                                }
                                $memberssort  =  $memberssort->latest()->get()->unique('stock_id');

                                $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');


                            }else{
                                $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date);
                                if($fod_id){
                                    $memberssort->where('fodder_id',$fod_id);
                                }
                                $memberssort  =  $memberssort->latest()->get()->unique('stock_id');

                                $members = Fodder_Stock_Move::with('Section','StockFeed','Company','FodderStock')->where('sub_id',$sub->id)->where('company_id',$comp_id);
                                if(!is_null($fod_id)){
                                    $members->where('fodder_id',$fod->id);
                                }
                                $members = $members->whereDate('created_at',$date)->latest()->get()->unique('stock_id');
                            }

                            $ms = [];
                            foreach ($memberssort as $k => $v){
                                $ms[] = $v->id;
                            }

                            if(count($adss) == 0){
                                $list['banners'] = [];
                            }else{
                                foreach ($adss as $key => $ad)
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


                            $list['section_type']   =  $sub->Section->type;


                            $list['columns'][]["title"]   = ' الاسم';
                            $list['columns'][]["title"]   = 'الصنف';
                            $list['columns'][]["title"]   = 'السعر';
                            $list['columns'][]["title"]   = 'مقدار التغير';
                            $list['columns'][]["title"]   = 'اتجاه السعر';

                            // members
                            foreach ($memberssort as $kfds => $member)
                            {

                                $ros['name']               = $member->Company->name;
                                $ros['mem_id']               = $member->Company->id;

                                $ros['feed']              = $member->StockFeed->name;

                                // last movement


                                $ros["price"]         = (string) $member->price;

                                $ros["change"]         = (string) round($member->change, 2);
                                $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                                if($member->status === 'up' ){
                                    $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                }
                                if($member->status === 'down' ){
                                    $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                }
                                if($member->status === 'equal' ){
                                    $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                }

                                $ros['type']              = 1;
                                $list['members'][]                = $ros;
                            }

                            // members
                            foreach ($members as $kfd => $member)
                            {
                                if(!in_array($member->id, $ms)) {
                                    $ro['name'] = $member->Company->name;
                                    $ro['mem_id'] = $member->Company->id;

                                    $ro['feed'] = $member->StockFeed->name;

                                    // last movement


                                    $ro["price"] = (string) $member->price;

                                    $ro["change"] = (string)round($member->change, 2);
                                    $ro["change_date"] = Date::parse($member->created_at)->format('H:i / Y-m-d');

                                    if ($member->status === 'up') {
                                        $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                    }
                                    if ($member->status === 'down') {
                                        $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                    }
                                    if ($member->status === 'equal') {
                                        $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                    }

                                    $ro['type'] = 0;
                                    $list['members'][] = $ro;
                                }
                            }
                            if(count($members) == 0 && count($memberssort) == 0){
                                $list['members'] = [];
                            }
                        }
                    }

                }
            }else{
                if($date < Carbon::now()->subDays(7)){
                    $msg = ' not membership';
                    return response()->json([
                        'message'  => null,
                        'error'    => $msg,
                    ],400);
                }else{
                    # start

                    if($type == 'local'){

                        $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();



                        $sub->view_count = $sub->view_count + 1;
                        # check sub exist
                        if(!$sub)
                        {
                            $msg = 'sub not found';
                            return response()->json([
                                'status'   => '0',
                                'message'  => null,
                                'error'    => $msg,
                            ],400);
                        }

                        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

                        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


                        $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

                        $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->where('status',1)->pluck('id')->toArray();

                        $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members)->whereDate('created_at',$date)->latest()->get()->unique('member_id');

                        if(count($adss) == 0){
                            $list['banners'] = [];
                        }else{
                            foreach ($adss as $key => $ad)
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



                        // columns
                        $list['columns'][]["title"]   = "الاسم";
                        foreach ($sub->LocalStockColumns as $key => $col)
                        {
                            if($col->type == 'price' ){
                                $list['columns'][]["title"]   = $col->name;
                            }

                            if($col->type == 'change' ){
                                $list['columns'][]["title"]   = $col->name;
                            }

                            if($col->type == null ){
                                $list['columns'][]["title"]   = $col->name;
                            }



                        }
                        $list['columns'][]["title"]   = 'اتجاه السعر';

                        // memberssort
                        foreach ($memberssort as $knf => $member)
                        {
                            // if company
                            if($member->Company != null){
                                $loss[$knf]['name']               = $member->Company->name;
                                $loss[$knf]['mem_id']               = $member->Company->id;

                                $loss[$knf]['kind']               = 'company';
                            }
                            // if product
                            if($member->LocalStockproducts != null){
                                $loss[$knf]['name']              = $member->LocalStockproducts->name;
                                $loss[$knf]['mem_id']               = $member->LocalStockproducts->id;

                                $loss[$knf]['kind']               = 'product';
                            }

                            // last movement
                            foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
                            {
                                // price
                                if($value->LocalStockColumns->type == 'price' ){
                                    $loss[$knf]["price"]         = (string) $value->value;
                                }
                                // change
                                if($value->LocalStockColumns->type == 'change' ){
                                    $loss[$knf]["change"]         = (string) round($value->value, 2);
                                    $loss[$knf]["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                                }
                                // if  null
                                if($value->LocalStockColumns->type == null ){
                                    $loss[$knf]["new_columns"][]              = $value->value;

                                }

                                // state
                                if($value->LocalStockColumns->type == 'state' ){
                                    if($value->value === 'up' ){
                                        $loss[$knf]["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                    }
                                    if($value->value === 'down' ){
                                        $loss[$knf]["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                    }
                                    if($value->value === 'equal' ){
                                        $loss[$knf]["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                    }
                                }


                            }

                            $loss[$knf]["type"]                 = 1;
                        }



                        // members
                        foreach ($moves as $kkf => $member)
                        {
                            // if company
                            if($member->LocalStockMember->Company != null){
                                $loss[$kkf  + count($memberssort)]['name']               = $member->LocalStockMember->Company->name;
                                $loss[$kkf  + count($memberssort)]['mem_id']               = $member->LocalStockMember->Company->id;
                                $loss[$kkf  + count($memberssort)]['kind']               = 'company';
                            }
                            // if product
                            if($member->LocalStockMember->LocalStockproducts != null){
                                $loss[$kkf  + count($memberssort)]['name']              = $member->LocalStockMember->LocalStockproducts->name;
                                $loss[$kkf  + count($memberssort)]['mem_id']               = $member->LocalStockMember->LocalStockproducts->id;
                                $loss[$kkf  + count($memberssort)]['kind']               = 'product';
                            }

                            // last movement
                            foreach ($member->LocalStockDetials as $k => $value)
                            {
                                // price
                                if($value->LocalStockColumns->type == 'price' ){
                                    $loss[$kkf  + count($memberssort)]["price"]         = (string) $value->value;
                                }
                                // change
                                if($value->LocalStockColumns->type == 'change' ){
                                    $loss[$kkf  + count($memberssort)]["change"]         = (string) round($value->value, 2);
                                    $loss[$kkf  + count($memberssort)]["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                                }
                                // if  null
                                if($value->LocalStockColumns->type == null ){
                                    $loss[$kkf  + count($memberssort)]["new_columns"][]                 = $value->value;

                                }
                                // state
                                if($value->LocalStockColumns->type == 'state' ){
                                    if($value->value === 'up' ){
                                        $loss[$kkf  + count($memberssort)]["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                    }
                                    if($value->value === 'down' ){
                                        $loss[$kkf  + count($memberssort)]["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                    }
                                    if($value->value === 'equal' ){
                                        $loss[$kkf  + count($memberssort)]["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                    }
                                }



                            }
                            $loss[$kkf  + count($memberssort)]["type"]                 = 0;

                        }

                        $list['members']               = $loss;

                        if(count($moves) == 0 && count($memberssort) == 0){
                            $list['members'] = [];
                        }

                    }elseif($type == 'fodder'){

                        $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();


                        # check sub exist
                        if(!$sub)
                        {
                            $msg = 'sub not found';
                            return response()->json([
                                'status'   => '0',
                                'message'  => null,
                                'error'    => $msg,
                            ],400);
                        }

                        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

                        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();



                        $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
                        $foooooo = [];
                        // feeds
                        foreach ($feeds as $kf => $fed)
                        {

                            $foooooo['name']               = $fed->name;

                            $foooooo['id']              = $fed->id;
                            $list['feeds'][] =$foooooo;

                        }

                        $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');

                        $cooooo = [];
                        // feeds
                        foreach ($fodss as $kcf => $fecd)
                        {

                            $cooooo['name']               = $fecd->Company->name;


                            $cooooo['id']              = $fecd->Company->id;
                            $list['companies'][] =$cooooo;

                        }

                        if(is_null($fod_id)){
                            $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
                        }else{
                            $fod = Stock_Feeds::where('id',$fod_id)->first();
                        }

                        if(is_null($comp_id)){
                            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
                            if($fod_id){
                                $memberssort->where('fodder_id',$fod_id);
                            }
                            $memberssort =$memberssort->latest()->get()->unique('stock_id');

                            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');

                        }else{
                            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
                            if($fod_id){
                                $memberssort->where('fodder_id',$fod_id);
                            }
                            $memberssort =$memberssort->latest()->get()->unique('stock_id');

                            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->where('company_id',$comp_id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');

                        }
                        if(count($adss) == 0){
                            $list['banners'] = [];
                        }else{
                            foreach ($adss as $key => $ad)
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


                        $list['section_type']   =  $sub->Section->type;


                        $list['columns'][]["title"]   = ' الاسم';
                        $list['columns'][]["title"]   = 'الصنف';
                        $list['columns'][]["title"]   = 'السعر';
                        $list['columns'][]["title"]   = 'مقدار التغير';
                        $list['columns'][]["title"]   = 'اتجاه السعر';

                        // members
                        foreach ($memberssort as $kfds => $member)
                        {

                            $ros['name']               = $member->Company->name;
                            $ros['mem_id']               = $member->Company->id;

                            $ros['feed']              = $member->StockFeed->name;

                            // last movement


                            $ros["price"]         = (string) $member->price;

                            $ros["change"]         = (string) round($member->change, 2);
                            $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');


                            if($member->status === 'up' ){
                                $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                            }
                            if($member->status === 'down' ){
                                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                            }
                            if($member->status === 'equal' ){
                                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                            }

                            $ros['type']              = 1;


                            $list['members'][]                = $ros;

                        }

                        // members
                        foreach ($members as $kfd => $member)
                        {

                            $ro['name']               = $member->Company->name;

                            $ro['mem_id']               = $member->Company->id;

                            $ro['feed']              = $member->StockFeed->name;

                            // last movement


                            $ro["price"]         = (string) $member->price;

                            $ro["change"]         = (string) round($member->change, 2);
                            $ro["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                            if($member->status === 'up' ){
                                $ro["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                            }
                            if($member->status === 'down' ){
                                $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                            }
                            if($member->status === 'equal' ){
                                $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                            }

                            $ro['type']              = 0;
                            $list['members'][]                = $ro;
                        }

                        if(count($members) == 0 && count($memberssort) == 0){
                            $list['members'] = [];
                        }
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
}
