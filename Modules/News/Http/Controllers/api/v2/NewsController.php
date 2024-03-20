<?php

namespace Modules\News\Http\Controllers\api\v2;

use App\Traits\ApiResponse;
use App\Traits\SearchReg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Modules\Analysis\Entities\Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\News\Entities\Multi_Sec;
use Modules\News\Entities\News;
use Modules\News\Entities\News_Section;
use Modules\News\Transformers\NewsResource;
use Modules\Store\Entities\Customer;
use Modules\SystemAds\Entities\System_Ads;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Transformers\LogoBannerResource;
use Date;
use Illuminate\Support\Facades\URL;
class NewsController extends Controller
{
    use ApiResponse,SearchReg;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        $type = Input::get("type");
        $sort       = Input::get("sort");
        $search     = Input::get("search");

        if($search){
            $keyword = $this->searchQuery($search);
        }

        if(!$type)
        {
            return $this->ErrorMsg('you should send type of section');
        }

        $section = News_Section::where('type',$type)->first();

        if(!$section)
        {
            return $this->ErrorMsg('section not found');
        }

        # check recomndation system
        if($request->header('Authorization'))
        {
            $token = $request->header('Authorization');
            $token = explode(' ',$token);
            if(count( $token) == 2)
            {

                $customer = Customer::where('api_token',$token[1])->first();
                if($customer)
                {
                    $main_keyword = Data_Analysis_Keywords::where('type',$type)->where('keyword','news')->first();
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

        $page = System_Ads_Pages::with('SystemAds')->where('section_type',$type)->where('type','news')->pluck('ads_id')->toArray();

        $adss = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();

        $majs = Multi_Sec::where('section_id' , $section->id)->pluck('new_id')->toArray();

        $news = News::where('section_id',$section->id);

        if($search){
            $news->where('title' , 'REGEXP' , $keyword);
        }
        if(count($majs) > 0)
        {
            $news->orWhereIn('id',$majs);
        }
        if($sort == 3){
            $news->orderByDesc('view_count');
        }
        if($sort == 2){
            $news->orderByDesc('title');
        }
        if($sort == 1){
            $news->latest();
        }

        $news = $news->paginate(10);

        $sections = News_Section::select('id','name','type')->get();
        $sections->map(function ($section) use($type){
            $section['selected'] = $section->type == $type ? 1 :0;
            return $section;
        });

        $list = [];
        $list['sections']=$sections;
        $list['banners']=LogoBannerResource::collection($adss);
        $list['logos']=LogoBannerResource::collection($logos);

        if(count($news) == 0)
        {
            $list['data'] = [];
            $list['current_page']                     = $news->toArray()['current_page'];
            $list['last_page']                        = $news->toArray()['last_page'];
        }

        foreach ($news as $key => $new)
        {
            $list['data'][$key]['id']          = $new->id;
            $list['data'][$key]['title']       = $new->title;
            $list['data'][$key]['image']       = URL::to('uploads/news/avatar/'.$new->image);
            $list['data'][$key]['created_at']  = Date::parse($new->created_at)->diffForHumans();
            $list['current_page']              = $news->toArray()['current_page'];
            $list['last_page']                 = $news->toArray()['last_page'];
            $list['first_page_url']            = $news->toArray()['first_page_url'];
            $list['next_page_url']             = $news->toArray()['next_page_url'];
            $list['last_page_url']             = $news->toArray()['last_page_url'];

        }

        return $this->ReturnData($list);
    }

    public function filter_list()
    {

        $type = Input::get("type");

        if(!$type) { return $this->ErrorMsg('you should send type of section');}

        $sections = News_Section::select('id','name','type')->get();
        $sections->map(function($section) use($type ){
            $section['selected'] = $section->type == $type ? 1 : 0;
            return $section;
        });

        $sort = config('news.sorts');

        return $this->ReturnData(['sectors'=>$sections,'sort'=>$sort]);
    }

    public function show()
    {

        $id = Input::get("id");
        $News = News::with('NewsReferences','NewsAdditions')->where('id',$id)->first();

        # check News exist
        if(!$News) { return $this->ErrorMsgWithStatus('News not found');}

        $section = News_Section::where('id' , $News->section_id)->first();
        $page = System_Ads_Pages::with('SystemAds')->where('status','1')->where('section_type',$section->type)->where('type','news')->pluck('ads_id')->toArray();

        $banners = System_Ads::whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();
        $logos = System_Ads::whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $majs = Multi_Sec::where('section_id',$News->section_id)->pluck('new_id')->toArray();

        $related_news = News::select('id','title','image')->where('section_id',$News->section_id);
        if(count($majs) > 0){
            $related_news->orWhereIn('id',$majs);
        }
        $related_news = $related_news->take(4)->inRandomOrder()->get();

        $related_news->map(function ($new){
            $new['date_time'] = Date::parse($new->created_at)->diffForHumans();
            return $new;
        });
        $related_news->makeHidden(['image','image_thum_url']);


        return $this->ReturnData([
            'banners'=>LogoBannerResource::collection($banners),
            'logos'=>LogoBannerResource::collection($logos),
            'news'=>new NewsResource($News),
            'related_news'=>$related_news,

        ]);

    }
}
