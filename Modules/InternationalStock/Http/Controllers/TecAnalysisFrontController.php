<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InternationalStock\Entities\Tec_Analysis;
use Session;
use Image;
use File;
use View;

class TecAnalysisFrontController extends Controller
{

    # news
    public function news(Request $request)
    {

        $news = Tec_Analysis::orderby('title')->paginate(10);
        $sort= '0';
        return view('internationalstock::fronts.analysis',compact('news','sort'));
    }

    # news
    public function newssort(Request $request)
    {


        $news = Tec_Analysis::orderby('view_count' , 'desc')->paginate(10);
        $sort= '1';
        return view('internationalstock::fronts.analysis',compact('news','sort'));
    }

  

    # news
    public function onenews(Request $request, $id)
    {

        $new = Tec_Analysis::where('id',$id)->first();

   
        $new->view_count = $new->view_count + 1;
        $new->save();
        $news = Tec_Analysis::take(4)->inRandomOrder()->get();
        return view('internationalstock::fronts.oneanalysis',compact('news','new'));
    }


    
}
 
 