<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InternationalStock\Entities\Inter_Rep;
use Session;
use Image;
use File;
use View;

class InterRepFrontController extends Controller
{

    # news
    public function news(Request $request)
    {

        $news = Inter_Rep::orderby('title')->paginate(10);
        $sort= '0';
        return view('internationalstock::fronts.reports',compact('news','sort'));
    }

    # news
    public function newssort(Request $request)
    {


        $news = Inter_Rep::orderby('view_count' , 'desc')->paginate(10);
        $sort= '1';
        return view('internationalstock::fronts.reports',compact('news','sort'));
    }

  

    # news
    public function onenews(Request $request, $id)
    {

        $new = Inter_Rep::where('id',$id)->first();

   
        $new->view_count = $new->view_count + 1;
        $new->save();
        $news = Inter_Rep::take(4)->inRandomOrder()->get();
        return view('internationalstock::fronts.report',compact('news','new'));
    }


    
}
 
 