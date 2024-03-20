<?php

namespace Modules\Academy\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academy\Entities\Course;
use Modules\Academy\Entities\Course_Live;
use Modules\Academy\Entities\Course_Meeting;
use Modules\Academy\Entities\Course_Offline_videos;
use Modules\Academy\Entities\Course_Offline;
use Modules\Academy\Entities\Course_Comment;
use Modules\Academy\Entities\REP_Comment;
use Modules\Academy\Entities\Course_Offline_Folder;
use Modules\Academy\Entities\Course_user;
use Modules\Academy\Entities\User_videos;
use Modules\Academy\Entities\Course_Quizz;
use Modules\Academy\Entities\Course_Quizz_Question;
use Modules\Academy\Entities\Course_Quizz_Question_Answer;
use Modules\Academy\Entities\Course_Quizz_Result;
use Modules\Academy\Entities\Course_Quizz_Answer;
use Modules\Store\Entities\Customer;
use Carbon\Carbon;
use Session;
use Image;
use File;
use Auth;

class AcademyfrontController extends Controller
{
   
    # index
    public function Index()
    {
        $lives = Course::with('CourseLive')->where('live','1')->where('status_l','0')->latest()->get();
        $meetings = Course::where('meeting','1')->where('status_o','0')->latest()->get();
        $offlines = Course::with('CourseOffline.CourseOfflineFolders.CourseQuizzs','CourseOffline.CourseOfflinevideos','CourseQuizz')->where('offline','1')->latest()->get();

        foreach($lives as $value){
            $llll = Course_Live::where('courses_id',$value->id)->latest()->first();

            $sum = Course_Live::where('courses_id',$value->id)->sum('hourse_count');
            $now =Carbon::today();
            if((float) $value->hourse_live == $sum && $llll->date <= $now){
                $value->status_l = 1;
                $value->update();
            }

        }

        foreach($meetings as $valuem){
            $llllm = Course_Meeting::where('courses_id',$valuem->id)->latest()->first();

            $summ = Course_Meeting::where('courses_id',$valuem->id)->sum('hourse_count');
            $nowm =Carbon::today();
            if((float) $valuem->hourse_meeting == $summ && $llllm->date <= $nowm){
                $valuem->status_o = 1;
                $valuem->update();
            }

        }

        // foreach($offlines as $valueo){
        //     if(Auth::guard('customer')->user())
        //     {
        //         $uses = User_videos::where('courses_id',$valueo->id)->where('status','1')->where('user_id', Auth::guard('customer')->user()->id)->latest()->get();
        //         $quize = Course_Quizz::where('courses_id',$valueo->id)->pluck('id')->toArray();
        //         $res = Course_Quizz_Result::whereIn('quizz_id',$quize)->where('user_id', Auth::guard('customer')->user()->id)->get();

        //         $nowo =Carbon::today();
        //         if(count($valueo->CourseOffline->first()->CourseOfflinevideos) == count($uses) && count($valueo->CourseQuizz) == count($res)){
        //             $valueo->status_n = 1;
        //             $valueo->update();
        //         }
        //     }
        // }

        return view('academy::fronts.courses',compact('lives','meetings','offlines'));
    }

    # live
    public function live($id)
    {
        $live = Course::with('CourseLive')->where('id',$id)->first();
        if(Auth::guard('customer')->user())
        {
            $com = Course_user::where('courses_id',$live->id)->where('status','1')->where('user_id', Auth::guard('customer')->user()->id)->first();

            return view('academy::fronts.live',compact('live','com'));
        }
        return view('academy::fronts.live',compact('live'));
    }

    # add going
    public function goinglive(Request $request)
    {

        $going = new Course_user;
        $going->courses_id = $request->courses_id;
        $going->user_id    = Auth::guard('customer')->user()->id;
        $going->status     = 1;
        $going->save();


        Session::flash('success','تم التحديد');
        return back();
    }

    # index
    public function lives()
    {
        $lives = Course::where('live','1')->latest()->get();

        return view('academy::fronts.lives',compact('lives'));
    }

    # offline
    public function offline($id)
    {
        $offline = Course::with('CourseMeeting')->where('id',$id)->first();
        if(Auth::guard('customer')->user())
        {
            $com = Course_user::where('courses_id',$offline->id)->where('status','2')->where('user_id', Auth::guard('customer')->user()->id)->first();

            return view('academy::fronts.offline',compact('offline','com'));
        }
        return view('academy::fronts.offline',compact('offline'));
    }

    # add going
    public function goingoffline(Request $request)
    {

        $going = new Course_user;
        $going->courses_id = $request->courses_id;
        $going->user_id    = Auth::guard('customer')->user()->id;
        $going->status     = 2;
        $going->save();


        Session::flash('success','تم التحديد');
        return back();
    }

    # offline
    public function offlines()
    {
        $offlines = Course::where('meeting','1')->latest()->get();

        return view('academy::fronts.offlines',compact('offlines'));
    }


    # online
    public function online($id)
    {
        $online = Course::with('CourseOffline.CourseOfflineFolders.CourseQuizzs','CourseOffline.CourseOfflinevideos')->where('id',$id)->first();
        if(Auth::guard('customer')->user())
        {
            $com = Course_user::where('courses_id',$online->id)->where('status','3')->where('user_id', Auth::guard('customer')->user()->id)->first();

            $uses = User_videos::where('courses_id',$id)->where('status','1')->where('user_id', Auth::guard('customer')->user()->id)->latest()->get();
           
            $coms = Course_Comment::with('REPComments')->where('courses_id',$online->id)->get();
           
            return view('academy::fronts.online',compact('online','com','uses','coms'));
        }
        return view('academy::fronts.online',compact('online'));
    }

    # add going
    public function goingonline(Request $request)
    {

        $going = new Course_user;
        $going->courses_id = $request->courses_id;
        $going->user_id    = Auth::guard('customer')->user()->id;
        $going->status     = 3;
        $going->save();


        Session::flash('success','تم التحديد');
        return back();
    }

    # online
    public function onlines()
    {
        $onlines = Course::where('offline','1')->latest()->get();

        return view('academy::fronts.onlines',compact('onlines'));
    }


    # add watch
    public function watch(Request $request)
    {

        $uses = User_videos::where('courses_id',$request->courses_id)->where('video_id',$request->video_id)->where('status','1')->where('user_id', Auth::guard('customer')->user()->id)->latest()->first();
        if(!$uses){
            $watch = new User_videos;
            $watch->courses_id = $request->courses_id;
            $watch->folder_id  = $request->folder_id;
            $watch->user_id    = Auth::guard('customer')->user()->id;
            $watch->status     = 1;
            $watch->video_id = $request->video_id;
            $watch->save();
    
    
            return $watch;
        }
       
    }

    # mycourses
    public function mycourses()
    {

        if(Auth::guard('customer')->user())
        {
            $lives = Course_user::with('Course')->where('status','1')->where('user_id', Auth::guard('customer')->user()->id)->get();

            $meetings = Course_user::with('Course')->where('status','2')->where('user_id', Auth::guard('customer')->user()->id)->get();

            $offlines = Course_user::with('Course')->where('status','3')->where('user_id', Auth::guard('customer')->user()->id)->get();
            return view('academy::fronts.mycourses',compact('lives','meetings','offlines'));
        }

       
        return view('store::fronts.customer_login');
    }

    # exams
    public function exams()
    {

        if(Auth::guard('customer')->user())
        {
            $courses = Course_user::with('Course.CourseQuizz')->where('user_id', Auth::guard('customer')->user()->id)->get()->unique('courses_id');

            $olds = Course_Quizz_Result::with('CourseQuizz')->where('user_id', Auth::guard('customer')->user()->id)->get();

            return view('academy::fronts.exams',compact('courses','olds'));
        }

        
        return view('store::fronts.customer_login');
    }

    # exam
    public function exam($id)
    {

        if(Auth::guard('customer')->user())
        {
            $res = Course_Quizz_Result::where('quizz_id',$id)->where('user_id', Auth::guard('customer')->user()->id)->first();
            if($res){
                Session::flash('success',' لقد قمت بحل هذا الاختبار');
                return back();
            }else{
                $quize = Course_Quizz::with('CourseQuizzQuestions')->where('id',$id)->first();

                return view('academy::fronts.exam',compact('quize'));
            }
          
        }

        
        return view('store::fronts.customer_login');
    }

    # add quize
    public function quize(Request $request)
    {
     

        $quize = Course_Quizz::with('CourseQuizzQuestions')->where('id',$request->quizz_id)->first();
        if($quize){

            $res = new Course_Quizz_Result;
            $res->result = 0;
            $res->user_id    = Auth::guard('customer')->user()->id;
            $res->success_rate     = 0;
            $res->quizz_id = $request->quizz_id;
            $res->save();
            
            foreach($quize->CourseQuizzQuestions as $value){
           
  
                $annn = Course_Quizz_Question_Answer::where('id',$request->input('ans'.$value->id))->first();


                $ansr = new Course_Quizz_Answer;
                $ansr->result_id = $res->id;
                $ansr->quizz_id = $request->quizz_id;
                $ansr->user_id    = Auth::guard('customer')->user()->id;
                $ansr->answer_id = $annn->id;
                $ansr->question_id = $value->id;
                if($annn->correct == 1){
                    $ansr->state = 1;
                }else{
                    $ansr->state = 0;
                }
    
                $ansr->save();

            }
            
            $anses = Course_Quizz_Answer::where('state','1')->where('user_id', Auth::guard('customer')->user()->id)->where('quizz_id',$request->quizz_id)->get();

            $res->result = count($anses);
            $res->success_rate  = (count($anses) /count($quize->CourseQuizzQuestions)) * 100;
            $res->update();
    
            

        }
        return redirect()->route('front_exams_courses');
    
    }

    # archive
    public function archive($id)
    {

        if(Auth::guard('customer')->user())
        {
            $res = Course_Quizz_Result::with('CourseQuizzAnswers.CourseQuizzQuestionAnswers','CourseQuizzAnswers.Customer','CourseQuizzAnswers.CourseQuizzQuestions','CourseQuizz.Course')->where('quizz_id',$id)->where('user_id', Auth::guard('customer')->user()->id)->first();
          

            return view('academy::fronts.archive',compact('res'));
        
        }

        
        return view('store::fronts.customer_login');
    }



    # certificates
    public function certificates()
    {

        if(Auth::guard('customer')->user())
        {
            $lives = Course_user::with('Course')->where('status','1')->where('user_id', Auth::guard('customer')->user()->id)->get();

            $meetings = Course_user::with('Course')->where('status','2')->where('user_id', Auth::guard('customer')->user()->id)->get();

            $offlines = Course_user::with('Course.CourseOffline.CourseOfflinevideos','Course.CourseQuizz')->where('status','3')->where('user_id', Auth::guard('customer')->user()->id)->get();
           

            $livess = Course::with('CourseLive')->where('live','1')->where('status_l','0')->latest()->get();
            $meetingss = Course::where('meeting','1')->where('status_o','0')->latest()->get();
            foreach($livess as $value){
                $llll = Course_Live::where('courses_id',$value->id)->latest()->first();
    
                $sum = Course_Live::where('courses_id',$value->id)->sum('hourse_count');
                $now =Carbon::today();
                if((float) $value->hourse_live == $sum && $llll->date <= $now){
                    $value->status_l = 1;
                    $value->update();
                }
    
            }
    
            foreach($meetingss as $valuem){
                $llllm = Course_Meeting::where('courses_id',$valuem->id)->latest()->first();
    
                $summ = Course_Meeting::where('courses_id',$valuem->id)->sum('hourse_count');
                $nowm =Carbon::today();
                if((float) $valuem->hourse_meeting == $summ && $llllm->date <= $nowm){
                    $valuem->status_o = 1;
                    $valuem->update();
                }
    
            }
           
           
            return view('academy::fronts.certificates',compact('lives','meetings','offlines'));
        }

        
        return view('store::fronts.customer_login');
    }


    # certificate
    public function certificate($id)
    {

        if(Auth::guard('customer')->user())
        {
        $cour = Course::where('id',$id)->first();
        
            return view('academy::fronts.certificate',compact('cour'));
        }

        
        return view('store::fronts.customer_login');
    }


    # add comment
    public function comment(Request $request)
    {

        $going = new Course_Comment;
        $going->courses_id = $request->courses_id;
        $going->user_id    = Auth::guard('customer')->user()->id;
        $going->comment = $request->com;
        $going->save();


        Session::flash('success','تم اضافة كومنت');
        return back();
    }

    # add replay
    public function replay(Request $request)
    {

        $going = new REP_Comment;
        $going->com_id = $request->com_id;
        $going->user_id    = Auth::guard('customer')->user()->id;
        $going->comment = $request->com;
        $going->save();


        Session::flash('success','تم اضافة رد');
        return back();
    }






}
