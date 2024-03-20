<?php

namespace Modules\Academy\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Academy\Entities\Course;
use Modules\Academy\Entities\Course_Quizz;
use Modules\Academy\Entities\Course_Quizz_Question;
use Modules\Academy\Entities\Course_Quizz_Question_Answer;
use Modules\Academy\Entities\Course_Quizz_Result;
use Modules\Academy\Entities\Course_Quizz_Answer;
use Validator;
use Date;
use URL;
use Image;
use File;
use Auth;
use View;
use Session;

class ApiQuizzesController extends Controller
{
   
}
