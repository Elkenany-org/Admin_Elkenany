<?php
use Illuminate\Support\Facades\Route;

use App\User;
use App\Role;
use App\Contact;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Modules\SystemAds\Entities\System_Ads;
use App\Permission;
use Illuminate\Support\Facades\URL;


# banner
function banner()
{
    $routes = Route::getRoutes();
    foreach ($routes as $value)
    {
        if($value->getName() === Route::currentRouteName())
        {
            if($value->getName() !== null)
            {
                if(isset($value->getAction()['type']))
                {
                  
                    $ads = System_Ads::where('type','banner')->where('status','1')->pluck('id')->toArray();
                    $Pages = System_Ads_Pages::with('SystemAds')->whereIn('ads_id',$ads)->get();
                    echo ' <div class="container-fluid one-full-slider">';

                    foreach ($Pages as $page)
                    {
                        if($value->getName() == $page->page_route)
                        {
                            $sor = URL::to('uploads/ads/'.$page->SystemAds->image);

                            echo '<a href="#" target="_blank"><img alt="banner" class="banner" src="'.$sor.'"></a>';


    
                        
    
                        }
                    }

                    echo ' </div>';
                   

                    
                    
                }
            } 
        }

    }
}

# logos
function logos()
{
    $routes = Route::getRoutes();
    foreach ($routes as $value)
    {
        if($value->getName() === Route::currentRouteName())
        {
            if($value->getName() !== null)
            {
                if(isset($value->getAction()['type']))
                {
                  
                    $ads = System_Ads::where('type','logo')->where('status','1')->pluck('id')->toArray();
                    $Pages = System_Ads_Pages::with('SystemAds')->whereIn('ads_id',$ads)->get();
                    echo ' <article class="partners slider my-2">';
                        echo ' <div class="container-fluid logos__holder">';
                            echo ' <section class="partners-slider">';
                                foreach ($Pages as $page)
                                {
                                    if($value->getName() == $page->page_route)
                                    {
                                        $sor = URL::to('uploads/ads/'.$page->SystemAds->image);

                                        echo ' <div class="item">';

                                            echo '<a href="#" class="logo-holder"><img alt="partner logo" src="'.$sor.'"></a>';

                                            echo ' <div class="item">';
                                            echo ' </div>';
                
                                    
                
                                    }
                                }

                            echo ' </section>';
                        echo ' </div>';
                    echo ' </article>';
                   

                    
                    
                }
            } 
        }

    }
}


# logoo
function logoo()
{
    $routes = Route::getRoutes();
    foreach ($routes as $value)
    {
        if($value->getName() === Route::currentRouteName())
        {
            if($value->getName() !== null)
            {
                if(isset($value->getAction()['type']))
                {
                  
                    $ads = System_Ads::where('type','logo')->where('status','1')->pluck('id')->toArray();
                    $Pages = System_Ads_Pages::with('SystemAds')->whereIn('ads_id',$ads)->get();
                        foreach ($Pages as $page)
                        {
                            if($value->getName() == $page->page_route)
                            {
                                $sor = URL::to('uploads/ads/'.$page->SystemAds->image);

                                    echo ' <img class="company__logo" src="'.$sor.'" alt="company logo">';

        
                            }
                        }

                    
                }
            } 
        }

    }
}




