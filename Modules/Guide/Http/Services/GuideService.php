<?php

namespace Modules\Guide\Http\Services;

use App\Configuration;
use App\Noty;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;



class GuideService
{
    use ApiResponse;

    public function CheckLinkOfAds($word,$arr)
    {

        foreach ($arr as $a){
            $temp = strstr( $a, $word);
            if($temp){
                $a['type'] = 'internal';
            }else{
                $a['type'] = 'external';
            }
        }

        return $arr;
    }

}