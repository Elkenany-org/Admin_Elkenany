<?php

namespace Modules\InternationalStock\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\InternationalStock\Entities\Tv;
use Session;
use Image;
use File;
use View;

class tvFrontController extends Controller
{

    # news
    public function tvs(Request $request)
    {

        $tvs = Tv::orderby('title')->latest()->paginate(10);
        $tv = Tv::latest()->first();
        return view('internationalstock::fronts.tv',compact('tvs','tv'));
    }



    
}
 
 