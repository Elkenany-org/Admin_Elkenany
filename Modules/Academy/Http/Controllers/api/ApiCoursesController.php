<?php

namespace Modules\Academy\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Session;
use Image;
use File;
use Auth;
use Validator;
use Date;
use URL;
use View;


class ApiCoursesController extends Controller
{

    # show all cources
    public function showcources()
    {
        $lives = Course::with('CourseLive')->where('live','1')->where('status_l','0')->latest()->get();
        $meetings = Course::where('meeting','1')->where('status_o','0')->latest()->get();
        $offlines = Course::with('CourseOffline.CourseOfflineFolders.CourseQuizzs','CourseOffline.CourseOfflinevideos','CourseQuizz')->where('offline','1')->latest()->get();

        $list = [];
        if(count($lives) < 1)
        {
            $list['live'] = [];

        }
        if(count($meetings) < 1)
        {
            $list['offline'] = [];

        }
        if(count($offlines) < 1)
        {
            $list['online'] = [];

        }

        foreach($lives as $key => $value){
            $llll = Course_Live::where('courses_id',$value->id)->latest()->first();

            $sum = Course_Live::where('courses_id',$value->id)->sum('hourse_count');
            $now =Carbon::today();
            if((float) $value->hourse_live == $sum && $llll->date <= $now){
                $value->status_l = 1;
                $value->update();
            }

            $list['live'][$key]['id']          = $value->id;
            $list['live'][$key]['title']       = $value->title;
            $list['live'][$key]['price_live']  = $value->price_live;
            $list['live'][$key]['image']       = URL::to('uploads/courses/avatar/'.$value->image);
  


        }

        foreach($meetings as $key => $valuem){
            $llllm = Course_Meeting::where('courses_id',$valuem->id)->latest()->first();

            $summ = Course_Meeting::where('courses_id',$valuem->id)->sum('hourse_count');
            $nowm =Carbon::today();
            if((float) $valuem->hourse_meeting == $summ && $llllm->date <= $nowm){
                $valuem->status_o = 1;
                $valuem->update();
            }

            
            $list['offline'][$key]['id']          = $valuem->id;
            $list['offline'][$key]['title']       = $valuem->title;
            $list['offline'][$key]['price_meeting']  = $valuem->price_meeting;
            $list['offline'][$key]['image']       = URL::to('uploads/courses/avatar/'.$valuem->image);

        }

        foreach($offlines as $key => $valueo){
       

            
            $list['online'][$key]['id']          = $valueo->id;
            $list['online'][$key]['title']       = $valueo->title;
            $list['online'][$key]['price_offline']  = $valueo->price_offline;
            $list['online'][$key]['image']       = URL::to('uploads/courses/avatar/'.$valueo->image);

        }


        

       

        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # show cource
    public function showcourcelive()
    {
        $id  = Input::get("id");

      

        $list = [];

 
        $live = Course::with('CourseLive')->where('id',$id)->first();

        if(!$live)
        {
            $msg = 'live not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }
        $com = Course_user::where('courses_id',$live->id)->where('status','1')->where('user_id', session('customer')->id)->first();
    
        $list['detials']['id']          = $live->id;
        $list['detials']['title']       = $live->title;
        $list['detials']['image']       = URL::to('uploads/courses/avatar/'.$live->image);

        foreach($live->CourseLive as $key => $value){

            if($value->date <= Carbon::today()){
            $list['live'][$key]['id']          = $value->id;
            $list['live'][$key]['title']       = $value->title;
            $list['live'][$key]['hourse_count']  = $value->hourse_count;
            $list['live'][$key]['date']  = $value->date;
            $list['live'][$key]['time']  = $value->time;
            $list['live'][$key]['checked']  = true;

            }else{
            $list['live'][$key]['id']          = $value->id;
            $list['live'][$key]['title']       = $value->title;
            $list['live'][$key]['hourse_count']  = $value->hourse_count;
            $list['live'][$key]['date']  = $value->date;
            $list['live'][$key]['time']  = $value->time;
            $list['live'][$key]['checked']  = false;
            }

            

    


        }

        if(!empty($com)){
            $list['sign'][]['id']             = session('customer')->id;
            $list['sign'][]['name']           = session('customer')->name;
            $list['sign'][]['email']          = session('customer')->email;
            $list['sign'][]['phone']          = session('customer')->phone;
        }else{
            $list['sign']             = [];
        }

    
        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }


    public function showcourceoffline()
    {
        $id  = Input::get("id");


        $list = [];

       
        $offline = Course::with('CourseMeeting')->where('id',$id)->first();

        if(!$offline)
        {
            $msg = 'offline not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $com = Course_user::where('courses_id',$offline->id)->where('status','2')->where('user_id', session('customer')->id)->first();

        $list['detials']['id']          = $offline->id;
        $list['detials']['title']       = $offline->title;
        $list['detials']['address']       = $offline->CourseMeeting->first()->location;
        $list['detials']['image']       = URL::to('uploads/courses/avatar/'.$offline->image);

        foreach($offline->CourseMeeting as $key => $value){

            $list['offline'][$key]['id']          = $value->id;
            $list['offline'][$key]['title']       = $value->title;
            $list['offline'][$key]['hourse_count']  = $value->hourse_count;
            $list['offline'][$key]['date']  = $value->date;
            $list['offline'][$key]['time']  = $value->time;
     



        }

        if(!empty($com)){
            $list['sign'][]['id']             = session('customer')->id;
            $list['sign'][]['name']           = session('customer')->name;
            $list['sign'][]['email']          = session('customer')->email;
            $list['sign'][]['phone']          = session('customer')->phone;
        }else{
            $list['sign']             = [];
        }


     

        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    public function showcourceonline()
    {
        $id  = Input::get("id");


        $list = [];

       
        $online = Course::with('CourseOffline.CourseOfflineFolders.CourseQuizzs','CourseOffline.CourseOfflinevideos')->where('id',$id)->first();

        if(!$online)
        {
            $msg = 'online not found';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);	
        }

        $com = Course_user::where('courses_id',$online->id)->where('status','3')->where('user_id', session('customer')->id)->first();

        $uses = User_videos::where('courses_id',$id)->where('status','1')->where('user_id', session('customer')->id)->latest()->get();
       
        $coms = Course_Comment::with('Customer','REPComments.Customer')->where('courses_id',$online->id)->get();

        foreach($coms as $key => $value){
            $list['comment'][$key]['id']          = $value->id;
            $list['comment'][$key]['comment']       = $value->comment;
            $list['comment'][$key]['name']       = $value->Customer->name;

            if(count($value->REPComments) < 1)
            {
                $list['comment'][$key]['replay'] = [];
    
            }

            foreach($value->REPComments as $que){
                            
                $queee = [];
                $queee['id']          = $que->id;
                $queee['comment']       = $que->comment;
                $queee['name']       = $que->Customer->name;
    
              
                $list['comment'][$key]['replay'][] = $queee;
               
            }

        }
       

        if(!empty($com)){

            $list['sign']          = true;


            $list['detials']['id']          = $online->id;
            $list['detials']['title']       = $online->title;
            $list['detials']['image']       = URL::to('uploads/courses/avatar/'.$online->image);




            $loin = [];


            foreach($online->CourseOffline->first()->CourseOfflineFolders as $key => $value){

                $list['Folders'][$key]['id']          = $value->id;
                $list['Folders'][$key]['name']       = $value->name;
                $list['Folders'][$key]['count_videos']  = count($value->CourseOfflinevideos);

                foreach ($value->CourseOfflinevideos as $val)
                {
                    $loin['id']          = $val->id;
                    $loin['title']       = $val->title;
                    $loin['video_name']  = $val->video;
                    $loin['video']       = URL::to('uploads/videos/'.$val->video);

                    foreach($uses as $user){
                        if($user->video_id == $val->id){
                            $loin['checked']  = true;
                        }
                    }
                    
                 

                    $list['Folders'][$key]['videos'][] = $loin;
                
                }

                $usvid = User_videos::where('folder_id',$value->id)->where('status','1')->where('user_id', session('customer')->id)->latest()->get();
                
                    if(count($value->CourseQuizzs) <= 0){
                    
                        $list['Folders'][$key]['exam'] = [];
                    }

                    if(count($value->CourseOfflinevideos) == count($usvid)){
                        foreach($value->CourseQuizzs as $que){
                            
                            $queee = [];
                            $queee['id']          = $que->id;
                            $queee['title']       = $que->title;
                          
                            $list['Folders'][$key]['exam'][] = $queee;
                           
                        }
                       
                       
                    }
                    
               
                

                
                



            }
        }else{

            $list['sign']          = false;
           
            $list['detials']['id']          = $online->id;
            $list['detials']['title']       = $online->title;
            $list['detials']['price_offline']       = $online->price_offline;
            $list['detials']['image']       = URL::to('uploads/courses/avatar/'.$online->image);
            $list['detials']['prof']          = $online->CourseOffline->first()->prof;
            $list['detials']['count_videos']       = count($online->CourseOffline->first()->CourseOfflinevideos);
            $list['detials']['hourse_count']       = $online->CourseOffline->first()->hourse_count;
            $list['detials']['desc']       = $online->desc;

            $loin = [];

            foreach($online->CourseOffline->first()->CourseOfflineFolders as $key => $value){

                $list['Folders'][$key]['id']          = $value->id;
                $list['Folders'][$key]['name']       = $value->name;
                $list['Folders'][$key]['count_videos']  = count($value->CourseOfflinevideos);

                foreach ($value->CourseOfflinevideos as $val)
                {
                    $loin['id']          = $val->id;
                    $loin['title']       = $val->title;
                    $loin['video']       = $val->video;
                 

                    $list['Folders'][$key]['videos'][] = $loin;
                
                }



            }


        }


     

        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);

    }

    # add going
    public function goinglive(Request $request)
    {

        $going = new Course_user;
        $going->courses_id = $request->courses_id;
        $going->user_id    = session('customer')->id;
        $going->status     = 1;
        $going->save();


        return response()->json([

            'message'  => 'انت حجزت الكورس اللايف',
            'error'    => null,
  
        ],200);
    }

    # add going
    public function goingoffline(Request $request)
    {

        $going = new Course_user;
        $going->courses_id = $request->courses_id;
        $going->user_id    = session('customer')->id;
        $going->status     = 2;
        $going->save();


        return response()->json([

            'message'  => 'انت حجزت الكورس الاوف لاين',
            'error'    => null,
    
        ],200);
    }


    # add going
    public function goingonline(Request $request)
    {

        $going = new Course_user;
        $going->courses_id = $request->courses_id;
        $going->user_id    = session('customer')->id;
        $going->status     = 3;
        $going->save();


        return response()->json([

            'message'  => 'انت حجزت الكورس الاونلاين',
            'error'    => null,
    
        ],200);
    }

    # index
    public function lives()
    {
        $lives = Course::where('live','1')->latest()->get();

        
        $list = [];
        if(count($lives) < 1)
        {
            $list['live'] = [];

        }

        foreach($lives as $key => $value){
            $llll = Course_Live::where('courses_id',$value->id)->latest()->first();

            $sum = Course_Live::where('courses_id',$value->id)->sum('hourse_count');
            $now =Carbon::today();
            if((float) $value->hourse_live == $sum && $llll->date <= $now){
                $value->status_l = 1;
                $value->update();
            }

            $list['live'][$key]['id']          = $value->id;
            $list['live'][$key]['title']       = $value->title;
            $list['live'][$key]['price_live']  = $value->price_live;
            $list['live'][$key]['image']       = URL::to('uploads/courses/avatar/'.$value->image);
  


        }

        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }

    # offline
    public function offlines()
    {
        $offlines = Course::where('meeting','1')->latest()->get();

        $list = [];
        if(count($offlines) < 1)
        {
            $list['offline'] = [];

        }
        
        foreach($offlines as $key => $valuem){
        $llllm = Course_Meeting::where('courses_id',$valuem->id)->latest()->first();

        $summ = Course_Meeting::where('courses_id',$valuem->id)->sum('hourse_count');
        $nowm =Carbon::today();
        if((float) $valuem->hourse_meeting == $summ && $llllm->date <= $nowm){
            $valuem->status_o = 1;
            $valuem->update();
        }

        
        $list['offline'][$key]['id']          = $valuem->id;
        $list['offline'][$key]['title']       = $valuem->title;
        $list['offline'][$key]['price_meeting']  = $valuem->price_meeting;
        $list['offline'][$key]['image']       = URL::to('uploads/courses/avatar/'.$valuem->image);

        }

        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }

    # online
    public function onlines()
    {
        $onlines = Course::where('offline','1')->latest()->get();

        $list = [];
        if(count($onlines) < 1)
        {
            $list['online'] = [];

        }


        foreach($onlines as $key => $valueo){
       

            
            $list['online'][$key]['id']          = $valueo->id;
            $list['online'][$key]['title']       = $valueo->title;
            $list['online'][$key]['price_offline']  = $valueo->price_offline;
            $list['online'][$key]['image']       = URL::to('uploads/courses/avatar/'.$valueo->image);

        }

        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }

    # add watch
    public function watch(Request $request)
    {

        $uses = User_videos::where('courses_id',$request->courses_id)->where('video_id',$request->video_id)->where('status','1')->where('user_id', session('customer')->id)->latest()->first();
        if(!$uses){
            $watch = new User_videos;
            $watch->courses_id = $request->courses_id;
            $watch->folder_id  = $request->folder_id;
            $watch->user_id    = session('customer')->id;
            $watch->status     = 1;
            $watch->video_id = $request->video_id;
            $watch->save();
    
    
            return response()->json([

                'message'  => 'تم مشاهدة الفديو الان',
                'error'    => null,
        
            ],200);
        }

        return response()->json([

            'message'  => 'تم مشاهدة الفديو سابقا',
            'error'    => null,
    
        ],200);
        
    }


    # mycourses
    public function mycourses()
    {

     
        $lives = Course_user::with('Course')->where('status','1')->where('user_id',session('customer')->id)->get();

        $meetings = Course_user::with('Course')->where('status','2')->where('user_id', session('customer')->id)->get();

        $offlines = Course_user::with('Course')->where('status','3')->where('user_id', session('customer')->id)->get();


        $list = [];
        if(count($lives) < 1)
        {
            $list['live'] = [];

        }
        if(count($meetings) < 1)
        {
            $list['offline'] = [];

        }
        if(count($offlines) < 1)
        {
            $list['online'] = [];

        }

        foreach($lives as $key => $value){
          

            $list['live'][$key]['id']          = $value->Course->id;
            $list['live'][$key]['title']       = $value->Course->title;
            $list['live'][$key]['price_live']  = $value->Course->price_live;
            $list['live'][$key]['image']       = URL::to('uploads/courses/avatar/'.$value->Course->image);
  


        }

        foreach($meetings as $key => $valuem){
        

            
            $list['offline'][$key]['id']          = $valuem->Course->id;
            $list['offline'][$key]['title']       = $valuem->Course->title;
            $list['offline'][$key]['price_meeting']  = $valuem->Course->price_meeting;
            $list['offline'][$key]['image']       = URL::to('uploads/courses/avatar/'.$valuem->Course->image);

        }

        foreach($offlines as $key => $valueo){
       

            
            $list['online'][$key]['id']          = $valueo->Course->id;
            $list['online'][$key]['title']       = $valueo->Course->title;
            $list['online'][$key]['price_offline']  = $valueo->Course->price_offline;
            $list['online'][$key]['image']       = URL::to('uploads/courses/avatar/'.$valueo->Course->image);

        }


        

       

        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    
    }

    # exams
    public function exams()
    {

        $courses = Course_user::with('Course.CourseQuizz')->where('user_id', session('customer')->id)->get()->unique('courses_id');

        $olds = Course_Quizz_Result::with('CourseQuizz')->where('user_id', session('customer')->id)->get();

        $list = [];
      

        foreach($courses as $key => $value){
          
          
            $list['courses'][$key]['id']          = $value->Course->id;
            $list['courses'][$key]['title']          = $value->Course->title;

            foreach($value->Course->CourseQuizz as $val){
                
                if($val->folder_id == null){
                    $queeee = [];
                    $queeee['id']          = $val->id;
                    $queeee['title']          = $val->title;
                    $list['courses'][$key]['exams'][]          = $queeee;
                }
              
              
            }
           
      
        }



        // old

        foreach($courses as $key => $value){
        
          
            $list['old_courses'][$key]['id']          = $value->Course->id;
            $list['old_courses'][$key]['title']          = $value->Course->title;

            foreach($value->Course->CourseQuizz as $val){
  
                foreach($olds as $vau){
                    if($val->id == $vau->quizz_id){
                        $queeee = [];
                        $queeee['id']          = $val->id;
                        $queeee['title']          = $val->title;
                        $list['old_courses'][$key]['exams'][]          = $queeee;
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


    # exam
    public function exam()
    {
        $id   = Input::get("id");

        $list = [];

        $res = Course_Quizz_Result::where('quizz_id',$id)->where('user_id', session('customer')->id)->first();
        if($res){
            return response()->json([

                'message'  => ' لقد قمت بحل هذا الاختبار',
                'error'    => null,
        
            ],200);
        }else{
            $quize = Course_Quizz::with('CourseQuizzQuestions')->where('id',$id)->first();
            
            foreach($quize->CourseQuizzQuestions as $key => $value){
        
          
                $list['question'][$key]['id']          = $value->id;
                $list['question'][$key]['question']    = $value->question;
    
                foreach($value->Lastans() as $val)
                {       
                    $queeee = [];
                    $queeee['id']          = $val->id;
                    $queeee['answer']          = $val->answer;
                    $list['question'][$key]['answer'][]          = $queeee;
    
                }
             
                
          
            }
            
            return response()->json([
    
                'message'  => null,
                'error'    => null,
                'data'     => $list
            ],200);
        }
        

      
    }

    public function quize(Request $request)
    {
     

        $quize = Course_Quizz::with('CourseQuizzQuestions')->where('id',$request->quizz_id)->first();
        if($quize){

            $res = new Course_Quizz_Result;
            $res->result = 0;
            $res->user_id    = session('customer')->id;
            $res->success_rate     = 0;
            $res->quizz_id = $request->quizz_id;
            $res->save();
            
            foreach($request->answer as $value){
           
  
                $annn = Course_Quizz_Question_Answer::with('CourseQuizzQuestions')->where('id', $value)->first();


                $ansr = new Course_Quizz_Answer;
                $ansr->result_id = $res->id;
                $ansr->quizz_id = $request->quizz_id;
                $ansr->user_id    = session('customer')->id;
                $ansr->answer_id = $annn->id;
                $ansr->question_id = $annn->CourseQuizzQuestions->id;
                if($annn->correct == 1){
                    $ansr->state = 1;
                }else{
                    $ansr->state = 0;
                }
    
                $ansr->save();

            }
            
            $anses = Course_Quizz_Answer::where('state','1')->where('user_id', session('customer')->id)->where('quizz_id',$request->quizz_id)->get();

            $res->result = count($anses);
            $res->success_rate  = (count($anses) /count($quize->CourseQuizzQuestions)) * 100;
            $res->update();
    
            

        }
        
        return response()->json([
    
            'message'  => null,
            'error'    => null,
            'data'     => $res
        ],200);
    
    }


    # archive
    public function archive()
    {

        $id   = Input::get("id");

        $list = [];

        $res = Course_Quizz_Result::with('CourseQuizzAnswers.CourseQuizzQuestionAnswers','CourseQuizzAnswers.Customer','CourseQuizzAnswers.CourseQuizzQuestions','CourseQuizz.Course')->where('quizz_id',$id)->where('user_id', session('customer')->id)->first();
        
        $list['detials']['title']        = $res->CourseQuizz->Course->title;
        $list['detials']['result']       = $res->result;
        $list['detials']['success_rate'] = $res->success_rate;
        $list['detials']['quize']        = $res->CourseQuizz->title;

        foreach($res->CourseQuizzAnswers as $key => $value){
            $list['question'][$key]['id']          = $value->CourseQuizzQuestions->id;
            $list['question'][$key]['question']    = $value->CourseQuizzQuestions->question;
            $list['question'][$key]['answer']    = $value->CourseQuizzQuestionAnswers->answer;
        }



        return response()->json([
    
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
          
    }

    # certificates
    public function certificates()
    {

        $list = [];

        $lives = Course_user::with('Course')->where('status','1')->where('user_id', session('customer')->id)->get();

        $meetings = Course_user::with('Course')->where('status','2')->where('user_id', session('customer')->id)->get();

        $offlines = Course_user::with('Course.CourseOffline.CourseOfflinevideos','Course.CourseQuizz')->where('status','3')->where('user_id', session('customer')->id)->get();


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

        foreach($lives as $key => $value){
            if($value->Course->status_l == 1){
                $list['live'][$key]['id']          = $value->Course->id;
                $list['live'][$key]['title']       = $value->Course->title;
            }
      

        }

        foreach($meetings as $key => $value){
            if($value->Course->status_o == 1){
                $list['offline'][$key]['id']          = $value->Course->id;
                $list['offline'][$key]['title']       = $value->Course->title;
            }
      

        }

        
        foreach($offlines as $key => $value){

            $uses = User_videos::where('courses_id',$value->Course->id)->where('status','1')->where('user_id', session('customer')->id)->latest()->get();

            $quize = Course_Quizz::where('courses_id',$value->Course->id)->pluck('id')->toArray();

            $resl = Course_Quizz_Result::whereIn('quizz_id',$quize)->where('user_id', session('customer')->id)->get();
            
            if(count($value->Course->CourseOffline->first()->CourseOfflinevideos) == count($uses) && count($value->Course->CourseQuizz) == count($resl))
            {
                $list['online'][$key]['id']          = $value->Course->id;
                $list['online'][$key]['title']       = $value->Course->title;
            }
      

        }
            
        return response()->json([
    
            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
        
    
    }

    # certificate


    public function certificate()
    {
        $id   = Input::get("id");

        $list = [];

        
        
        $cour = Course::where('id',$id)->first();
        
        $list['detials']['title']        = $cour->title;
        $list['detials']['name']       = session('customer')->name;
        $list['detials']['email']       = session('customer')->email;

        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $list
        ],200);
    }


    # add comment
    public function comment(Request $request)
    {

        $going = new Course_Comment;
        $going->courses_id = $request->courses_id;
        $going->user_id    = session('customer')->id;
        $going->comment = $request->com;
        $going->save();


        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $going
        ],200);
    }

    # add replay
    public function replay(Request $request)
    {

        $going = new REP_Comment;
        $going->com_id = $request->com_id;
        $going->user_id    = session('customer')->id;
        $going->comment = $request->com;
        $going->save();


        return response()->json([

            'message'  => null,
            'error'    => null,
            'data'     => $going
        ],200);
    }

 

}
