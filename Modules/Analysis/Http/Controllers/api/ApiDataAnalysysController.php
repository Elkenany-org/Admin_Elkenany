<?php
namespace Modules\Analysis\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\Data_Analysis;
use Validator;

class ApiDataAnalysysController extends Controller
{
    # store user data analysis
    public function StoreDataAnalysis(Request $request)
    {
		$validator = Validator::make($request->all(), [
			'data'   => 'required'
		]);

		foreach ((array) $validator->errors() as $value)
		{
			if(isset($value['data']))
			{
				$msg = 'data is required';
				return response()->json([
					'status'   => '0',
					'message'  => null,
					'error'    => $msg,
				],400);
			}
        }
        
        # check if comming data is array
        $arr = json_decode($request->data);
        if(!is_array($arr))
        {
            $msg = 'data must be an array';
            return response()->json([
                'status'   => '0',
                'message'  => null,
                'error'    => $msg,
            ],400);
        }

        foreach($arr as $v)
        {
            # check if keyword_id is exist
            $main_keyword = Data_Analysis_Keywords::where('id',$v)->first();
            if($main_keyword)
            {
                # increment keyword main count
                $main_keyword->use_count = $main_keyword->use_count + 1;
                $main_keyword->update();

                # check if user has keyword before
                $user_data = User_Data_Analysis::where([['keyword_id',$v],['user_id',1]])->first();
                if($user_data)
                {
                    # increment user analysis count
                    $user_data->use_count = $user_data->use_count + 1;
                    $user_data->update();
                }else{
                    # create new record for user with keyword
                    $user_data = new User_Data_Analysis;
                    $user_data->user_id    = 1;
                    $user_data->keyword_id = $v;
                    $user_data->use_count  = 1;
                    $user_data->save();
                }

                # crate new record for data analysis
                $data_analysis = new Data_Analysis;
                $data_analysis->user_id    = 1;
                $data_analysis->keyword_id = $v;
                $data_analysis->save();
            }
        }

        return response()->json([
            'status'   => '1',
            'message'  => null,
            'error'    => null,
        ],200);
    }
}
