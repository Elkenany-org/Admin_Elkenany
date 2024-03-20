<?php

namespace Modules\LocalStock\Http\Services;

use App\Configuration;
use App\Noty;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Modules\FodderStock\Entities\Fodder_Stock_Move;
use Modules\FodderStock\Entities\Stock_Feeds;
use Modules\FodderStock\Transformers\TablesFooderResource;
use Modules\Guide\Entities\Company;
use Modules\LocalStock\Entities\Local_Stock_Member;
use Modules\LocalStock\Entities\Local_Stock_Movement;
use Modules\LocalStock\Entities\Local_Stock_Sections;
use Modules\LocalStock\Entities\Local_Stock_Sub;
use Modules\LocalStock\Entities\Sec_All;
use Modules\LocalStock\Transformers\TableLocalResource;
use Modules\LocalStock\Transformers\TableMemberLocalResource;


class LocalService
{
    use ApiResponse;

    public function TableProcess($request,$ads,$date)
    {
        $id = $request->input("id");
        $fod_id = $request->input("fod_id");
        $comp_id = $request->input("comp_id");

        $memberssort  = Local_Stock_Member::with('Company','LocalStockproducts')->whereIn('company_id',$ads)->where('section_id',$id);

        $members = Local_Stock_Member::whereNotIn('company_id',$ads)->where('section_id',$id)->where('status',1)->pluck('id')->toArray();

        $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns','LocalStockMember.Company','LocalStockMember.LocalStockproducts')->whereIn('member_id',$members);

        if($date){
            $moves->whereDate('created_at',$date);
            $memberssort->whereDate('created_at',$date);
        }
        $moves = $moves->latest()->get()->unique('member_id');
        $memberssort = $memberssort->get();

        $mems = TableLocalResource::collection($memberssort);
        $mem  = TableMemberLocalResource::collection($moves);

        $list = [];
        foreach ($mems as $m){
            array_push($list,$m);
        }
        foreach ($mem as $m){
            array_push($list,$m);
        }
        return $list;
    }

    public function TableLikeweb($request,$ads,$date){

        $id = $request->input("id");

        $memberssort = Local_Stock_Member::whereIn('company_id', $ads)->where('section_id', $id)->where('status', 1)->pluck('id');
        $movessort = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns', 'LocalStockMember.Company', 'LocalStockMember.LocalStockproducts')->whereIn('member_id', $memberssort);
        if ($date) {
            $movessort->whereDate('created_at', $date);
        }
        $movessort = $movessort->latest()->get()->unique('member_id');

        $movessort->map(function ($move){
            $move['type'] = 1;
            return $move;
        });


        $members = Local_Stock_Member::where('section_id', $id)->whereNotIn('company_id', $ads)->where('status', 1)->pluck('id')->toArray();
        $members_withoutComp = Local_Stock_Member::where('section_id', $id)->where('company_id', null)->where('status', 1)->pluck('id')->toArray();
        if(count($members_withoutComp) > 0){
            foreach ($members_withoutComp as $v){
                array_unshift($members,$v);
            }
        }

        $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns', 'LocalStockMember.Company', 'LocalStockMember.LocalStockproducts')->whereIn('member_id', $members);
        if ($date) {
            $moves->whereDate('created_at', $date);
        }
        $moves = $moves->latest()->get()->unique('member_id');
        $moves->map(function ($move){
            $move['type'] = 0;
            return $move;
        });


        $mems = TableMemberLocalResource::collection($movessort);
        $mem  = TableMemberLocalResource::collection($moves);

        $list = [];
        foreach ($mems as $m){
            array_push($list,$m);
        }
        foreach ($mem as $m){
            array_push($list,$m);
        }
        return $list;
    }


    public function Members($id,$ads,$date)
    {
        $members = Local_Stock_Member::where('section_id', $id)->whereNotIn('company_id', $ads)->where('status', 1)->pluck('id')->toArray();
        $members_withoutComp = Local_Stock_Member::where('section_id', $id)->where('company_id', null)->where('status', 1)->pluck('id')->toArray();
        if(count($members_withoutComp) > 0){
            foreach ($members_withoutComp as $v){
                array_unshift($members,$v);
            }
        }

        $moves = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns', 'LocalStockMember.Company', 'LocalStockMember.LocalStockproducts')->whereIn('member_id', $members);
        if ($date) {
            $moves->whereDate('created_at', $date);
        }
        $moves = $moves->latest()->get()->unique('member_id');

        return $moves;
    }

    public function RankingMembers($id,$ads,$date)
    {
        $memberssort = Local_Stock_Member::whereIn('company_id', $ads)->where('section_id', $id)->where('status', 1)->pluck('id');
        $movessort = Local_Stock_Movement::with('LocalStockDetials.LocalStockColumns', 'LocalStockMember.Company', 'LocalStockMember.LocalStockproducts')->whereIn('member_id', $memberssort);
        if ($date) {
            $movessort->whereDate('created_at', $date);
        }
        $movessort = $movessort->latest()->get()->unique('member_id');

        return $movessort;

    }
}