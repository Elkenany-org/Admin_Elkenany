<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InternationalStock\Entities\L_news;
use Session;
use Image;
use File;
use View;

class LatestNewsFrontController extends Controller
{

    # news
    public function news(Request $request)
    {

        $news = L_news::orderby('title')->paginate(10);
        $sort= '0';
        return view('internationalstock::fronts.news',compact('news','sort'));
    }

    # news
    public function newssort(Request $request)
    {


        $news = L_news::orderby('view_count' , 'desc')->paginate(10);
        $sort= '1';
        return view('internationalstock::fronts.news',compact('news','sort'));
    }

  

    # news
    public function onenews(Request $request, $id)
    {

        $new = L_news::where('id',$id)->first();

   
        $new->view_count = $new->view_count + 1;
        $new->save();
        $news = L_news::take(4)->inRandomOrder()->get();
        return view('internationalstock::fronts.new',compact('news','new'));
    }


    
}
 
 