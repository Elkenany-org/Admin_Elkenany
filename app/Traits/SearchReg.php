<?php

namespace App\Traits;

use Modules\SystemAds\Entities\System_Ads;
use Modules\SystemAds\Entities\System_Ads_Pages;

trait SearchReg
{
    public function searchQuery($text){
        $replace = array(
            "أ",
            "ا",
            "إ",
            "آ",
            "ي",
            "ى",
            "ه",
            "ة",
        );
        $with = array("(أ|ا|آ|إ)",
            "(أ|ا|آ|إ)",
            "(أ|ا|آ|إ)",
            "(أ|ا|آ|إ)",
            "(ي|ى)",
            "(ي|ى)",
            "(ه|ة)",
            "(ه|ة)",
        );
        $new = array_combine($replace,$with);
        $return = "" ;
        $len = strlen(utf8_decode($text));
        for($i=0;$i<$len;$i++){
            $current = mb_substr($text,$i,1,'utf-8');
            if(isset($new[$current])){
                $return.=$new[$current];
            }
            else{
                $return.=$current;
            }
        }
        return $return;
    }



    public function BannersLogo($id,$type)
    {
        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$id)->where('type',$type)->pluck('ads_id');

        $bannars = System_Ads::where('sub','1')->whereIn('id',$page)->whereIN('type',['banner','logo'])->where('status','1')->inRandomOrder()->get();

        return $bannars;
    }
}