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
use Modules\Academy\Entities\Course_Offline_Folder;
use Session;
use Image;
use File;

class CourcesController extends Controller
{
    # index
    public function Index()
    {
        $courses = Course::latest()->get();
        return view('academy::courses.courses',compact('courses'));
    }

    # add courses page
    public function Addcourses()
    {
        return view('academy::courses.add_courses');
    }

    # store courses 
    public function Storecourses(Request $request)
    {
        $request->validate([
            'title'    => 'required',
            'desc'     => 'required',
            'image'    => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $courses = new Course;
        $courses->title            = $request->title;
        $courses->desc             = $request->desc;
        $courses->price_live       = $request->price_live;
        $courses->price_meeting    = $request->price_meeting;
        $courses->price_offline    = $request->price_offline;
        $courses->hourse_live      = $request->hourse_live;
        $courses->hourse_meeting   = $request->hourse_meeting;
        $courses->hourse_offline   = $request->hourse_offline;

        # upload image
        if(!is_null($request->image))
        {
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/courses/avatar/'.$name);
            $courses->image=$name;
        }
        $courses->save();
        $course = Course::where('id',$courses->id)->first();

            if(!is_null($request->price_live) && !is_null($request->date_live)  && !is_null($request->time_live)  && !is_null($request->link_live) && !is_null($request->application) && !is_null($request->hourse_count_live))
            {
                $live = new Course_Live;
                $live->date          = $request->date_live;
                $live->time          = $request->time_live;
                $live->link          = $request->link_live;
                $live->application   = $request->application;
                $live->title         = $request->title_live;
                $live->prof          = $request->prof_live;
                $live->hourse_count  = $request->hourse_count_live;
                $live->courses_id    = $courses->id;
                $live->save();

                $course->live    = 1;
            }

            if(!is_null($request->price_meeting) && !is_null($request->date_Meeting) && !is_null($request->time_Meeting) && !is_null($request->location) && !is_null($request->hourse_count_Meeting))
            {
                $Meeting = new Course_Meeting;
                $Meeting->date        = $request->date_Meeting;
                $Meeting->time        = $request->time_Meeting;
                $Meeting->location    = $request->location;
                $Meeting->longitude   = $request->longitude;
                $Meeting->latitude    = $request->latitude;
                $Meeting->title       = $request->title_Meeting;
                $Meeting->prof       = $request->prof_Meeting;
                $Meeting->hourse_count= $request->hourse_count_Meeting;
                $Meeting->courses_id  = $courses->id;
                $Meeting->save();

                $course->meeting    = 1;
            }

            if(!is_null($request->price_offline) && !is_null($request->hourse_offline))
            {
                $offline = new Course_Offline;
                $offline->prof          = $request->prof_offline;
                $offline->hourse_count  = $request->hourse_offline;
                $offline->courses_id    = $courses->id;
                $offline->save();

                $course->offline    = 1;
            }

        $course->save();

        MakeReport('بإضافة كورس جديدة ' .$courses->title);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # add folder
    public function Storefolder(Request $request)
    {
        $request->validate([
            'folder_name'         => 'required',

        ]);

        $folder = new Course_Offline_Folder;
        $folder->name       = $request->folder_name;
        $folder->offline_id = $request->id;
        $folder->courses_id = $request->courses_id;
        $folder->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة فولدر '.$folder->name);
        return back();
    }


    # update folder
    public function Updatefolder(Request $request)
    {
        $request->validate([
            'edit_folder_name'         => 'required',
        ]);

        $folder = Course_Offline_Folder::findOrFail($request->edit_id);
        $folder->name       = $request->edit_folder_name;

        $folder->save();
        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  فولدر '.$folder->name);
        return back();
    }

    # delete folder
    public function Deletefolder(Request $request)
    {

        $folder = Course_Offline_Folder::where('id',$request->id)->first();
        MakeReport('بحذف فولدر ');
        $folder->delete();
        Session::flash('success','تم الحذف');
        return back();
    }	

    # show folder
    public function showfolder($id)
    {
        $folder = Course_Offline_Folder::with('CourseOfflinevideos','CourseQuizzs')->where('id',$id)->first();
        $folders = Course_Offline_Folder::with('CourseOfflinevideos','CourseQuizzs')->where('courses_id',$folder->courses_id)->get();
        return view('academy::courses.show_folder',compact('folder','folders'));
    }

    # edit courses
    public function Editcourses($id)
    {
        $courses = Course::with('CourseLive','CourseMeeting','CourseOffline.CourseOfflineFolders','CourseQuizz','CourseComments.Customer')->where('id',$id)->first();
        return view('academy::courses.edit_courses',compact('courses'));
    }

    # update courses
    public function Updatecourses(Request $request)
    {
        $request->validate([
            'title'    => 'required',
            'desc'     => 'required',
            'image'    => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        $courses = Course::where('id',$request->id)->first();
        $courses->title   = $request->title;
        $courses->desc    = $request->desc;
        $courses->price_live       = $request->price_live;
        $courses->price_meeting    = $request->price_meeting;
        $courses->price_offline    = $request->price_offline;
        $courses->hourse_live      = $request->hourse_live;
        $courses->hourse_meeting   = $request->hourse_meeting;
        $courses->hourse_offline   = $request->hourse_offline;

            # upload avatar
            if(!is_null($request->image))
            {
    
                File::delete('uploads/courses/avatar/'.$courses->image);
                # upload new image
                $photo=$request->image;
                $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
                Image::make($photo)->save('uploads/courses/avatar/'.$name);
                $courses->image=$name;
            }

           
        $courses->save();
        MakeReport('بتحديث الكورس ' .$courses->title);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete courses
    public function Deletecourses(Request $request)
    {

        $courses = Course::where('id',$request->id)->first();
        File::delete('uploads/courses/avatar/'.$courses->image);
        MakeReport('بحذف الكورس '.$courses->title);
        $courses->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # edit live
    public function Editlive($id)
    {
        $live = Course_Live::where('id',$id)->first();
        return view('academy::courses.edit_live',compact('live'));
    }

    # edit meeting
    public function Editmeeting($id)
    {
        $meeting = Course_Meeting::where('id',$id)->first();
        return view('academy::courses.edit_meeting',compact('meeting'));
    }
 

    # update live 
    public function updatelive(Request $request)
    {
        $request->validate([
            'edit_date_live'         => 'required',
            'edit_time_live'         => 'required',
            'edit_link_live'         => 'required',
            'edit_application'       => 'required',
            'edit_hourse_count_live' => 'required',
        ]);

        $live = Course_Live::where('id',$request->lid)->first();
        $live->date          = $request->edit_date_live;
        $live->time          = $request->edit_time_live;
        $live->link          = $request->edit_link_live;
        $live->application   = $request->edit_application;
        $live->title         = $request->edit_title_live;
        $live->prof          = $request->edit_prof_live;
        $live->hourse_count  = $request->edit_hourse_count_live;
        $live->save();

        MakeReport('بتحديث كورس لايف  ');
        Session::flash('success','تم الحفظ');
        return back();
    }

    # store live 
    public function storelive(Request $request)
    {
        $request->validate([
            'date_live'         => 'required',
            'time_live'         => 'required',
            'link_live'         => 'required',
            'application'       => 'required',
            'hourse_count_live' => 'required',
        ]);

        $courses = Course::where('id',$request->id)->first();

        $live = new Course_Live;
        $live->date          = $request->date_live;
        $live->time          = $request->time_live;
        $live->link          = $request->link_live;
        $live->application   = $request->application;
        $live->title         = $request->title_live;
        $live->prof          = $request->prof_live;
        $live->hourse_count  = $request->hourse_count_live;
        $live->courses_id    = $request->id;
        $live->save();
        $courses->live    = 1;
        $courses->save();

        MakeReport('بتحديث كورس لايف  ');
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete live
    public function Deletelive(Request $request)
    {

        $live = Course_Live::where('id',$request->id)->first();
        MakeReport('بحذف اللايف للكورس ');
        $live->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # store meeting 
    public function storemeeting(Request $request)
    {
        $request->validate([
        'date_Meeting'         => 'required',
        'time_Meeting'         => 'required',
        'location'             => 'required',
        'hourse_count_Meeting' => 'required',
        ]);
        $courses = Course::where('id',$request->id)->first();

        $Meeting = new Course_Meeting;
        $Meeting->date        = $request->date_Meeting;
        $Meeting->time        = $request->time_Meeting;
        $Meeting->location    = $request->location;
        $Meeting->longitude   = $request->longitude;
        $Meeting->latitude    = $request->latitude;
        $Meeting->title       = $request->title_Meeting;
        $Meeting->prof       = $request->prof_Meeting;
        $Meeting->hourse_count= $request->hourse_count_Meeting;
        $Meeting->courses_id  = $courses->id;
        $Meeting->save();

        $courses->meeting    = 1;
        $courses->save();

        MakeReport('  باضافة مقابلة  ');
        Session::flash('success','تم الحفظ');
        return back();
    }
 

    # update meeting 
    public function updatemeeting(Request $request)
    {
        $request->validate([
            'edit_date_meeting'         => 'required',
            'edit_time_meeting'         => 'required',
            'edit_location'             => 'required',
            'edit_hourse_count_meeting' => 'required',
            'edit_title_Meeting' => 'required',
        ]);

        $meeting = Course_Meeting::where('id',$request->mid)->first();
        $meeting->date        = $request->edit_date_meeting;
        $meeting->time        = $request->edit_time_meeting;
        $meeting->location    = $request->edit_location;
        $meeting->longitude   = $request->edit_longitude;
        $meeting->latitude    = $request->edit_latitude;
        $meeting->title       = $request->edit_title_Meeting;
        $meeting->prof       = $request->edit_prof_Meeting;
        $meeting->hourse_count= $request->edit_hourse_count_meeting;
        $meeting->save();

        MakeReport('بتحديث كورس مقابلة  ');
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete meeting
    public function Deletemeeting(Request $request)
    {

        $Meeting = Course_Meeting::where('id',$request->id)->first();
        MakeReport('بحذف المقابلة للكورس ');
        $Meeting->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # store offline 
    public function storeoffline(Request $request)
    {
        $request->validate([
        'prof_offline'        => 'required',
        'hourse_count_offline' => 'required',
        ]);
        $courses = Course::where('id',$request->id)->first();

        $offline = new Course_Offline;
        $offline->prof         = $request->prof_offline;
        $offline->hourse_count  = $request->hourse_count_offline;
        $offline->courses_id  = $courses->id;
        $offline->save();

        $courses->offline    = 1;
        $courses->save();

       

        MakeReport('  باضافة كورس فديوهات  ');
        Session::flash('success','تم الحفظ');
        return back();
    }

    # store videos 
    public function storeovideos(Request $request)
    {
        $request->validate([
        'title' => 'required',
        'desc'  => 'required',
        ]);

        $videos = new Course_Offline_videos;
        # upload video
        if(!is_null($request->video))
        {
            $video=$request->video;
            $namev =date('d-m-y').time().rand().'.'.$video->getClientOriginalExtension();
            $destinationPath = 'uploads/videos';
            $video->move($destinationPath, $namev);
            $videos->video  = $namev;
        }

        $videos->title      = $request->title; 
        $videos->desc       = $request->desc; 
        $videos->offline_id = $request->offline_id;   
        $videos->folder_id  = $request->folder_id;
        $videos->save();

    

        MakeReport('  باضافة  فديو  ');
        Session::flash('success','تم الحفظ');
        return back();
    }
  
    # update video 
    public function updatevideo(Request $request)
    {
        $request->validate([
            'edit_title'        => 'required',
            'edit_desc'         => 'required'
        ]);

        $videos = Course_Offline_videos::where('id',$request->edit_id)->first();
       

        $videos->title      = $request->edit_title; 
        $videos->desc       = $request->edit_desc; 
        $videos->save();

        MakeReport('بتحديث  فديو  ');
        Session::flash('success','تم الحفظ');
        return back();
    }
 

    # update offline 
    public function updateoffline(Request $request)
    {
        $request->validate([
            'edit_prof_offline'        => 'required',
            'edit_hourse_count_offline' => 'required',
        ]);

        $offline = Course_Offline::where('id',$request->oid)->first();
        $offline->prof         = $request->edit_prof_offline;
        $offline->hourse_count  = $request->edit_hourse_count_offline;
        $offline->save();


        MakeReport('بتحديث كورس فديو  ');
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete video
    public function Deletevideo(Request $request)
    {

        $video = Course_Offline_videos::where('id',$request->id)->first();
        File::delete('uploads/videos/'.$video->video);
        MakeReport('بحذف الفديو ');
        $video->delete();
        Session::flash('success','تم الحذف');
        return back();
    }	

    
    # delete comment
    public function Deletecomment(Request $request)
    {

        $comment = Course_Comment::where('id',$request->id)->first();
        MakeReport('بحذف التعليق ');
        $comment->delete();
        Session::flash('success','تم الحذف');
        return back();
    }	


 
}
