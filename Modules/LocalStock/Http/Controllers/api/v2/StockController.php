<?php

namespace Modules\LocalStock\Http\Controllers\api\v2;

use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Modules\FodderStock\Entities\Fodder_Stock;
use Modules\FodderStock\Entities\Fodder_Stock_Move;
use Modules\FodderStock\Entities\Stock_Feeds;
use Modules\FodderStock\Entities\Stock_Fodder_Sub;
use Modules\Guide\Entities\Company;
use Modules\LocalStock\Entities\Local_Stock_Member;
use Modules\LocalStock\Entities\Local_Stock_Movement;
use Modules\LocalStock\Entities\Local_Stock_Sub;
use Modules\LocalStock\Transformers\AdsSystemResource;
use Modules\Store\Entities\Customer;
use Modules\SystemAds\Entities\System_Ads;
use Modules\SystemAds\Entities\System_Ads_Pages;
use Date,URL;

class StockController extends Controller
{
    use ApiResponse;
    protected function localWithoutDate($id)
    {
        $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();

        $sub->view_count = $sub->view_count + 1;
        # check sub exist
        if(!$sub)
        {
            $msg = 'sub not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


        $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

        $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->get();



        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);
            }
        }

        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);
            }
        }

        // columns
        $list['columns'][]["title"]   = "الاسم";
        foreach ($sub->LocalStockColumns as $key => $col)
        {
            if($col->type == 'price' ){
                $list['columns'][]["title"]   = $col->name;
            }

            if($col->type == 'change' ){
                $list['columns'][]["title"]   = $col->name;
            }

            if($col->type == null ){
                $list['columns'][]["title"]   = $col->name;
            }
        }
        $list['columns'][]["title"]   = 'اتجاه السعر';

        // memberssort
        foreach ($memberssort as $kn => $member)
        {
            // if company
            if($member->Company != null){
                $loss[$kn]['name']               = $member->Company->name;
                $loss[$kn]['mem_id']               = $member->Company->id;
                $loss[$kn]['kind']               = 'company';
            }
            // if product
            if($member->LocalStockproducts != null){
                $loss[$kn]['name']              = $member->LocalStockproducts->name;
                $loss[$kn]['mem_id']              = $member->LocalStockproducts->id;
                $loss[$kn]['kind']               = 'product';
            }
            // last movement
            foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
            {
                // price
                if($value->LocalStockColumns->type == 'price' ){
                    $loss[$kn]["price"]         = $value->value;
                }
                // change
                if($value->LocalStockColumns->type == 'change' ){
                    $loss[$kn]["change"]         = (string) round($value->value, 2);
                    $loss[$kn]["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                }
                // if  null
                // if  null
                if($value->LocalStockColumns->type == null ){
                    $loss[$kn]["new_columns"][]                 = $value->value;
                }

                // state
                if($value->LocalStockColumns->type == 'state' ){
                    if($value->value === 'up' ){
                        $loss[$kn]["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($value->value === 'down' ){
                        $loss[$kn]["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($value->value === 'equal' ){
                        $loss[$kn]["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                    }
                }

            }

            $loss[$kn]["type"]                 = 1;
        }

        // members
        foreach ($members as $kk => $member)
        {
            // if company
            if($member->Company != null){
                $loss[$kk + count($memberssort)]['name']               = $member->Company->name;
                $loss[$kk + count($memberssort)]['mem_id']               = $member->Company->id;
                $loss[$kk + count($memberssort)]['kind']               = 'company';
            }
            // if product
            if($member->LocalStockproducts != null){
                $loss[$kk + count($memberssort)]['name']              = $member->LocalStockproducts->name;

                $loss[$kk + count($memberssort)]['mem_id']              = $member->LocalStockproducts->id;
                $loss[$kk + count($memberssort)]['kind']               = 'product';
            }

            // last movement
            foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
            {
                // price
                if($value->LocalStockColumns->type == 'price' ){
                    $loss[$kk + count($memberssort)]["price"]         = $value->value;
                }
                // change
                if($value->LocalStockColumns->type == 'change' ){
                    // $loss[$kk + count($memberssort)]["change"]         = round($value->value, 2) . Date::parse($value->created_at)->format('H:i / Y-m-d');
                    $loss[$kk + count($memberssort)]["change"]         =(string) round($value->value, 2);
                    $loss[$kk + count($memberssort)]["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                }
                // if  null
                // if  null
                if($value->LocalStockColumns->type == null ){
                    $loss[$kk + count($memberssort)]["new_columns"][]                 = $value->value;

                }

                // state
                if($value->LocalStockColumns->type == 'state' ){
                    if($value->value === 'up' ){
                        $loss[$kk + count($memberssort)]["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($value->value === 'down' ){
                        $loss[$kk + count($memberssort)]["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($value->value === 'equal' ){
                        $loss[$kk + count($memberssort)]["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                    }
                }
            }
            $loss[$kk + count($memberssort)]["type"]                 = 0;
        }

        $list['members']                 = $loss;

        if(count($members) == 0 && count($memberssort) == 0){
            $list['members'] = [];
        }
        return $list;
    }

    protected function fodderWithoutDate($id,$comp_id,$fod_id)
    {
        $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();

        # check sub exist
        if(!$sub)
        {
            $msg = 'sub not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();

        $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
        $selected_feeds = Stock_Feeds::where('section_id',$sub->id)->where('id',$fod_id)->first();


        $foooooo = [];
        // $fooooo = [];
        # feeds
        if($selected_feeds)
        {
            $foooooo['name']     = $selected_feeds->name;
            $foooooo['id']       = $selected_feeds->id;
            $list['feeds'][]     = $foooooo;
        }
        // feeds
        foreach ($feeds as $kf => $fed)
        {
            $foooooo['name']               = $fed->name;
            $foooooo['id']              = $fed->id;
            $list['feeds'][] =$foooooo;
        }

        $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');
        $selected_company = Company::where('id',$comp_id)->first();

        $cooooo = [];
        if($selected_company)
        {
            $cooooo['name']    = $selected_company->name;
            $cooooo['id']      = $selected_company->id;
            $list['companies'][] =$cooooo;
        }
        // feeds
        foreach ($fodss as $kcf => $fecd)
        {
            if($comp_id != $fecd->Company->id)
            {
                $cooooo['name']           = $fecd->Company->name;
                $cooooo['id']              = $fecd->Company->id;
                $list['companies'][] =$cooooo;
            }
        }

        if(is_null($fod_id)){
            $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
        }else{
            $fod = Stock_Feeds::where('id',$fod_id)->where('section_id',$sub->id)->first();
        }

        $ads[] = $comp_id;

        if(is_null($comp_id))
        {
            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
            if($fod_id){
                $memberssort->where('fodder_id',$fod_id);
            }
            $memberssort = $memberssort->latest()->get()->unique('stock_id');
            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->latest()->get()->unique('stock_id');
        }else{
            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
            if($fod_id){
                $memberssort->where('fodder_id',$fod_id);
            }
            $memberssort = $memberssort->latest()->get()->unique('stock_id');
            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('company_id',$comp_id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->latest()->get()->unique('stock_id');
        }



        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


            }

        }


        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


            }
        }


        $list['section_type']   =  $sub->Section->type;


        $list['columns'][]["title"]   = ' الاسم';
        $list['columns'][]["title"]   = 'الصنف';
        $list['columns'][]["title"]   = 'السعر';
        $list['columns'][]["title"]   = 'مقدار التغير';
        $list['columns'][]["title"]   = 'اتجاه السعر';

        // members
        foreach ($memberssort as $kfds => $member)
        {

            $ros['name']               = $member->Company->name;
            $ros['mem_id']               = $member->Company->id;

            $ros['feed']              = $member->StockFeed->name;

            // last movement


            $ros["price"]         = $member->price;

            $ros["change"]         = (string) round($member->change, 2);
            $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

            if($member->status === 'up' ){
                $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
            }
            if($member->status === 'down' ){
                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
            }
            if($member->status === 'equal' ){
                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');

            }
            $ros['type']              = 1;
            $list['members'][]                = $ros;

        }
        // return $ros;
        // members
        foreach ($members as $kfd => $mem)
        {

            $ro['name']               = $mem->Company->name;
            $ro['mem_id']               = $mem->Company->id;
            $ro['feed']              = $mem->StockFeed->name;

            // last movement


            $ro["price"]         = $mem->price;

            $ro["change"]         = (string) round($mem->change, 2);
            $ro["change_date"]         = Date::parse($mem->created_at)->format('H:i / Y-m-d');


            if($mem->status === 'up' ){
                $ro["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
            }
            if($mem->status === 'down' ){
                $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
            }
            if($mem->status === 'equal' ){
                $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
            }
            $ro['type']              = 0;
            $list['members'][]                = $ro;
        }
        // return $ro;
        if(count($members) == 0 && count($memberssort) == 0){
            $list['members'] = [];
        }
        return $list;
    }

    protected function localWithAuthMember($id,$date){
        $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();

        # check sub exist
        if(!$sub)
        {
            $msg = 'sub not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $sub->view_count = $sub->view_count + 1;

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


        $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

        $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->where('status',1)->pluck('id')->toArray();

        $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members)->whereDate('created_at',$date)->latest()->get()->unique('member_id');

        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


            }

        }


        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


            }
        }



        // columns
        $list['columns'][]["title"]   = "الاسم";
        foreach ($sub->LocalStockColumns as $key => $col)
        {
            if($col->type == 'price' ){
                $list['columns'][]["title"]   = $col->name;
            }

            if($col->type == 'change' ){
                $list['columns'][]["title"]   = $col->name;
            }

            if($col->type == null ){
                $list['columns'][]["title"]   = $col->name;
            }



        }
        $list['columns'][]["title"]   = 'اتجاه السعر';

        // memberssort
        $push = [];
        foreach ($memberssort as $kno => $member)
        {
            // if company
            if($member->Company != null){
                $loss['name']               = $member->Company->name;
                $loss['mem_id']               = $member->Company->id;
                $loss['kind']               = 'company';

            }
            // if product
            if($member->LocalStockproducts != null){
                $loss['name']              = $member->LocalStockproducts->name;
                $loss['mem_id']               = $member->LocalStockproducts->id;
                $loss['kind']               = 'product';
            }

            // last movement
            foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
            {
                // price
                if($value->LocalStockColumns->type == 'price' ){
                    $loss["price"]         = $value->value;
                }
                // change
                if($value->LocalStockColumns->type == 'change' ){
                    $loss["change"]         = (string) round($value->value, 2);
                    $loss["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                }
                // if  null
                if($value->LocalStockColumns->type == null ){
                    $loss["new_columns"][]                 = $value->value;

                }

                // state
                if($value->LocalStockColumns->type == 'state' ){
                    if($value->value === 'up' ){
                        $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($value->value === 'down' ){
                        $loss["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($value->value === 'equal' ){
                        $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                    }
                }


            }

            $loss["type"]                 = 1;
            $push[] = $loss;


        }



        // members
        foreach ($moves as $kko => $member)
        {
            // if company
            if($member->LocalStockMember->Company != null){
                $loss['name']               = $member->LocalStockMember->Company->name;
                $loss['mem_id']               = $member->LocalStockMember->Company->id;
                $loss['kind']               = 'company';
            }
            // if product
            if($member->LocalStockMember->LocalStockproducts != null){
                $loss['name']              = $member->LocalStockMember->LocalStockproducts->name;
                $loss['mem_id']               = $member->LocalStockMember->LocalStockproducts->id;
                $loss['kind']               = 'product';
            }

            // last movement
            foreach ($member->LocalStockDetials as $koo => $value)
            {
                // price
                if($value->LocalStockColumns->type == 'price' ){
                    $loss["price"]         = $value->value;
                }
                // change
                if($value->LocalStockColumns->type == 'change' ){
                    // $loss["change"]         = round($value->value, 2)  . "  /". Date::parse($value->created_at)->format('H:i / Y-m-d');
                    $loss["change"]         = (string) round($value->value, 2);
                    $loss["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');
                }
                // if  null
                if($value->LocalStockColumns->type == null ){
                    $loss["new_columns"]                 = [$value->value];

                }

                // state
                if($value->LocalStockColumns->type == 'state' ){
                    if($value->value === 'up' ){
                        $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($value->value === 'down' ){
                        $loss["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($value->value === 'equal' ){
                        $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                    }
                }



            }

            $loss["type"]                 = 0;


            $push[] = $loss;
        }

        $list['members']               = $push;
        if(count($moves) == 0 && count($memberssort) == 0){
            $list['members'] = [];
        }
        return $list;
    }

    protected function FodderWithAuthMember($id,$fod_id,$comp_id,$date){
        $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();


        # check sub exist
        if(!$sub)
        {
            $msg = 'sub not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();



        $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
        $selected_feeds = Stock_Feeds::where('section_id',$sub->id)->where('id',$fod_id)->first();



        $foooooo = [];
        // $fooooo = [];
        # feeds
        if($selected_feeds)
        {
            $foooooo['name']     = $selected_feeds->name;
            $foooooo['id']       = $selected_feeds->id;
            $list['feeds'][]     = $foooooo;
        }
        foreach ($feeds as $kf => $fed)
        {

            $foooooo['name']               = $fed->name;

            $foooooo['id']              = $fed->id;
            $list['feeds'][] =$foooooo;

        }

        $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');
        $selected_company = Company::where('id',$comp_id)->first();

        $cooooo = [];
        if($selected_company)
        {
            $cooooo['name']    = $selected_company->name;
            $cooooo['id']      = $selected_company->id;
            $list['companies'][] =$cooooo;
        }
        // feeds
        foreach ($fodss as $kcf => $fecd)
        {

            $cooooo['name']               = $fecd->Company->name;


            $cooooo['id']              = $fecd->Company->id;
            $list['companies'][] =$cooooo;

        }

        if(is_null($fod_id)){
            $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
        }else{
            $fod = Stock_Feeds::where('id',$fod_id)->first();
        }

//                                $ads[] = $comp_id;
        if(is_null($comp_id)){
            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->whereDate('created_at',$date)->with('Section','StockFeed','Company','FodderStock');
            if($fod_id){
                $memberssort->where('fodder_id',$fod_id);
            }

            $memberssort = $memberssort->latest()->get()->unique('stock_id');

            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');


        }else{
            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->whereDate('created_at',$date)->with('Section','StockFeed','Company','FodderStock');

            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('company_id',$comp_id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date);

            if($fod_id){
                $memberssort->where('fodder_id',$fod_id);
                $members->where('fodder_id',$fod_id);
            }
            $memberssort = $memberssort->latest()->get()->unique('stock_id');

            $members = $members->latest()->get()->unique('stock_id');
        }

        $ms = [];
        foreach ($memberssort as $k => $v){
            $ms[] = $v->id;
        }
        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);
            }
        }

        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);
            }
        }


        $list['section_type']   =  $sub->Section->type;

        $list['columns'][]["title"]   = ' الاسم';
        $list['columns'][]["title"]   = 'الصنف';
        $list['columns'][]["title"]   = 'السعر';
        $list['columns'][]["title"]   = 'مقدار التغير';
        $list['columns'][]["title"]   = 'اتجاه السعر';

        // members
        foreach ($memberssort as $kfds => $member)
        {

            $ros['name']               = $member->Company->name;
            $ros['mem_id']               = $member->Company->id;
            $ros['feed']              = $member->StockFeed->name;

            // last movement

            $ros["price"]         = $member->price;
            $ros["change"]         = (string) round($member->change, 2);
            $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');


            if($member->status === 'up' ){
                $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
            }
            if($member->status === 'down' ){
                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
            }
            if($member->status === 'equal' ){
                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
            }
            $ros['type']              = 1;
            $list['members'][]                = $ros;
        }

        // members
        foreach ($members as $kfd => $member)
        {
            if(!in_array($member->id ,$ms)) {
                $ro['name'] = $member->Company->name;
                $ro['mem_id'] = $member->Company->id;
                $ro['feed'] = $member->StockFeed->name;

                // last movement
                $ro["price"] = $member->price;
                $ro["change"] = (string)round($member->change, 2);
                $ro["change_date"] = Date::parse($member->created_at)->format('H:i / Y-m-d');


                if ($member->status === 'up') {
                    $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                }
                if ($member->status === 'down') {
                    $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                }
                if ($member->status === 'equal') {
                    $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                }
                $ro['type'] = 0;
                $list['members'][] = $ro;
            }
        }

        if(count($members) == 0 && count($memberssort) == 0){
            $list['members'] = [];
        }
        return $list;
    }

    protected function localWithAuthWithoutMemb($id,$date)
    {
        $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();

        $sub->view_count = $sub->view_count + 1;
        # check sub exist
        if(!$sub)
        {
            $msg = 'sub not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


        $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

        $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->where('status',1)->pluck('id')->toArray();

        $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members)->whereDate('created_at',$date)->latest()->get()->unique('member_id');

        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);
            }
        }


        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);
            }
        }



        // columns
        $list['columns'][]["title"]   = "الاسم";
        foreach ($sub->LocalStockColumns as $key => $col)
        {
            if($col->type == 'price' ){
                $list['columns'][]["title"]   = $col->name;
            }

            if($col->type == 'change' ){
                $list['columns'][]["title"]   = $col->name;
            }

            if($col->type == null ){
                $list['columns'][]["title"]   = $col->name;
            }
        }
        $list['columns'][]["title"]   = 'اتجاه السعر';

        // memberssort
        // memberssort
        foreach ($memberssort as $knt => $member)
        {
            // if company
            if($member->Company != null){
                $loss[$knt]['name']               = $member->Company->name;
                $loss[$knt]['mem_id']               = $member->Company->id;
                $loss[$knt]['kind']               = 'company';
            }
            // if product
            if($member->LocalStockproducts != null){
                $loss[$knt]['name']              = $member->LocalStockproducts->name;
                $loss[$knt]['mem_id']               = $member->LocalStockproducts->id;
                $loss[$knt]['kind']               = 'product';
            }

            // last movement
            foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
            {
                // price
                if($value->LocalStockColumns->type == 'price' ){
                    $loss[$knt]["price"]         = $value->value;
                }
                // change
                if($value->LocalStockColumns->type == 'change' ){
                    //  $loss[$knt]["change"]         = round($value->value, 2)  . "  /".  Date::parse($value->created_at)->format('H:i / Y-m-d');
                    $loss[$knt]["change"]         = (string) round($value->value, 2);
                    $loss[$knt]["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');
                }
                // if  null
                if($value->LocalStockColumns->type == null ){
                    $loss[$knt]["new_columns"][]                 = $value->value;

                }

                // state
                if($value->LocalStockColumns->type == 'state' ){
                    if($value->value === 'up' ){
                        $loss[$knt]["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($value->value === 'down' ){
                        $loss[$knt]["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($value->value === 'equal' ){
                        $loss[$knt]["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                    }
                }
            }
            $loss[$knt]["type"]                 = 1;
        }


        $push = [];
        // members
        foreach ($moves as $kkt => $member)
        {
            // if company
            if($member->LocalStockMember->Company != null){
                $loss['name']               = $member->LocalStockMember->Company->name;
                $loss['mem_id']               = $member->LocalStockMember->Company->id;
                $loss['kind']               = 'company';
            }
            // if product
            if($member->LocalStockMember->LocalStockproducts != null){
                $loss['name']              = $member->LocalStockMember->LocalStockproducts->name;
                $loss['mem_id']               = $member->LocalStockMember->LocalStockproducts->id;
                $loss['kind']               = 'product';
            }

            // last movement
            foreach ($member->LocalStockDetials as $k => $value)
            {
                // price
                if($value->LocalStockColumns->type == 'price' ){
                    $loss["price"]         = $value->value;
                }
                // change
                if($value->LocalStockColumns->type == 'change' ){
                    $loss["change"]         = (string) round($value->value, 2);

                    $loss["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                }
                // if  null
                if($value->LocalStockColumns->type == null ){
                    $loss["new_columns"]             = [$value->value];

                }

                // state
                if($value->LocalStockColumns->type == 'state' ){
                    if($value->value === 'up' ){
                        $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($value->value === 'down' ){
                        $loss["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($value->value === 'equal' ){
                        $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                    }
                }
            }
            $loss["type"]                 = 0;

            $push[] =  $loss;

        }

        $list['members']                = $push;


        if(count($moves) == 0 && count($memberssort) == 0){
            $list['members'] = [];
        }
        return $list;
    }

    protected function fodderWithAuthWithoutMemb($id,$comp_id,$fod_id,$date)
    {
        $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();


        # check sub exist
        if(!$sub)
        {
            $msg = 'sub not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();



        $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
        $selected_feeds = Stock_Feeds::where('section_id',$sub->id)->where('id',$fod_id)->first();



        $foooooo = [];
        // $fooooo = [];
        # feeds
        if($selected_feeds)
        {
            $foooooo['name']     = $selected_feeds->name;
            $foooooo['id']       = $selected_feeds->id;
            $list['feeds'][]     = $foooooo;
        }
        // feeds
        foreach ($feeds as $kf => $fed)
        {

            $foooooo['name']               = $fed->name;

            $foooooo['id']              = $fed->id;
            $list['feeds'][] =$foooooo;

        }

        $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');

        $selected_company = Company::where('id',$comp_id)->first();

        $cooooo = [];
        if($selected_company)
        {
            $cooooo['name']    = $selected_company->name;
            $cooooo['id']      = $selected_company->id;
            $list['companies'][] =$cooooo;
        }
        // feeds
        foreach ($fodss as $kcf => $fecd)
        {

            $cooooo['name']               = $fecd->Company->name;
            $cooooo['id']              = $fecd->Company->id;
            $list['companies'][] =$cooooo;

        }

        if(is_null($fod_id)){
            $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
        }else{
            $fod = Stock_Feeds::where('id',$fod_id)->first();
        }

        if(is_null($comp_id)){
            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->whereDate('created_at',$date)->with('Section','StockFeed','Company','FodderStock');
            if($fod_id){
                $memberssort->where('fodder_id',$fod_id);
            }
            $memberssort = $memberssort->latest()->get()->unique('stock_id');

            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');


        }else{
            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
            if($fod_id){
                $memberssort->where('fodder_id',$fod_id);
            }
            $memberssort = $memberssort->latest()->get()->unique('stock_id');

            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->where('company_id',$comp_id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');


        }


        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


            }

        }


        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


            }
        }


        $list['section_type']   =  $sub->Section->type;


        $list['columns'][]["title"]   = ' الاسم';
        $list['columns'][]["title"]   = 'الصنف';
        $list['columns'][]["title"]   = 'السعر';
        $list['columns'][]["title"]   = 'مقدار التغير';
        $list['columns'][]["title"]   = 'اتجاه السعر';

        // members
        foreach ($memberssort as $kfds => $member)
        {

            $ros['name']               = $member->Company->name;
            $ros['mem_id']               = $member->Company->id;

            $ros['feed']              = $member->StockFeed->name;

            // last movement


            $ros["price"]         = $member->price;

            $ros["change"]         = (string) round($member->change, 2);
            $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

            if($member->status === 'up' ){
                $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
            }
            if($member->status === 'down' ){
                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
            }
            if($member->status === 'equal' ){
                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
            }

            $ros['type']              = 1;

            $list['members'][]                = $ros;
        }

        // members
        foreach ($members as $kfd => $member)
        {

            $ro['name']               = $member->Company->name;
            $ro['mem_id']               = $member->Company->id;

            $ro['feed']              = $member->StockFeed->name;

            // last movement


            $ro["price"]         = $member->price;

            $ro["change"]         = (string) round($member->change, 2);
            $ro["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

            if($member->status === 'up' ){
                $ro["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
            }
            if($member->status === 'down' ){
                $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
            }
            if($member->status === 'equal' ){
                $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
            }

            $ro['type']              = 0;
            $list['members'][]                = $ro;
        }

        if(count($members) == 0 && count($memberssort) == 0){
            $list['members'] = [];
        }
        return $list;
    }

    protected function localWithAuthOutCount($id,$date)
    {
        // return 0;
        $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();



        $sub->view_count = $sub->view_count + 1;
        # check sub exist
        if(!$sub)
        {
            $msg = 'sub not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


        $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

        $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->where('status',1)->pluck('id')->toArray();

        $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members)->whereDate('created_at',$date)->latest()->get()->unique('member_id');

        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


            }

        }


        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


            }
        }



        // columns
        $list['columns'][]["title"]   = "الاسم";
        foreach ($sub->LocalStockColumns as $key => $col)
        {
            if($col->type == 'price' ){
                $list['columns'][]["title"]   = $col->name;
            }

            if($col->type == 'change' ){
                $list['columns'][]["title"]   = $col->name;
            }

            if($col->type == null ){
                $list['columns'][]["title"]   = $col->name;
            }



        }
        $list['columns'][]["title"]   = 'اتجاه السعر';

        // memberssort
        $push = [];
        foreach ($memberssort as $knr => $member)
        {

            // if company
            if($member->Company != null){
                $loss['name']               = $member->Company->name;
                $loss['mem_id']               = $member->Company->id;
                $loss['kind']               = 'company';
            }
            // if product
            if($member->LocalStockproducts != null){
                $loss['name']              = $member->LocalStockproducts->name;
                $loss['mem_id']               = $member->LocalStockproducts->id;
                $loss['kind']               = 'product';
            }

            // last movement
            foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
            {
                // price
                if($value->LocalStockColumns->type == 'price' ){
                    $loss["price"]         = $value->value;
                }
                // change
                if($value->LocalStockColumns->type == 'change' ){
                    $loss["change"]         = (string) round($value->value, 2);
                    $loss["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                }
                // if  null
                if($value->LocalStockColumns->type == null ){
                    $loss["new_columns"][]                 = $value->value;

                }

                // state
                if($value->LocalStockColumns->type == 'state' ){
                    if($value->value === 'up' ){
                        $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($value->value === 'down' ){
                        $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($value->value === 'equal' ){
                        $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                    }
                }



            }

            $loss["type"]                 = 1;
            $push[] = $loss;
        }



        // members

        foreach ($moves as $kkr => $member)
        {
            // if company
            if($member->LocalStockMember->Company != null){
                $loss['name']               = $member->LocalStockMember->Company->name;
                $loss['mem_id']               = $member->LocalStockMember->Company->id;
                $loss['kind']               = 'company';
            }
            // if product
            if($member->LocalStockMember->LocalStockproducts != null){
                $loss['name']              = $member->LocalStockMember->LocalStockproducts->name;
                $loss['mem_id']               = $member->LocalStockMember->LocalStockproducts->id;
                $loss['kind']               = 'product';
            }

            // last movement
            foreach ($member->LocalStockDetials as $k => $value)
            {
                // price
                if($value->LocalStockColumns->type == 'price' ){
                    $loss["price"]         = $value->value;
                }
                // change
                if($value->LocalStockColumns->type == 'change' ){

                    // $loss["change"]         = round($value->value, 2)  . "  /". Date::parse($value->created_at)->format('H:i / Y-m-d');
                    $loss["change"]         = (string) round($value->value, 2);
                    $loss["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                }
                // if  null
                if($value->LocalStockColumns->type == null ){////////////////////////
                    $loss["new_columns"]              = [$value->value];

                }



                // state
                if($value->LocalStockColumns->type == 'state' ){
                    if($value->value === 'up' ){
                        $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($value->value === 'down' ){
                        $loss["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($value->value === 'equal' ){
                        $loss["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                    }
                }

            }
            $loss["type"]                 = 0;
            $push[] = $loss;


        }
        $list['members']              = $push;
        // return  $push;

        if(count($moves) == 0 && count($memberssort) == 0){
            $list['members'] = [];
        }
        return $list;
    }

    protected function fodderWithAuthOutCount($id,$comp_id,$fod_id,$date)
    {
        $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();


        # check sub exist
        if(!$sub)
        {
            $msg = 'sub not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();



        $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
        $foooooo = [];
        // feeds
        foreach ($feeds as $kf => $fed)
        {

            $foooooo['name']               = $fed->name;

            $foooooo['id']              = $fed->id;
            $list['feeds'][] =$foooooo;

        }

        $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');

        $cooooo = [];
        // feeds
        foreach ($fodss as $kcf => $fecd)
        {

            $cooooo['name']               = $fecd->Company->name;


            $cooooo['id']              = $fecd->Company->id;
            $list['companies'][] =$cooooo;

        }

        if(is_null($fod_id)){
            $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
        }else{
            $fod = Stock_Feeds::where('id',$fod_id)->first();
        }
//                            $ads[] = $comp_id;
        if(is_null($comp_id)){
            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date);
            if($fod_id){
                $memberssort->where('fodder_id',$fod_id);
            }
            $memberssort  =  $memberssort->latest()->get()->unique('stock_id');

            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');


        }else{
            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date);
            if($fod_id){
                $memberssort->where('fodder_id',$fod_id);
            }
            $memberssort  =  $memberssort->latest()->get()->unique('stock_id');

            $members = Fodder_Stock_Move::with('Section','StockFeed','Company','FodderStock')->where('sub_id',$sub->id)->where('company_id',$comp_id);
            if(!is_null($fod_id)){
                $members->where('fodder_id',$fod->id);
            }
            $members = $members->whereDate('created_at',$date)->latest()->get()->unique('stock_id');
        }

        $ms = [];
        foreach ($memberssort as $k => $v){
            $ms[] = $v->id;
        }

        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


            }

        }


        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


            }
        }


        $list['section_type']   =  $sub->Section->type;


        $list['columns'][]["title"]   = ' الاسم';
        $list['columns'][]["title"]   = 'الصنف';
        $list['columns'][]["title"]   = 'السعر';
        $list['columns'][]["title"]   = 'مقدار التغير';
        $list['columns'][]["title"]   = 'اتجاه السعر';

        // members
        foreach ($memberssort as $kfds => $member)
        {

            $ros['name']               = $member->Company->name;
            $ros['mem_id']               = $member->Company->id;

            $ros['feed']              = $member->StockFeed->name;

            // last movement


            $ros["price"]         = $member->price;

            $ros["change"]         = (string) round($member->change, 2);
            $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

            if($member->status === 'up' ){
                $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
            }
            if($member->status === 'down' ){
                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
            }
            if($member->status === 'equal' ){
                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
            }

            $ros['type']              = 1;
            $list['members'][]                = $ros;
        }

        // members
        foreach ($members as $kfd => $member)
        {
            if(!in_array($member->id, $ms)) {
                $ro['name'] = $member->Company->name;
                $ro['mem_id'] = $member->Company->id;

                $ro['feed'] = $member->StockFeed->name;

                // last movement


                $ro["price"] = $member->price;

                $ro["change"] = (string)round($member->change, 2);
                $ro["change_date"] = Date::parse($member->created_at)->format('H:i / Y-m-d');

                if ($member->status === 'up') {
                    $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                }
                if ($member->status === 'down') {
                    $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                }
                if ($member->status === 'equal') {
                    $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                }

                $ro['type'] = 0;
                $list['members'][] = $ro;
            }
        }
        if(count($members) == 0 && count($memberssort) == 0){
            $list['members'] = [];
        }

    }

    protected function localWithoutAuth($id,$date)
    {
        $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();



        $sub->view_count = $sub->view_count + 1;
        # check sub exist
        if(!$sub)
        {
            $msg = 'sub not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


        $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

        $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->where('status',1)->pluck('id')->toArray();

        $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members)->whereDate('created_at',$date)->latest()->get()->unique('member_id');

        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


            }

        }


        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


            }
        }



        // columns
        $list['columns'][]["title"]   = "الاسم";
        foreach ($sub->LocalStockColumns as $key => $col)
        {
            if($col->type == 'price' ){
                $list['columns'][]["title"]   = $col->name;
            }

            if($col->type == 'change' ){
                $list['columns'][]["title"]   = $col->name;
            }

            if($col->type == null ){
                $list['columns'][]["title"]   = $col->name;
            }



        }
        $list['columns'][]["title"]   = 'اتجاه السعر';

        // memberssort
        foreach ($memberssort as $knf => $member)
        {
            // if company
            if($member->Company != null){
                $loss[$knf]['name']               = $member->Company->name;
                $loss[$knf]['mem_id']               = $member->Company->id;

                $loss[$knf]['kind']               = 'company';
            }
            // if product
            if($member->LocalStockproducts != null){
                $loss[$knf]['name']              = $member->LocalStockproducts->name;
                $loss[$knf]['mem_id']               = $member->LocalStockproducts->id;

                $loss[$knf]['kind']               = 'product';
            }

            // last movement
            foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
            {
                // price
                if($value->LocalStockColumns->type == 'price' ){
                    $loss[$knf]["price"]         = $value->value;
                }
                // change
                if($value->LocalStockColumns->type == 'change' ){
                    $loss[$knf]["change"]         = (string) round($value->value, 2);
                    $loss[$knf]["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                }
                // if  null
                if($value->LocalStockColumns->type == null ){
                    $loss[$knf]["new_columns"][]              = $value->value;

                }

                // state
                if($value->LocalStockColumns->type == 'state' ){
                    if($value->value === 'up' ){
                        $loss[$knf]["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($value->value === 'down' ){
                        $loss[$knf]["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($value->value === 'equal' ){
                        $loss[$knf]["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                    }
                }


            }

            $loss[$knf]["type"]                 = 1;
        }



        // members
        foreach ($moves as $kkf => $member)
        {
            // if company
            if($member->LocalStockMember->Company != null){
                $loss[$kkf  + count($memberssort)]['name']               = $member->LocalStockMember->Company->name;
                $loss[$kkf  + count($memberssort)]['mem_id']               = $member->LocalStockMember->Company->id;
                $loss[$kkf  + count($memberssort)]['kind']               = 'company';
            }
            // if product
            if($member->LocalStockMember->LocalStockproducts != null){
                $loss[$kkf  + count($memberssort)]['name']              = $member->LocalStockMember->LocalStockproducts->name;
                $loss[$kkf  + count($memberssort)]['mem_id']               = $member->LocalStockMember->LocalStockproducts->id;
                $loss[$kkf  + count($memberssort)]['kind']               = 'product';
            }

            // last movement
            foreach ($member->LocalStockDetials as $k => $value)
            {
                // price
                if($value->LocalStockColumns->type == 'price' ){
                    $loss[$kkf  + count($memberssort)]["price"]         = $value->value;
                }
                // change
                if($value->LocalStockColumns->type == 'change' ){
                    $loss[$kkf  + count($memberssort)]["change"]         = (string) round($value->value, 2);
                    $loss[$kkf  + count($memberssort)]["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                }
                // if  null
                if($value->LocalStockColumns->type == null ){
                    $loss[$kkf  + count($memberssort)]["new_columns"][]                 = $value->value;

                }
                // state
                if($value->LocalStockColumns->type == 'state' ){
                    if($value->value === 'up' ){
                        $loss[$kkf  + count($memberssort)]["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($value->value === 'down' ){
                        $loss[$kkf  + count($memberssort)]["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($value->value === 'equal' ){
                        $loss[$kkf  + count($memberssort)]["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                    }
                }



            }
            $loss[$kkf  + count($memberssort)]["type"]                 = 0;

        }

        $list['members']               = $loss;

        if(count($moves) == 0 && count($memberssort) == 0){
            $list['members'] = [];
        }

    }

    protected function fodderWithoutAuth($id,$comp_id,$fod_id,$date)
    {

        $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();

        # check sub exist
        if(!$sub)
        {
            $msg = 'sub not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();



        $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
        $foooooo = [];
        // feeds
        foreach ($feeds as $kf => $fed)
        {

            $foooooo['name']               = $fed->name;

            $foooooo['id']              = $fed->id;
            $list['feeds'][] =$foooooo;

        }

        $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');

        $cooooo = [];
        // feeds
        foreach ($fodss as $kcf => $fecd)
        {

            $cooooo['name']               = $fecd->Company->name;


            $cooooo['id']              = $fecd->Company->id;
            $list['companies'][] =$cooooo;

        }

        if(is_null($fod_id)){
            $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
        }else{
            $fod = Stock_Feeds::where('id',$fod_id)->first();
        }

        if(is_null($comp_id)){
            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
            if($fod_id){
                $memberssort->where('fodder_id',$fod_id);
            }
            $memberssort =$memberssort->latest()->get()->unique('stock_id');

            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');

        }else{
            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
            if($fod_id){
                $memberssort->where('fodder_id',$fod_id);
            }
            $memberssort =$memberssort->latest()->get()->unique('stock_id');

            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->where('company_id',$comp_id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');

        }
        if(count($adss) == 0){
            $list['banners'] = [];
        }else{
            foreach ($adss as $key => $ad)
            {
                $list['banners'][$key]['id']          = $ad->id;
                $list['banners'][$key]['link']        = $ad->link;
                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


            }

        }


        if(count($logos) == 0){
            $list['logos'] = [];
        }else{
            foreach ($logos as $key => $logo)
            {
                $list['logos'][$key]['id']          = $logo->id;
                $list['logos'][$key]['link']        = $logo->link;
                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


            }
        }


        $list['section_type']   =  $sub->Section->type;


        $list['columns'][]["title"]   = ' الاسم';
        $list['columns'][]["title"]   = 'الصنف';
        $list['columns'][]["title"]   = 'السعر';
        $list['columns'][]["title"]   = 'مقدار التغير';
        $list['columns'][]["title"]   = 'اتجاه السعر';

        // members
        foreach ($memberssort as $kfds => $member)
        {

            $ros['name']               = $member->Company->name;
            $ros['mem_id']               = $member->Company->id;

            $ros['feed']              = $member->StockFeed->name;

            // last movement


            $ros["price"]         = $member->price;

            $ros["change"]         = (string) round($member->change, 2);
            $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');


            if($member->status === 'up' ){
                $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
            }
            if($member->status === 'down' ){
                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
            }
            if($member->status === 'equal' ){
                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
            }

            $ros['type']              = 1;


            $list['members'][]                = $ros;

        }

        // members
        foreach ($members as $kfd => $member)
        {

            $ro['name']               = $member->Company->name;

            $ro['mem_id']               = $member->Company->id;

            $ro['feed']              = $member->StockFeed->name;

            // last movement


            $ro["price"]         = $member->price;

            $ro["change"]         = (string) round($member->change, 2);
            $ro["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

            if($member->status === 'up' ){
                $ro["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
            }
            if($member->status === 'down' ){
                $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
            }
            if($member->status === 'equal' ){
                $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
            }

            $ro['type']              = 0;
            $list['members'][]                = $ro;
        }

        if(count($members) == 0 && count($memberssort) == 0){
            $list['members'] = [];
        }

    }

    public $ads_data = [];
    public function refactory(Request $request)
    {

        $id = Input::get("id");
        $type = Input::get("type");
        $date = Input::get("date");
        $fod_id = Input::get("fod_id");
        $comp_id = Input::get("comp_id");



        $list = [];
        $ro = [];
        $ros = [];
        $losss = [];
        $loss = [];
        $list['columns'] = [];

        $nowm =date('Y-m-d'); //Carbon::today();

        if(isset($date) && $date < Carbon::now()->subDays(7)){
            return $this->ErrorMsg('not membership');
        }

        if(is_null($date) || $date == $nowm){

            if($type == 'local'){

                $this->localWithoutDate($id);

            }elseif($type == 'fodder'){

                $this->fodderWithoutDate($id,$comp_id,$fod_id);
            }


        }else{
            # check recomndation system
            if(!is_null($request->header('Authorization')))
            {
                $token = $request->header('Authorization');
                $token = explode(' ',$token);
                if(count( $token) == 2)
                {
                    // return 'not null';
                    $customer = Customer::where('api_token',$token[1])->first();

                    if(!$customer) { return response()->json(['message'  => null, 'error'    => 'unauthorization',],401);}

                    if($customer->memb == '1')
                    {
                        # start
                        if($type == 'local'){
                            $this->localWithAuthMember($id,$date);
                        }elseif($type == 'fodder'){

                            $this->FodderWithAuthMember($id,$fod_id,$comp_id,$date);
                        }
                    }else{
                        # start
                        if($type == 'local'){
                            $this->localWithAuthWithoutMemb($id,$date);
                        }elseif($type == 'fodder'){
                           echo $this->fodderWithAuthWithoutMemb($id,$comp_id,$fod_id,$date);
                        }
                    }

                }else{
                    # start
                    if($type == 'local'){
                        $this->localWithAuthOutCount($id,$date);
                    }elseif($type == 'fodder'){
                        $this->fodderWithAuthOutCount($id,$comp_id,$fod_id,$date);
                    }
                }
            }else{
                # start //new
//                $this->localWithoutAuth($id,$date);
//                $this->fodderWithoutAuth($id,$comp_id,$fod_id,$date);

                if($type == 'local'){
                    $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();
                    $type = 'localstock';
                }elseif($type == 'fodder'){
                    $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();
                    $type = 'fodderstock';
                }
                if(!$sub){ return $this->ErrorMsg('sub not found');}

                $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type',$type)->pluck('ads_id')->toArray();

                $system_ads = System_Ads::select('id','link','image','type')->where('sub','1')->whereIn('id',$page)->where('status','1')->whereIn('type',['banner','logo'])->inRandomOrder()->get();
                $this->ads_data['banners'] = [];
                $this->ads_data['logos'] = [];
                $system_ads->map(function ($ad) {
                    $ad['image'] = url('uploads/full_images/'.$ad->image);
                    $ad->type == 'banner' ? $this->ads_data['banners'][] = $ad : $this->ads_data['logos'][] = $ad;
                    unset($ad->type);
                    return $ad;
                });

                $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();

                return $this->ads_data;
            }

        }


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }






    public function showmembers(Request $request)
    {

        $id = Input::get("id");
        $type = Input::get("type");
        $date = Input::get("date");
        $fod_id = Input::get("fod_id");
        $comp_id = Input::get("comp_id");



        $list = [];
        $ro = [];
        $ros = [];
        $losss = [];
        $loss = [];
        $list['columns'] = [];

        $nowm =date('Y-m-d'); //Carbon::today();
        if(is_null($date) || $date == $nowm){

            if($type == 'local'){

                $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();

                $sub->view_count = $sub->view_count + 1;
                # check sub exist
                if(!$sub)
                {
                    $msg = 'sub not found';
                    return response()->json([
                        'status'   => '0',
                        'message'  => null,
                        'error'    => $msg,
                    ],400);
                }

                $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

                $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->get();

                $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->get();


                $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


                $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

                $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->get();



                if(count($adss) == 0){
                    $list['banners'] = [];
                }else{
                    foreach ($adss as $key => $ad)
                    {
                        $list['banners'][$key]['id']          = $ad->id;
                        $list['banners'][$key]['link']        = $ad->link;
                        $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);
                    }
                }

                if(count($logos) == 0){
                    $list['logos'] = [];
                }else{
                    foreach ($logos as $key => $logo)
                    {
                        $list['logos'][$key]['id']          = $logo->id;
                        $list['logos'][$key]['link']        = $logo->link;
                        $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);
                    }
                }

                // columns
                $list['columns'][]["title"]   = "الاسم";
                foreach ($sub->LocalStockColumns as $key => $col)
                {
                    if($col->type == 'price' ){
                        $list['columns'][]["title"]   = $col->name;
                    }

                    if($col->type == 'change' ){
                        $list['columns'][]["title"]   = $col->name;
                    }

                    if($col->type == null ){
                        $list['columns'][]["title"]   = $col->name;
                    }
                }
                $list['columns'][]["title"]   = 'اتجاه السعر';

                // memberssort
                foreach ($memberssort as $kn => $member)
                {
                    // if company
                    if($member->Company != null){
                        $loss[$kn]['name']               = $member->Company->name;
                        $loss[$kn]['mem_id']               = $member->Company->id;
                        $loss[$kn]['kind']               = 'company';
                    }
                    // if product
                    if($member->LocalStockproducts != null){
                        $loss[$kn]['name']              = $member->LocalStockproducts->name;
                        $loss[$kn]['mem_id']              = $member->LocalStockproducts->id;
                        $loss[$kn]['kind']               = 'product';
                    }
                    // last movement
                    foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
                    {
                        // price
                        if($value->LocalStockColumns->type == 'price' ){
                            $loss[$kn]["price"]         = $value->value;
                        }
                        // change
                        if($value->LocalStockColumns->type == 'change' ){
                            $loss[$kn]["change"]         = (string) round($value->value, 2);
                            $loss[$kn]["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                        }
                        // if  null
                        // if  null
                        if($value->LocalStockColumns->type == null ){
                            $loss[$kn]["new_columns"][]                 = $value->value;
                        }

                        // state
                        if($value->LocalStockColumns->type == 'state' ){
                            if($value->value === 'up' ){
                                $loss[$kn]["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                            }
                            if($value->value === 'down' ){
                                $loss[$kn]["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                            }
                            if($value->value === 'equal' ){
                                $loss[$kn]["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                            }
                        }

                    }

                    $loss[$kn]["type"]                 = 1;
                }

                // members
                foreach ($members as $kk => $member)
                {
                    // if company
                    if($member->Company != null){
                        $loss[$kk + count($memberssort)]['name']               = $member->Company->name;
                        $loss[$kk + count($memberssort)]['mem_id']               = $member->Company->id;
                        $loss[$kk + count($memberssort)]['kind']               = 'company';
                    }
                    // if product
                    if($member->LocalStockproducts != null){
                        $loss[$kk + count($memberssort)]['name']              = $member->LocalStockproducts->name;

                        $loss[$kk + count($memberssort)]['mem_id']              = $member->LocalStockproducts->id;
                        $loss[$kk + count($memberssort)]['kind']               = 'product';
                    }

                    // last movement
                    foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
                    {
                        // price
                        if($value->LocalStockColumns->type == 'price' ){
                            $loss[$kk + count($memberssort)]["price"]         = $value->value;
                        }
                        // change
                        if($value->LocalStockColumns->type == 'change' ){
                            // $loss[$kk + count($memberssort)]["change"]         = round($value->value, 2) . Date::parse($value->created_at)->format('H:i / Y-m-d');
                            $loss[$kk + count($memberssort)]["change"]         =(string) round($value->value, 2);
                            $loss[$kk + count($memberssort)]["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                        }
                        // if  null
                        // if  null
                        if($value->LocalStockColumns->type == null ){
                            $loss[$kk + count($memberssort)]["new_columns"][]                 = $value->value;

                        }

                        // state
                        if($value->LocalStockColumns->type == 'state' ){
                            if($value->value === 'up' ){
                                $loss[$kk + count($memberssort)]["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                            }
                            if($value->value === 'down' ){
                                $loss[$kk + count($memberssort)]["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                            }
                            if($value->value === 'equal' ){
                                $loss[$kk + count($memberssort)]["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                            }
                        }
                    }
                    $loss[$kk + count($memberssort)]["type"]                 = 0;
                }

                $list['members']                 = $loss;

                if(count($members) == 0 && count($memberssort) == 0){
                    $list['members'] = [];
                }

            }elseif($type == 'fodder'){

                $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();

                # check sub exist
                if(!$sub)
                {
                    $msg = 'sub not found';
                    return response()->json([
                        'status'   => '0',
                        'message'  => null,
                        'error'    => $msg,
                    ],400);
                }

                $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

                $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->get();

                $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->get();


                $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();

                $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
                $selected_feeds = Stock_Feeds::where('section_id',$sub->id)->where('id',$fod_id)->first();


                $foooooo = [];
                // $fooooo = [];
                # feeds
                if($selected_feeds)
                {
                    $foooooo['name']     = $selected_feeds->name;
                    $foooooo['id']       = $selected_feeds->id;
                    $list['feeds'][]     = $foooooo;
                }
                // feeds
                foreach ($feeds as $kf => $fed)
                {
                    $foooooo['name']               = $fed->name;
                    $foooooo['id']              = $fed->id;
                    $list['feeds'][] =$foooooo;
                }

                $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');
                $selected_company = Company::where('id',$comp_id)->first();

                $cooooo = [];
                if($selected_company)
                {
                    $cooooo['name']    = $selected_company->name;
                    $cooooo['id']      = $selected_company->id;
                    $list['companies'][] =$cooooo;
                }
                // feeds
                foreach ($fodss as $kcf => $fecd)
                {
                    if($comp_id != $fecd->Company->id)
                    {
                        $cooooo['name']           = $fecd->Company->name;
                        $cooooo['id']              = $fecd->Company->id;
                        $list['companies'][] =$cooooo;
                    }
                }

                if(is_null($fod_id)){
                    $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
                }else{
                    $fod = Stock_Feeds::where('id',$fod_id)->where('section_id',$sub->id)->first();
                }

                $ads[] = $comp_id;

                if(is_null($comp_id))
                {
                    $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
                    if($fod_id){
                        $memberssort->where('fodder_id',$fod_id);
                    }
                    $memberssort = $memberssort->latest()->get()->unique('stock_id');
                    $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->latest()->get()->unique('stock_id');
                }else{
                    $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
                    if($fod_id){
                        $memberssort->where('fodder_id',$fod_id);
                    }
                    $memberssort = $memberssort->latest()->get()->unique('stock_id');
                    $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('company_id',$comp_id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->latest()->get()->unique('stock_id');
                }



                if(count($adss) == 0){
                    $list['banners'] = [];
                }else{
                    foreach ($adss as $key => $ad)
                    {
                        $list['banners'][$key]['id']          = $ad->id;
                        $list['banners'][$key]['link']        = $ad->link;
                        $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


                    }

                }


                if(count($logos) == 0){
                    $list['logos'] = [];
                }else{
                    foreach ($logos as $key => $logo)
                    {
                        $list['logos'][$key]['id']          = $logo->id;
                        $list['logos'][$key]['link']        = $logo->link;
                        $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


                    }
                }


                $list['section_type']   =  $sub->Section->type;


                $list['columns'][]["title"]   = ' الاسم';
                $list['columns'][]["title"]   = 'الصنف';
                $list['columns'][]["title"]   = 'السعر';
                $list['columns'][]["title"]   = 'مقدار التغير';
                $list['columns'][]["title"]   = 'اتجاه السعر';

                // members
                foreach ($memberssort as $kfds => $member)
                {

                    $ros['name']               = $member->Company->name;
                    $ros['mem_id']               = $member->Company->id;

                    $ros['feed']              = $member->StockFeed->name;

                    // last movement


                    $ros["price"]         = $member->price;

                    $ros["change"]         = (string) round($member->change, 2);
                    $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                    if($member->status === 'up' ){
                        $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($member->status === 'down' ){
                        $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($member->status === 'equal' ){
                        $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');

                    }
                    $ros['type']              = 1;
                    $list['members'][]                = $ros;

                }
                // return $ros;
                // members
                foreach ($members as $kfd => $mem)
                {

                    $ro['name']               = $mem->Company->name;
                    $ro['mem_id']               = $mem->Company->id;
                    $ro['feed']              = $mem->StockFeed->name;

                    // last movement


                    $ro["price"]         = $mem->price;

                    $ro["change"]         = (string) round($mem->change, 2);
                    $ro["change_date"]         = Date::parse($mem->created_at)->format('H:i / Y-m-d');


                    if($mem->status === 'up' ){
                        $ro["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                    }
                    if($mem->status === 'down' ){
                        $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                    }
                    if($mem->status === 'equal' ){
                        $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                    }
                    $ro['type']              = 0;
                    $list['members'][]                = $ro;
                }
                // return $ro;
                if(count($members) == 0 && count($memberssort) == 0){
                    $list['members'] = [];
                }
            }


        }else{
            # check recomndation system
            if(!is_null($request->header('Authorization')))
            {

                $token = $request->header('Authorization');
                $token = explode(' ',$token);
                if(count( $token) == 2)
                {


                    // return 'not null';
                    $customer = Customer::where('api_token',$token[1])->first();

                    if(!$customer)
                    {
                        $msg = 'unauthorization';
                        return response()->json([
                            'message'  => null,
                            'error'    => $msg,
                        ],401);
                    }

                    if($customer->memb == '1')
                    {
                        # start
                        if($type == 'local'){

                            $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();




                            # check sub exist
                            if(!$sub)
                            {
                                $msg = 'sub not found';
                                return response()->json([
                                    'status'   => '0',
                                    'message'  => null,
                                    'error'    => $msg,
                                ],400);
                            }

                            $sub->view_count = $sub->view_count + 1;

                            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

                            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                            $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


                            $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

                            $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->where('status',1)->pluck('id')->toArray();

                            $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members)->whereDate('created_at',$date)->latest()->get()->unique('member_id');

                            if(count($adss) == 0){
                                $list['banners'] = [];
                            }else{
                                foreach ($adss as $key => $ad)
                                {
                                    $list['banners'][$key]['id']          = $ad->id;
                                    $list['banners'][$key]['link']        = $ad->link;
                                    $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


                                }

                            }


                            if(count($logos) == 0){
                                $list['logos'] = [];
                            }else{
                                foreach ($logos as $key => $logo)
                                {
                                    $list['logos'][$key]['id']          = $logo->id;
                                    $list['logos'][$key]['link']        = $logo->link;
                                    $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


                                }
                            }



                            // columns
                            $list['columns'][]["title"]   = "الاسم";
                            foreach ($sub->LocalStockColumns as $key => $col)
                            {
                                if($col->type == 'price' ){
                                    $list['columns'][]["title"]   = $col->name;
                                }

                                if($col->type == 'change' ){
                                    $list['columns'][]["title"]   = $col->name;
                                }

                                if($col->type == null ){
                                    $list['columns'][]["title"]   = $col->name;
                                }



                            }
                            $list['columns'][]["title"]   = 'اتجاه السعر';

                            // memberssort
                            $push = [];
                            foreach ($memberssort as $kno => $member)
                            {
                                // if company
                                if($member->Company != null){
                                    $loss['name']               = $member->Company->name;
                                    $loss['mem_id']               = $member->Company->id;
                                    $loss['kind']               = 'company';

                                }
                                // if product
                                if($member->LocalStockproducts != null){
                                    $loss['name']              = $member->LocalStockproducts->name;
                                    $loss['mem_id']               = $member->LocalStockproducts->id;
                                    $loss['kind']               = 'product';
                                }

                                // last movement
                                foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
                                {
                                    // price
                                    if($value->LocalStockColumns->type == 'price' ){
                                        $loss["price"]         = $value->value;
                                    }
                                    // change
                                    if($value->LocalStockColumns->type == 'change' ){
                                        $loss["change"]         = (string) round($value->value, 2);
                                        $loss["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                                    }
                                    // if  null
                                    if($value->LocalStockColumns->type == null ){
                                        $loss["new_columns"][]                 = $value->value;

                                    }

                                    // state
                                    if($value->LocalStockColumns->type == 'state' ){
                                        if($value->value === 'up' ){
                                            $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                        }
                                        if($value->value === 'down' ){
                                            $loss["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                        }
                                        if($value->value === 'equal' ){
                                            $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                        }
                                    }


                                }

                                $loss["type"]                 = 1;
                                $push[] = $loss;


                            }



                            // members
                            foreach ($moves as $kko => $member)
                            {
                                // if company
                                if($member->LocalStockMember->Company != null){
                                    $loss['name']               = $member->LocalStockMember->Company->name;
                                    $loss['mem_id']               = $member->LocalStockMember->Company->id;
                                    $loss['kind']               = 'company';
                                }
                                // if product
                                if($member->LocalStockMember->LocalStockproducts != null){
                                    $loss['name']              = $member->LocalStockMember->LocalStockproducts->name;
                                    $loss['mem_id']               = $member->LocalStockMember->LocalStockproducts->id;
                                    $loss['kind']               = 'product';
                                }

                                // last movement
                                foreach ($member->LocalStockDetials as $koo => $value)
                                {
                                    // price
                                    if($value->LocalStockColumns->type == 'price' ){
                                        $loss["price"]         = $value->value;
                                    }
                                    // change
                                    if($value->LocalStockColumns->type == 'change' ){
                                        // $loss["change"]         = round($value->value, 2)  . "  /". Date::parse($value->created_at)->format('H:i / Y-m-d');
                                        $loss["change"]         = (string) round($value->value, 2);
                                        $loss["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');
                                    }
                                    // if  null
                                    if($value->LocalStockColumns->type == null ){
                                        $loss["new_columns"]                 = [$value->value];

                                    }

                                    // state
                                    if($value->LocalStockColumns->type == 'state' ){
                                        if($value->value === 'up' ){
                                            $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                        }
                                        if($value->value === 'down' ){
                                            $loss["statistics"]       = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                        }
                                        if($value->value === 'equal' ){
                                            $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                        }
                                    }



                                }

                                $loss["type"]                 = 0;


                                $push[] = $loss;
                            }

                            $list['members']               = $push;
                            if(count($moves) == 0 && count($memberssort) == 0){
                                $list['members'] = [];
                            }

                        }elseif($type == 'fodder'){

                            $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();


                            # check sub exist
                            if(!$sub)
                            {
                                $msg = 'sub not found';
                                return response()->json([
                                    'status'   => '0',
                                    'message'  => null,
                                    'error'    => $msg,
                                ],400);
                            }

                            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

                            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                            $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();



                            $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
                            $selected_feeds = Stock_Feeds::where('section_id',$sub->id)->where('id',$fod_id)->first();



                            $foooooo = [];
                            // $fooooo = [];
                            # feeds
                            if($selected_feeds)
                            {
                                $foooooo['name']     = $selected_feeds->name;
                                $foooooo['id']       = $selected_feeds->id;
                                $list['feeds'][]     = $foooooo;
                            }
                            foreach ($feeds as $kf => $fed)
                            {

                                $foooooo['name']               = $fed->name;

                                $foooooo['id']              = $fed->id;
                                $list['feeds'][] =$foooooo;

                            }

                            $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');
                            $selected_company = Company::where('id',$comp_id)->first();

                            $cooooo = [];
                            if($selected_company)
                            {
                                $cooooo['name']    = $selected_company->name;
                                $cooooo['id']      = $selected_company->id;
                                $list['companies'][] =$cooooo;
                            }
                            // feeds
                            foreach ($fodss as $kcf => $fecd)
                            {

                                $cooooo['name']               = $fecd->Company->name;


                                $cooooo['id']              = $fecd->Company->id;
                                $list['companies'][] =$cooooo;

                            }

                            if(is_null($fod_id)){
                                $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
                            }else{
                                $fod = Stock_Feeds::where('id',$fod_id)->first();
                            }

//                                $ads[] = $comp_id;
                            if(is_null($comp_id)){
                                $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->whereDate('created_at',$date)->with('Section','StockFeed','Company','FodderStock');
                                if($fod_id){
                                    $memberssort->where('fodder_id',$fod_id);
                                }

                                $memberssort = $memberssort->latest()->get()->unique('stock_id');

                                $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');


                            }else{
                                $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->whereDate('created_at',$date)->with('Section','StockFeed','Company','FodderStock');

                                $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('company_id',$comp_id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date);

                                if($fod_id){
                                    $memberssort->where('fodder_id',$fod_id);
                                    $members->where('fodder_id',$fod_id);
                                }
                                $memberssort = $memberssort->latest()->get()->unique('stock_id');

                                $members = $members->latest()->get()->unique('stock_id');
                            }

                            $ms = [];
                            foreach ($memberssort as $k => $v){
                                $ms[] = $v->id;
                            }
                            if(count($adss) == 0){
                                $list['banners'] = [];
                            }else{
                                foreach ($adss as $key => $ad)
                                {
                                    $list['banners'][$key]['id']          = $ad->id;
                                    $list['banners'][$key]['link']        = $ad->link;
                                    $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);
                                }
                            }

                            if(count($logos) == 0){
                                $list['logos'] = [];
                            }else{
                                foreach ($logos as $key => $logo)
                                {
                                    $list['logos'][$key]['id']          = $logo->id;
                                    $list['logos'][$key]['link']        = $logo->link;
                                    $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);
                                }
                            }


                            $list['section_type']   =  $sub->Section->type;

                            $list['columns'][]["title"]   = ' الاسم';
                            $list['columns'][]["title"]   = 'الصنف';
                            $list['columns'][]["title"]   = 'السعر';
                            $list['columns'][]["title"]   = 'مقدار التغير';
                            $list['columns'][]["title"]   = 'اتجاه السعر';

                            // members
                            foreach ($memberssort as $kfds => $member)
                            {

                                $ros['name']               = $member->Company->name;
                                $ros['mem_id']               = $member->Company->id;
                                $ros['feed']              = $member->StockFeed->name;

                                // last movement

                                $ros["price"]         = $member->price;
                                $ros["change"]         = (string) round($member->change, 2);
                                $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');


                                if($member->status === 'up' ){
                                    $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                }
                                if($member->status === 'down' ){
                                    $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                }
                                if($member->status === 'equal' ){
                                    $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                }
                                $ros['type']              = 1;
                                $list['members'][]                = $ros;
                            }

                            // members
                            foreach ($members as $kfd => $member)
                            {
                                if(!in_array($member->id ,$ms)) {
                                    $ro['name'] = $member->Company->name;
                                    $ro['mem_id'] = $member->Company->id;
                                    $ro['feed'] = $member->StockFeed->name;

                                    // last movement
                                    $ro["price"] = $member->price;
                                    $ro["change"] = (string)round($member->change, 2);
                                    $ro["change_date"] = Date::parse($member->created_at)->format('H:i / Y-m-d');


                                    if ($member->status === 'up') {
                                        $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                    }
                                    if ($member->status === 'down') {
                                        $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                    }
                                    if ($member->status === 'equal') {
                                        $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                    }
                                    $ro['type'] = 0;
                                    $list['members'][] = $ro;
                                }
                            }

                            if(count($members) == 0 && count($memberssort) == 0){
                                $list['members'] = [];
                            }
                        }

                    }else{
                        // return 1;
                        if($date < Carbon::now()->subDays(7)){
                            $msg = ' not membership';
                            return response()->json([
                                'message'  => null,
                                'error'    => $msg,
                            ],400);
                        }else{
                            # start

                            if($type == 'local'){

                                $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();

                                $sub->view_count = $sub->view_count + 1;
                                # check sub exist
                                if(!$sub)
                                {
                                    $msg = 'sub not found';
                                    return response()->json([
                                        'status'   => '0',
                                        'message'  => null,
                                        'error'    => $msg,
                                    ],400);
                                }

                                $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

                                $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                                $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                                $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                                $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


                                $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

                                $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->where('status',1)->pluck('id')->toArray();

                                $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members)->whereDate('created_at',$date)->latest()->get()->unique('member_id');

                                if(count($adss) == 0){
                                    $list['banners'] = [];
                                }else{
                                    foreach ($adss as $key => $ad)
                                    {
                                        $list['banners'][$key]['id']          = $ad->id;
                                        $list['banners'][$key]['link']        = $ad->link;
                                        $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);
                                    }
                                }


                                if(count($logos) == 0){
                                    $list['logos'] = [];
                                }else{
                                    foreach ($logos as $key => $logo)
                                    {
                                        $list['logos'][$key]['id']          = $logo->id;
                                        $list['logos'][$key]['link']        = $logo->link;
                                        $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);
                                    }
                                }



                                // columns
                                $list['columns'][]["title"]   = "الاسم";
                                foreach ($sub->LocalStockColumns as $key => $col)
                                {
                                    if($col->type == 'price' ){
                                        $list['columns'][]["title"]   = $col->name;
                                    }

                                    if($col->type == 'change' ){
                                        $list['columns'][]["title"]   = $col->name;
                                    }

                                    if($col->type == null ){
                                        $list['columns'][]["title"]   = $col->name;
                                    }
                                }
                                $list['columns'][]["title"]   = 'اتجاه السعر';

                                // memberssort
                                // memberssort
                                foreach ($memberssort as $knt => $member)
                                {
                                    // if company
                                    if($member->Company != null){
                                        $loss[$knt]['name']               = $member->Company->name;
                                        $loss[$knt]['mem_id']               = $member->Company->id;
                                        $loss[$knt]['kind']               = 'company';
                                    }
                                    // if product
                                    if($member->LocalStockproducts != null){
                                        $loss[$knt]['name']              = $member->LocalStockproducts->name;
                                        $loss[$knt]['mem_id']               = $member->LocalStockproducts->id;
                                        $loss[$knt]['kind']               = 'product';
                                    }

                                    // last movement
                                    foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
                                    {
                                        // price
                                        if($value->LocalStockColumns->type == 'price' ){
                                            $loss[$knt]["price"]         = $value->value;
                                        }
                                        // change
                                        if($value->LocalStockColumns->type == 'change' ){
                                            //  $loss[$knt]["change"]         = round($value->value, 2)  . "  /".  Date::parse($value->created_at)->format('H:i / Y-m-d');
                                            $loss[$knt]["change"]         = (string) round($value->value, 2);
                                            $loss[$knt]["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');
                                        }
                                        // if  null
                                        if($value->LocalStockColumns->type == null ){
                                            $loss[$knt]["new_columns"][]                 = $value->value;

                                        }

                                        // state
                                        if($value->LocalStockColumns->type == 'state' ){
                                            if($value->value === 'up' ){
                                                $loss[$knt]["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                            }
                                            if($value->value === 'down' ){
                                                $loss[$knt]["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                            }
                                            if($value->value === 'equal' ){
                                                $loss[$knt]["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                            }
                                        }
                                    }
                                    $loss[$knt]["type"]                 = 1;
                                }


                                $push = [];
                                // members
                                foreach ($moves as $kkt => $member)
                                {
                                    // if company
                                    if($member->LocalStockMember->Company != null){
                                        $loss['name']               = $member->LocalStockMember->Company->name;
                                        $loss['mem_id']               = $member->LocalStockMember->Company->id;
                                        $loss['kind']               = 'company';
                                    }
                                    // if product
                                    if($member->LocalStockMember->LocalStockproducts != null){
                                        $loss['name']              = $member->LocalStockMember->LocalStockproducts->name;
                                        $loss['mem_id']               = $member->LocalStockMember->LocalStockproducts->id;
                                        $loss['kind']               = 'product';
                                    }

                                    // last movement
                                    foreach ($member->LocalStockDetials as $k => $value)
                                    {
                                        // price
                                        if($value->LocalStockColumns->type == 'price' ){
                                            $loss["price"]         = $value->value;
                                        }
                                        // change
                                        if($value->LocalStockColumns->type == 'change' ){
                                            $loss["change"]         = (string) round($value->value, 2);

                                            $loss["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                                        }
                                        // if  null
                                        if($value->LocalStockColumns->type == null ){
                                            $loss["new_columns"]             = [$value->value];

                                        }

                                        // state
                                        if($value->LocalStockColumns->type == 'state' ){
                                            if($value->value === 'up' ){
                                                $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                            }
                                            if($value->value === 'down' ){
                                                $loss["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                            }
                                            if($value->value === 'equal' ){
                                                $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                            }
                                        }
                                    }
                                    $loss["type"]                 = 0;

                                    $push[] =  $loss;

                                }

                                $list['members']                = $push;


                                if(count($moves) == 0 && count($memberssort) == 0){
                                    $list['members'] = [];
                                }

                            }elseif($type == 'fodder'){

                                $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();


                                # check sub exist
                                if(!$sub)
                                {
                                    $msg = 'sub not found';
                                    return response()->json([
                                        'status'   => '0',
                                        'message'  => null,
                                        'error'    => $msg,
                                    ],400);
                                }

                                $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

                                $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                                $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                                $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                                $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();



                                $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
                                $selected_feeds = Stock_Feeds::where('section_id',$sub->id)->where('id',$fod_id)->first();



                                $foooooo = [];
                                // $fooooo = [];
                                # feeds
                                if($selected_feeds)
                                {
                                    $foooooo['name']     = $selected_feeds->name;
                                    $foooooo['id']       = $selected_feeds->id;
                                    $list['feeds'][]     = $foooooo;
                                }
                                // feeds
                                foreach ($feeds as $kf => $fed)
                                {

                                    $foooooo['name']               = $fed->name;

                                    $foooooo['id']              = $fed->id;
                                    $list['feeds'][] =$foooooo;

                                }

                                $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');

                                $selected_company = Company::where('id',$comp_id)->first();

                                $cooooo = [];
                                if($selected_company)
                                {
                                    $cooooo['name']    = $selected_company->name;
                                    $cooooo['id']      = $selected_company->id;
                                    $list['companies'][] =$cooooo;
                                }
                                // feeds
                                foreach ($fodss as $kcf => $fecd)
                                {

                                    $cooooo['name']               = $fecd->Company->name;
                                    $cooooo['id']              = $fecd->Company->id;
                                    $list['companies'][] =$cooooo;

                                }

                                if(is_null($fod_id)){
                                    $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
                                }else{
                                    $fod = Stock_Feeds::where('id',$fod_id)->first();
                                }

                                if(is_null($comp_id)){
                                    $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->whereDate('created_at',$date)->with('Section','StockFeed','Company','FodderStock');
                                    if($fod_id){
                                        $memberssort->where('fodder_id',$fod_id);
                                    }
                                    $memberssort = $memberssort->latest()->get()->unique('stock_id');

                                    $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');


                                }else{
                                    $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
                                    if($fod_id){
                                        $memberssort->where('fodder_id',$fod_id);
                                    }
                                    $memberssort = $memberssort->latest()->get()->unique('stock_id');

                                    $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->where('company_id',$comp_id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');


                                }


                                if(count($adss) == 0){
                                    $list['banners'] = [];
                                }else{
                                    foreach ($adss as $key => $ad)
                                    {
                                        $list['banners'][$key]['id']          = $ad->id;
                                        $list['banners'][$key]['link']        = $ad->link;
                                        $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


                                    }

                                }


                                if(count($logos) == 0){
                                    $list['logos'] = [];
                                }else{
                                    foreach ($logos as $key => $logo)
                                    {
                                        $list['logos'][$key]['id']          = $logo->id;
                                        $list['logos'][$key]['link']        = $logo->link;
                                        $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


                                    }
                                }


                                $list['section_type']   =  $sub->Section->type;


                                $list['columns'][]["title"]   = ' الاسم';
                                $list['columns'][]["title"]   = 'الصنف';
                                $list['columns'][]["title"]   = 'السعر';
                                $list['columns'][]["title"]   = 'مقدار التغير';
                                $list['columns'][]["title"]   = 'اتجاه السعر';

                                // members
                                foreach ($memberssort as $kfds => $member)
                                {

                                    $ros['name']               = $member->Company->name;
                                    $ros['mem_id']               = $member->Company->id;

                                    $ros['feed']              = $member->StockFeed->name;

                                    // last movement


                                    $ros["price"]         = $member->price;

                                    $ros["change"]         = (string) round($member->change, 2);
                                    $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                                    if($member->status === 'up' ){
                                        $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                    }
                                    if($member->status === 'down' ){
                                        $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                    }
                                    if($member->status === 'equal' ){
                                        $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                    }

                                    $ros['type']              = 1;

                                    $list['members'][]                = $ros;
                                }

                                // members
                                foreach ($members as $kfd => $member)
                                {

                                    $ro['name']               = $member->Company->name;
                                    $ro['mem_id']               = $member->Company->id;

                                    $ro['feed']              = $member->StockFeed->name;

                                    // last movement


                                    $ro["price"]         = $member->price;

                                    $ro["change"]         = (string) round($member->change, 2);
                                    $ro["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                                    if($member->status === 'up' ){
                                        $ro["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                    }
                                    if($member->status === 'down' ){
                                        $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                    }
                                    if($member->status === 'equal' ){
                                        $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                    }

                                    $ro['type']              = 0;
                                    $list['members'][]                = $ro;
                                }

                                if(count($members) == 0 && count($memberssort) == 0){
                                    $list['members'] = [];
                                }
                            }
                        }


                    }

                }else{/////////

                    if($date < Carbon::now()->subDays(7)){

                        $msg = ' not membership';
                        return response()->json([
                            'message'  => null,
                            'error'    => $msg,
                        ],400);
                    }else{

                        # start

                        if($type == 'local'){
                            // return 0;
                            $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();



                            $sub->view_count = $sub->view_count + 1;
                            # check sub exist
                            if(!$sub)
                            {
                                $msg = 'sub not found';
                                return response()->json([
                                    'status'   => '0',
                                    'message'  => null,
                                    'error'    => $msg,
                                ],400);
                            }

                            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

                            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                            $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


                            $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

                            $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->where('status',1)->pluck('id')->toArray();

                            $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members)->whereDate('created_at',$date)->latest()->get()->unique('member_id');

                            if(count($adss) == 0){
                                $list['banners'] = [];
                            }else{
                                foreach ($adss as $key => $ad)
                                {
                                    $list['banners'][$key]['id']          = $ad->id;
                                    $list['banners'][$key]['link']        = $ad->link;
                                    $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


                                }

                            }


                            if(count($logos) == 0){
                                $list['logos'] = [];
                            }else{
                                foreach ($logos as $key => $logo)
                                {
                                    $list['logos'][$key]['id']          = $logo->id;
                                    $list['logos'][$key]['link']        = $logo->link;
                                    $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


                                }
                            }



                            // columns
                            $list['columns'][]["title"]   = "الاسم";
                            foreach ($sub->LocalStockColumns as $key => $col)
                            {
                                if($col->type == 'price' ){
                                    $list['columns'][]["title"]   = $col->name;
                                }

                                if($col->type == 'change' ){
                                    $list['columns'][]["title"]   = $col->name;
                                }

                                if($col->type == null ){
                                    $list['columns'][]["title"]   = $col->name;
                                }



                            }
                            $list['columns'][]["title"]   = 'اتجاه السعر';

                            // memberssort
                            $push = [];
                            foreach ($memberssort as $knr => $member)
                            {

                                // if company
                                if($member->Company != null){
                                    $loss['name']               = $member->Company->name;
                                    $loss['mem_id']               = $member->Company->id;
                                    $loss['kind']               = 'company';
                                }
                                // if product
                                if($member->LocalStockproducts != null){
                                    $loss['name']              = $member->LocalStockproducts->name;
                                    $loss['mem_id']               = $member->LocalStockproducts->id;
                                    $loss['kind']               = 'product';
                                }

                                // last movement
                                foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
                                {
                                    // price
                                    if($value->LocalStockColumns->type == 'price' ){
                                        $loss["price"]         = $value->value;
                                    }
                                    // change
                                    if($value->LocalStockColumns->type == 'change' ){
                                        $loss["change"]         = (string) round($value->value, 2);
                                        $loss["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                                    }
                                    // if  null
                                    if($value->LocalStockColumns->type == null ){
                                        $loss["new_columns"][]                 = $value->value;

                                    }

                                    // state
                                    if($value->LocalStockColumns->type == 'state' ){
                                        if($value->value === 'up' ){
                                            $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                        }
                                        if($value->value === 'down' ){
                                            $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                        }
                                        if($value->value === 'equal' ){
                                            $loss["statistics"]      = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                        }
                                    }



                                }

                                $loss["type"]                 = 1;
                                $push[] = $loss;
                            }



                            // members

                            foreach ($moves as $kkr => $member)
                            {
                                // if company
                                if($member->LocalStockMember->Company != null){
                                    $loss['name']               = $member->LocalStockMember->Company->name;
                                    $loss['mem_id']               = $member->LocalStockMember->Company->id;
                                    $loss['kind']               = 'company';
                                }
                                // if product
                                if($member->LocalStockMember->LocalStockproducts != null){
                                    $loss['name']              = $member->LocalStockMember->LocalStockproducts->name;
                                    $loss['mem_id']               = $member->LocalStockMember->LocalStockproducts->id;
                                    $loss['kind']               = 'product';
                                }

                                // last movement
                                foreach ($member->LocalStockDetials as $k => $value)
                                {
                                    // price
                                    if($value->LocalStockColumns->type == 'price' ){
                                        $loss["price"]         = $value->value;
                                    }
                                    // change
                                    if($value->LocalStockColumns->type == 'change' ){

                                        // $loss["change"]         = round($value->value, 2)  . "  /". Date::parse($value->created_at)->format('H:i / Y-m-d');
                                        $loss["change"]         = (string) round($value->value, 2);
                                        $loss["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                                    }
                                    // if  null
                                    if($value->LocalStockColumns->type == null ){////////////////////////
                                        $loss["new_columns"]              = [$value->value];

                                    }



                                    // state
                                    if($value->LocalStockColumns->type == 'state' ){
                                        if($value->value === 'up' ){
                                            $loss["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                        }
                                        if($value->value === 'down' ){
                                            $loss["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                        }
                                        if($value->value === 'equal' ){
                                            $loss["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                        }
                                    }

                                }
                                $loss["type"]                 = 0;
                                $push[] = $loss;


                            }
                            $list['members']              = $push;
                            // return  $push;

                            if(count($moves) == 0 && count($memberssort) == 0){
                                $list['members'] = [];
                            }


                        }elseif($type == 'fodder'){

                            $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();


                            # check sub exist
                            if(!$sub)
                            {
                                $msg = 'sub not found';
                                return response()->json([
                                    'status'   => '0',
                                    'message'  => null,
                                    'error'    => $msg,
                                ],400);
                            }

                            $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

                            $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                            $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                            $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                            $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();



                            $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
                            $foooooo = [];
                            // feeds
                            foreach ($feeds as $kf => $fed)
                            {

                                $foooooo['name']               = $fed->name;

                                $foooooo['id']              = $fed->id;
                                $list['feeds'][] =$foooooo;

                            }

                            $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');

                            $cooooo = [];
                            // feeds
                            foreach ($fodss as $kcf => $fecd)
                            {

                                $cooooo['name']               = $fecd->Company->name;


                                $cooooo['id']              = $fecd->Company->id;
                                $list['companies'][] =$cooooo;

                            }

                            if(is_null($fod_id)){
                                $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
                            }else{
                                $fod = Stock_Feeds::where('id',$fod_id)->first();
                            }
//                            $ads[] = $comp_id;
                            if(is_null($comp_id)){
                                $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date);
                                if($fod_id){
                                    $memberssort->where('fodder_id',$fod_id);
                                }
                                $memberssort  =  $memberssort->latest()->get()->unique('stock_id');

                                $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');


                            }else{
                                $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date);
                                if($fod_id){
                                    $memberssort->where('fodder_id',$fod_id);
                                }
                                $memberssort  =  $memberssort->latest()->get()->unique('stock_id');

                                $members = Fodder_Stock_Move::with('Section','StockFeed','Company','FodderStock')->where('sub_id',$sub->id)->where('company_id',$comp_id);
                                if(!is_null($fod_id)){
                                    $members->where('fodder_id',$fod->id);
                                }
                                $members = $members->whereDate('created_at',$date)->latest()->get()->unique('stock_id');
                            }

                            $ms = [];
                            foreach ($memberssort as $k => $v){
                                $ms[] = $v->id;
                            }

                            if(count($adss) == 0){
                                $list['banners'] = [];
                            }else{
                                foreach ($adss as $key => $ad)
                                {
                                    $list['banners'][$key]['id']          = $ad->id;
                                    $list['banners'][$key]['link']        = $ad->link;
                                    $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


                                }

                            }


                            if(count($logos) == 0){
                                $list['logos'] = [];
                            }else{
                                foreach ($logos as $key => $logo)
                                {
                                    $list['logos'][$key]['id']          = $logo->id;
                                    $list['logos'][$key]['link']        = $logo->link;
                                    $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


                                }
                            }


                            $list['section_type']   =  $sub->Section->type;


                            $list['columns'][]["title"]   = ' الاسم';
                            $list['columns'][]["title"]   = 'الصنف';
                            $list['columns'][]["title"]   = 'السعر';
                            $list['columns'][]["title"]   = 'مقدار التغير';
                            $list['columns'][]["title"]   = 'اتجاه السعر';

                            // members
                            foreach ($memberssort as $kfds => $member)
                            {

                                $ros['name']               = $member->Company->name;
                                $ros['mem_id']               = $member->Company->id;

                                $ros['feed']              = $member->StockFeed->name;

                                // last movement


                                $ros["price"]         = $member->price;

                                $ros["change"]         = (string) round($member->change, 2);
                                $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                                if($member->status === 'up' ){
                                    $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                }
                                if($member->status === 'down' ){
                                    $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                }
                                if($member->status === 'equal' ){
                                    $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                }

                                $ros['type']              = 1;
                                $list['members'][]                = $ros;
                            }

                            // members
                            foreach ($members as $kfd => $member)
                            {
                                if(!in_array($member->id, $ms)) {
                                    $ro['name'] = $member->Company->name;
                                    $ro['mem_id'] = $member->Company->id;

                                    $ro['feed'] = $member->StockFeed->name;

                                    // last movement


                                    $ro["price"] = $member->price;

                                    $ro["change"] = (string)round($member->change, 2);
                                    $ro["change_date"] = Date::parse($member->created_at)->format('H:i / Y-m-d');

                                    if ($member->status === 'up') {
                                        $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                    }
                                    if ($member->status === 'down') {
                                        $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                    }
                                    if ($member->status === 'equal') {
                                        $ro["statistics"] = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                    }

                                    $ro['type'] = 0;
                                    $list['members'][] = $ro;
                                }
                            }
                            if(count($members) == 0 && count($memberssort) == 0){
                                $list['members'] = [];
                            }
                        }
                    }

                }
            }else{
                if($date < Carbon::now()->subDays(7)){
                    $msg = ' not membership';
                    return response()->json([
                        'message'  => null,
                        'error'    => $msg,
                    ],400);
                }else{
                    # start

                    if($type == 'local'){

                        $sub = Local_Stock_Sub::with('LocalStockColumns','LocalStockMembers.Company','LocalStockMembers.LocalStockproducts')->where('id',$id)->first();



                        $sub->view_count = $sub->view_count + 1;
                        # check sub exist
                        if(!$sub)
                        {
                            $msg = 'sub not found';
                            return response()->json([
                                'status'   => '0',
                                'message'  => null,
                                'error'    => $msg,
                            ],400);
                        }

                        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','localstock')->pluck('ads_id')->toArray();

                        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();


                        $memberssort  = Local_Stock_Member::whereIn('company_id',$ads)->with('Company','LocalStockproducts')->where('section_id',$sub->id)->get();

                        $members = Local_Stock_Member::with('Company','LocalStockproducts')->whereNotIn('company_id',$ads)->where('section_id',$sub->id)->where('status',1)->pluck('id')->toArray();

                        $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members)->whereDate('created_at',$date)->latest()->get()->unique('member_id');

                        if(count($adss) == 0){
                            $list['banners'] = [];
                        }else{
                            foreach ($adss as $key => $ad)
                            {
                                $list['banners'][$key]['id']          = $ad->id;
                                $list['banners'][$key]['link']        = $ad->link;
                                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


                            }

                        }


                        if(count($logos) == 0){
                            $list['logos'] = [];
                        }else{
                            foreach ($logos as $key => $logo)
                            {
                                $list['logos'][$key]['id']          = $logo->id;
                                $list['logos'][$key]['link']        = $logo->link;
                                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


                            }
                        }



                        // columns
                        $list['columns'][]["title"]   = "الاسم";
                        foreach ($sub->LocalStockColumns as $key => $col)
                        {
                            if($col->type == 'price' ){
                                $list['columns'][]["title"]   = $col->name;
                            }

                            if($col->type == 'change' ){
                                $list['columns'][]["title"]   = $col->name;
                            }

                            if($col->type == null ){
                                $list['columns'][]["title"]   = $col->name;
                            }



                        }
                        $list['columns'][]["title"]   = 'اتجاه السعر';

                        // memberssort
                        foreach ($memberssort as $knf => $member)
                        {
                            // if company
                            if($member->Company != null){
                                $loss[$knf]['name']               = $member->Company->name;
                                $loss[$knf]['mem_id']               = $member->Company->id;

                                $loss[$knf]['kind']               = 'company';
                            }
                            // if product
                            if($member->LocalStockproducts != null){
                                $loss[$knf]['name']              = $member->LocalStockproducts->name;
                                $loss[$knf]['mem_id']               = $member->LocalStockproducts->id;

                                $loss[$knf]['kind']               = 'product';
                            }

                            // last movement
                            foreach ($member->LastMovement()->LocalStockDetials as $k => $value)
                            {
                                // price
                                if($value->LocalStockColumns->type == 'price' ){
                                    $loss[$knf]["price"]         = $value->value;
                                }
                                // change
                                if($value->LocalStockColumns->type == 'change' ){
                                    $loss[$knf]["change"]         = (string) round($value->value, 2);
                                    $loss[$knf]["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                                }
                                // if  null
                                if($value->LocalStockColumns->type == null ){
                                    $loss[$knf]["new_columns"][]              = $value->value;

                                }

                                // state
                                if($value->LocalStockColumns->type == 'state' ){
                                    if($value->value === 'up' ){
                                        $loss[$knf]["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                    }
                                    if($value->value === 'down' ){
                                        $loss[$knf]["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                    }
                                    if($value->value === 'equal' ){
                                        $loss[$knf]["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                    }
                                }


                            }

                            $loss[$knf]["type"]                 = 1;
                        }



                        // members
                        foreach ($moves as $kkf => $member)
                        {
                            // if company
                            if($member->LocalStockMember->Company != null){
                                $loss[$kkf  + count($memberssort)]['name']               = $member->LocalStockMember->Company->name;
                                $loss[$kkf  + count($memberssort)]['mem_id']               = $member->LocalStockMember->Company->id;
                                $loss[$kkf  + count($memberssort)]['kind']               = 'company';
                            }
                            // if product
                            if($member->LocalStockMember->LocalStockproducts != null){
                                $loss[$kkf  + count($memberssort)]['name']              = $member->LocalStockMember->LocalStockproducts->name;
                                $loss[$kkf  + count($memberssort)]['mem_id']               = $member->LocalStockMember->LocalStockproducts->id;
                                $loss[$kkf  + count($memberssort)]['kind']               = 'product';
                            }

                            // last movement
                            foreach ($member->LocalStockDetials as $k => $value)
                            {
                                // price
                                if($value->LocalStockColumns->type == 'price' ){
                                    $loss[$kkf  + count($memberssort)]["price"]         = $value->value;
                                }
                                // change
                                if($value->LocalStockColumns->type == 'change' ){
                                    $loss[$kkf  + count($memberssort)]["change"]         = (string) round($value->value, 2);
                                    $loss[$kkf  + count($memberssort)]["change_date"]         = Date::parse($value->created_at)->format('H:i / Y-m-d');
                                }
                                // if  null
                                if($value->LocalStockColumns->type == null ){
                                    $loss[$kkf  + count($memberssort)]["new_columns"][]                 = $value->value;

                                }
                                // state
                                if($value->LocalStockColumns->type == 'state' ){
                                    if($value->value === 'up' ){
                                        $loss[$kkf  + count($memberssort)]["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                                    }
                                    if($value->value === 'down' ){
                                        $loss[$kkf  + count($memberssort)]["statistics"]     = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                                    }
                                    if($value->value === 'equal' ){
                                        $loss[$kkf  + count($memberssort)]["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                                    }
                                }



                            }
                            $loss[$kkf  + count($memberssort)]["type"]                 = 0;

                        }

                        $list['members']               = $loss;

                        if(count($moves) == 0 && count($memberssort) == 0){
                            $list['members'] = [];
                        }

                    }elseif($type == 'fodder'){

                        $sub = Stock_Fodder_Sub::with('Section','FodderStocks','FodderStocks.Company','FodderStocks.StockFeed')->where('id',$id)->first();


                        # check sub exist
                        if(!$sub)
                        {
                            $msg = 'sub not found';
                            return response()->json([
                                'status'   => '0',
                                'message'  => null,
                                'error'    => $msg,
                            ],400);
                        }

                        $page = System_Ads_Pages::with('SystemAds')->where('sub_id',$sub->id)->where('type','fodderstock')->pluck('ads_id')->toArray();

                        $adss = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','banner')->where('status','1')->inRandomOrder()->get();

                        $logos = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','logo')->where('status','1')->inRandomOrder()->get();


                        $ads = System_Ads::where('sub','1')->whereIn('id',$page)->where('type','sort')->where('status','1')->pluck('company_id')->toArray();

                        $compsort = Company::whereIn('id',$ads)->inRandomOrder()->get();



                        $feeds = Stock_Feeds::where('section_id',$sub->id)->orderBy('fixed' , 'desc')->get();
                        $foooooo = [];
                        // feeds
                        foreach ($feeds as $kf => $fed)
                        {

                            $foooooo['name']               = $fed->name;

                            $foooooo['id']              = $fed->id;
                            $list['feeds'][] =$foooooo;

                        }

                        $fodss = Fodder_Stock::with('Section','FodderStockMoves','Company')->where('sub_id',$sub->id)->latest()->get()->unique('company_id');

                        $cooooo = [];
                        // feeds
                        foreach ($fodss as $kcf => $fecd)
                        {

                            $cooooo['name']               = $fecd->Company->name;


                            $cooooo['id']              = $fecd->Company->id;
                            $list['companies'][] =$cooooo;

                        }

                        if(is_null($fod_id)){
                            $fod = Stock_Feeds::where('section_id',$sub->id)->where('fixed','1')->first();
                        }else{
                            $fod = Stock_Feeds::where('id',$fod_id)->first();
                        }

                        if(is_null($comp_id)){
                            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
                            if($fod_id){
                                $memberssort->where('fodder_id',$fod_id);
                            }
                            $memberssort =$memberssort->latest()->get()->unique('stock_id');

                            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');

                        }else{
                            $memberssort = Fodder_Stock_Move::whereIn('company_id',$ads)->where('company_id',$comp_id)->where('sub_id',$sub->id)->with('Section','StockFeed','Company','FodderStock');
                            if($fod_id){
                                $memberssort->where('fodder_id',$fod_id);
                            }
                            $memberssort =$memberssort->latest()->get()->unique('stock_id');

                            $members = Fodder_Stock_Move::where('sub_id',$sub->id)->where('fodder_id',$fod->id)->where('company_id',$comp_id)->with('Section','StockFeed','Company','FodderStock')->whereDate('created_at',$date)->latest()->get()->unique('stock_id');

                        }
                        if(count($adss) == 0){
                            $list['banners'] = [];
                        }else{
                            foreach ($adss as $key => $ad)
                            {
                                $list['banners'][$key]['id']          = $ad->id;
                                $list['banners'][$key]['link']        = $ad->link;
                                $list['banners'][$key]['image']       = URL::to('uploads/full_images/'.$ad->image);


                            }

                        }


                        if(count($logos) == 0){
                            $list['logos'] = [];
                        }else{
                            foreach ($logos as $key => $logo)
                            {
                                $list['logos'][$key]['id']          = $logo->id;
                                $list['logos'][$key]['link']        = $logo->link;
                                $list['logos'][$key]['image']       = URL::to('uploads/full_images/'.$logo->image);


                            }
                        }


                        $list['section_type']   =  $sub->Section->type;


                        $list['columns'][]["title"]   = ' الاسم';
                        $list['columns'][]["title"]   = 'الصنف';
                        $list['columns'][]["title"]   = 'السعر';
                        $list['columns'][]["title"]   = 'مقدار التغير';
                        $list['columns'][]["title"]   = 'اتجاه السعر';

                        // members
                        foreach ($memberssort as $kfds => $member)
                        {

                            $ros['name']               = $member->Company->name;
                            $ros['mem_id']               = $member->Company->id;

                            $ros['feed']              = $member->StockFeed->name;

                            // last movement


                            $ros["price"]         = $member->price;

                            $ros["change"]         = (string) round($member->change, 2);
                            $ros["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');


                            if($member->status === 'up' ){
                                $ros["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                            }
                            if($member->status === 'down' ){
                                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                            }
                            if($member->status === 'equal' ){
                                $ros["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                            }

                            $ros['type']              = 1;


                            $list['members'][]                = $ros;

                        }

                        // members
                        foreach ($members as $kfd => $member)
                        {

                            $ro['name']               = $member->Company->name;

                            $ro['mem_id']               = $member->Company->id;

                            $ro['feed']              = $member->StockFeed->name;

                            // last movement


                            $ro["price"]         = $member->price;

                            $ro["change"]         = (string) round($member->change, 2);
                            $ro["change_date"]         = Date::parse($member->created_at)->format('H:i / Y-m-d');

                            if($member->status === 'up' ){
                                $ro["statistics"]        = URL::to('https://elkenany.com/uploads/full_images/arrows3-01.png');
                            }
                            if($member->status === 'down' ){
                                $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-02.png');
                            }
                            if($member->status === 'equal' ){
                                $ro["statistics"]    = URL::to('https://elkenany.com/uploads/full_images/arrows3-03.png');
                            }

                            $ro['type']              = 0;
                            $list['members'][]                = $ro;
                        }

                        if(count($members) == 0 && count($memberssort) == 0){
                            $list['members'] = [];
                        }
                    }
                }
            }

        }


        return response()->json([
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }








    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('localstock::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('localstock::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('localstock::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('localstock::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
