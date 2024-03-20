<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InternationalStock\Entities\BodCast;
use Session;
use Image;
use File;
use View;

class bodcastFrontController extends Controller
{

    # news
    public function news(Request $request)
    {

        $news = BodCast::orderby('title')->paginate(10);
        $sort= '0';
        return view('internationalstock::fronts.bodcasts',compact('news','sort'));
    }

    # news
    public function newssort(Request $request)
    {


        $news = BodCast::orderby('view_count' , 'desc')->paginate(10);
        $sort= '1';
        return view('internationalstock::fronts.bodcasts',compact('news','sort'));
    }

  

    # news
    public function onenews(Request $request, $id)
    {

        $new = BodCast::where('id',$id)->first();

   
        $new->view_count = $new->view_count + 1;
        $new->save();
        $news = BodCast::take(4)->inRandomOrder()->get();
        return view('internationalstock::fronts.bodcast',compact('news','new'));
    }


    
}
 
 