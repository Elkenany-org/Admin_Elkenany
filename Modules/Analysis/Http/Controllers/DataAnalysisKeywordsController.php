<?php

namespace Modules\Analysis\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\Data_Analysis;
use App\Main;
use Modules\FodderStock\Entities\Stock_Fodder_Section;
use Modules\Guide\Entities\Guide_Section;
use Modules\Guide\Entities\Services;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\Magazines\Entities\Mag_Section;
use Modules\Magazines\Entities\Magazine_Sec;
use Modules\News\Entities\News_Section;
use Modules\Recruitment\Entities\Job_Categories;
use Modules\Shows\Entities\Show_Section;
use Modules\Store\Entities\Store_Section;
use Modules\Tenders\Entities\Tender_Section;
use Session;

class DataAnalysisKeywordsController extends Controller
{
    # index
    public function Index()
    {
        $keywords = Data_Analysis_Keywords::latest()->paginate(100);
        $sectionss = Main::latest()->get();
        $sections_guide = Guide_Section::latest()->get();
        $sections_shows = Show_Section::latest()->get();
        $sections_magazines = Mag_Section::latest()->get();
        $sections_store = Store_Section::latest()->get();
        $sections_localstock = Local_Stock_Sections::latest()->get();
        $sections_fodderstock = Stock_Fodder_Section::latest()->get();
        $sections_news = News_Section::latest()->get();
        $sections_tenders = Tender_Section::latest()->get();
        $sections_jobs = Job_Categories::latest()->get();

        $keywords_of_services = Services::get();
        return view('analysis::data_analysis.keywords',compact('keywords','keywords_of_services','sectionss','sections_guide','sections_shows','sections_magazines','sections_store','sections_localstock','sections_fodderstock','sections_news','sections_tenders','sections_jobs'));
    }

    # keywords statistics
    public function KeywordsStatistics()
    {
        $keywords = Data_Analysis_Keywords::latest()->get();
        $dataPoints = [];
        foreach($keywords as $key)
        {
            $push['x']          = $key->id;
            $push['y']          = $key->use_count;
            $push['indexLabel'] = '(' . $key->use_count .') ' . $key->name;
            $dataPoints[]       = $push;
        }
        return view('analysis::data_analysis.keywords_statistics',compact('dataPoints'));
    }

    # get keywords statistics by date ( ajax )
    public function KeywordsStatisticsByDate(Request $request)
    {
        $keywords = Data_Analysis::whereDate('created_at',$request->date)->pluck('keyword_id')->toArray();
        $ids = array_unique($keywords);
        $dataPoints = [];
        foreach($ids as $i)
        {
            $count = Data_Analysis::where('keyword_id',$i)->count();
            $name  = Data_Analysis_Keywords::where('id',$i)->first();
            $push['x'] =$i;
            $push['y'] = $count;
            $push['indexLabel'] = '(' . $count .') ' . $name->name;
            $dataPoints[]  = $push;
        }
        return $dataPoints;
    }

    # keyword statistics
    public function KeywordStatistics($id)
    {
        $keyword = Data_Analysis_Keywords::where('id',$id)->first();
        $dates = Data_Analysis::where('keyword_id',$id)->pluck('created_at')->toArray();
        $monthes = [];
        foreach($dates as $k => $d)
        {
            $monthes[$k] = explode('-',$d)[1];
        }
        $fillters = array_unique($monthes);
        $dataPoints = [];
        foreach($fillters as $f)
        {
            $count = Data_Analysis::where('keyword_id',$id)->whereMonth('created_at', $f)->count();
            $push['x']          = $f;
            $push['y']          = $count;
            $push['indexLabel'] = '(' . $count .') ';
            $dataPoints[]  = $push;
        }
        return view('analysis::data_analysis.keyword_statistics',compact('dataPoints','keyword'));
    }

    # store
    public function Store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'keywords'  => 'required'
        ]);

        $last_id = Data_Analysis_Keywords::latest()->first();
        $keyword = new Data_Analysis_Keywords;

        if($last_id)
        {
            $keyword->id      = $last_id->id + 1;
        }
        $keyword->name    = $request->name;
        $keyword->keyword = $request->keywords;
        $keyword->type    = $request->type;
        $keyword->save();

        MakeReport('بإضافة كلمة دلالية '.$keyword->keywords);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # update
    public function Update(Request $request)
    {
        $request->validate([
            'edit_id'       => 'required',
            'edit_name'     => 'required|max:190',
            'edit_keyword'  => 'required|max:190'
        ]);

        $keyword = Data_Analysis_Keywords::where('id',$request->edit_id)->first();
        $keyword->name    = $request->edit_name;
        $keyword->keyword = $request->edit_keyword;
        $keyword->save();

        MakeReport('بتعديل كلمة دلالية '.$keyword->keyword);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete
    public function Delete(Request $request)
    {
        $request->validate([
            'id'  => 'required',
        ]);

        $keyword = Data_Analysis_Keywords::where('id',$request->id)->first();

        MakeReport('بحذف كلمة دلالية '.$keyword->keyword);
        $keyword->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
}
