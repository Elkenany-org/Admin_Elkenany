<?php

namespace Modules\Recruitment\Http\Controllers;

use App\Main;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Recruitment\Entities\Job_Categories;
use Modules\Recruitment\Entities\Job_Offer;
use Modules\Store\Entities\Customer;
use Session;
class RecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $categories = Job_Categories::get();
        return view('recruitment::categories',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('recruitment::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'type'         => 'required',
            'desc'         => 'required',
        ]);

        $category = new Job_Categories;
        $category->name       = $request->name;
        $category->type       = $request->type;
        $category->desc       = $request->desc;
        $category->save();


        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة قسم جديد '.$category->name);
        return back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('recruitment::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('recruitment::edit');
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

    public function  UpdateCategory($id){
        $category= Job_Categories::where('id',$id)->first();

        return view('recruitment::editcategory',compact('category'));

    }

    public function  storeUpdateCategory(Request $request){
        $request->validate([
            'name'           => 'required|max:500',
            'type'           => 'required',
            'desc'           => 'required',
        ]);

        $category = Job_Categories::where('id',$request->id)->first();
        $category->name            = $request->name;
        $category->type            = $request->type;
        $category->desc            = $request->desc;
        $category->save();


        Session::flash('success','تم الحفظ');
        MakeReport('بتحديث قسم '.$category->name);
        return back();

    }

    public function deleteCategory(Request $request){
        $category = Job_Categories::where('id',$request->id)->first();
        Session::flash('success','تم الحذف');
        MakeReport('بحذف معرض '.$category->name);
        $category->delete();
        return back();
    }

    public function Recruiters(){
        $recruiters=Customer::where('company_id','!=',null)->get();

        return view('recruitment::recruiters',compact('recruiters'));

    }

    public function UpdateRecruiter($id){
        $recruiter= Customer::where('id',$id)->first();
        return view('recruitment::editrecruiter',compact('recruiter'));

    }
    public function  storeEditRecruiter(Request $request){
        $request->validate([
            'status'           => 'required',
        ]);

        $recruiter = Customer::where('id',$request->id)->first();
        $recruiter->verified_company = $request->status;
        $recruiter->save();

        $jobs= Job_Offer::where('recruiter_id',$recruiter->id)->get();

        if($recruiter->verified_company == '1'){
            foreach ($jobs as $job){
                $job->approved = '1';
                $job->save();
            }
        }else{
            foreach ($jobs as $job){
                $job->approved = '0';
                $job->save();
            }
        }



        Session::flash('success','تم الحفظ');
        MakeReport('بتحديث حالة المعلن '.$recruiter->name);
        return back();

    }

    public function Jobs(){
        $jobs= Job_Offer::get();
        return view('recruitment::jobs',compact('jobs'));
    }

    public function  UpdateJob($id){
        $job= Job_Offer::where('id',$id)->first();
        $recruiter= Customer::where('id',$job->recruiter_id)->first();
        $job_Category=Job_Categories::where('id',$job->category_id)->first();
        return view('recruitment::editjob',compact('job','recruiter','job_Category'));

    }

    public function  storeUpdateJob(Request $request){
        $request->validate([
            'approved'           => 'required',
        ]);

        $job = Job_Offer::where('id',$request->id)->first();
        $job->approved            = $request->approved;
        $job->save();


        Session::flash('success','تم الحفظ');
        MakeReport('بتحديث حالة الوظيفة '.$job->name);
        return back();

    }

    public function deleteJob(Request $request){
        $category = Job_Offer::where('id',$request->id)->first();
        Session::flash('success','تم الحذف');
        MakeReport('بحذف معرض '.$category->name);
        $category->delete();
        return back();
    }
}
