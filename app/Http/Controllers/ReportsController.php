<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\User;
use Session;

class ReportsController extends Controller
{
    # super visors
    public function Index()
    {
        $users   = User::where('role','!=','0')->get();
    	return view('reports.users',compact('users'));
    }

    # reports
    public function Reports($id = null)
    {
        if($id)
        {
            $reports = Report::with('User.Role')->where('user_id',$id)->latest()->paginate(100);
            $user = User::where('id',$id)->first();
        }else{
    	$reports = Report::with('User.Role')->latest()->paginate(100);
            $user = null;
    }

    	return view('reports.reports',compact('reports','user'));
    }

    # delete all reports
    public function DeleteAllReports(Request $request)
    {
        if(!is_null($request->id))
    {
            $reports = Report::where('user_id',$request->id)->get();
        }else{
    	$reports = Report::get();
        }
    	foreach ($reports as $key => $value) {
    		$value->delete();
    	}
    	Session::flash('success','تم الحذف بنجاح');
    	return back();
    }

    # delete report
    public function DeleteReport(Request $request)
    {
    	$repo = Report::where('id',$request->id)->first();
    	$repo->delete();
    	Session::flash('success','تم الحذف بنجاح');
    	return back();
    }
}
