<?php


namespace Modules\Store\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Store\Entities\Customer;
use Modules\Analysis\Entities\User_Data_Analysis;
use Modules\Analysis\Entities\Data_Analysis_Keywords;
use Modules\Analysis\Entities\Data_Analysis;
use PhpMyAdmin\Utils\HttpRequest;
use Session;
use Image;
use File;
use Auth;
use Illuminate\Support\Facades\Http;

class CustomersController extends Controller
{
    # index
    public function Index()
    {
    	$customers = Customer::latest()->get();
    	return view('store::customers.customers',compact('customers'));
    }

    # add user page
    public function AddCustomerPage()
    {
    	return view('store::customers.add_customer');
    }

    # store user 
    public function StoreCustomer(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|unique:customers',
            'phone'     => 'required|min:11|numeric|unique:customers',
            'password'  => 'required',
            'avatar'    => 'nullable|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $customer = new Customer;
        $customer->name     = $request->name;
        $customer->email    = $request->email;
        $customer->phone    = $request->phone;
        $customer->password = bcrypt($request->password);

        # upload avatar
        if(!is_null($request->avatar))
        {
            $photo=$request->avatar;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/customers/avatar/'.$name);
            $customer->avatar=$name;
        }

        $customer->save();
        MakeReport('بإضافة مستخدم جديد ' .$customer->name);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # edit user
    public function EditCustomer($id)
    {
    	$customer = Customer::with('User_Analysis.Keyword')->where('id',$id)->first();
    	return view('store::customers.edit_customer',compact('customer'));
    }

    # data date user
    public function dataajaxCustomer(Request $request)
    {
        $data = Data_Analysis::whereDate('created_at',$request->date)->where('user_id',$request->id)->pluck('keyword_id')->toArray();
        $ids = array_unique($data);
        $dataPoints = [];
        foreach($ids as $i)
        {
            $count = Data_Analysis::where([['keyword_id',$i],['user_id',$request->id]])->count();
            $name  = Data_Analysis_Keywords::where('id',$i)->first();
            $push['x'] =$i;
            $push['y'] = $count;
            $push['indexLabel'] = '(' . $count .') ' . $name->name;
            $dataPoints[]  = $push;
        }

        return $dataPoints;
    }

    # data user
    public function dataCustomer($id)
    {
        $customer = Customer::with('User_Analysis.Keyword')->where('id',$id)->first();
        
        $dataPoints = [];
        foreach($customer->User_Analysis as $value)
        {
            $push['x'] = $value->keyword_id;
            $push['y'] = $value->use_count;
            $push['indexLabel'] = '(' . $value->use_count .') ' . $value->Keyword->name;
            $dataPoints[] = $push;
        }

        return view('store::customers.data_customer',compact('customer','dataPoints'));
    }

    # update user
    public function UpdateCustomer(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|unique:customers,email,'.$request->id,
            'phone'     => 'required|min:11|numeric|unique:customers,email,'.$request->id,
            'avatar'    => 'nullable|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $customer = Customer::where('id',$request->id)->first();
        $customer->name     = $request->name;
        $customer->email    = $request->email;
        $customer->phone    = $request->phone;
        $customer->memb    = $request->memb;

        # password
        if(!is_null($request->password))
        {
            $customer->password = bcrypt($request->password);
        }

        # upload avatar
        if(!is_null($request->avatar))
        {
        	# delete avatar
	    	if($customer->avatar != 'default.png')
	    	{
	   			File::delete('uploads/customers/avatar/'.$customer->avatar);
	    	}

	    	# upload new avatar
            $photo=$request->avatar;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/customers/avatar/'.$name);
            $customer->avatar=$name;
        }

        $customer->save();
        MakeReport('بتحديث مستخدم ' .$customer->name);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete customer
    public function DeleteCustomer(Request $request)
    {

    	$customer = Customer::where('id',$request->id)->first();
    	if($customer->avatar != 'default.png')
    	{
   			File::delete('uploads/customers/avatar/'.$customer->avatar);
    	}
    	MakeReport('بحذف مستخدم '.$customer->name);
    	$customer->delete();
    	Session::flash('success','تم الحذف');
    	return back();
    }



    public function credit() {
        $token = $this->getToken();
        $order = $this->createOrder($token);
        $paymentToken = $this->getPaymentToken($order, $token);
        return \Redirect::away('https://portal.weaccept.co/api/acceptance/iframes/'.env('PAYMOB_IFRAME_ID').'?payment_token='.$paymentToken);
    }
    public function getToken() {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://accept.paymob.com/api/auth/tokens', [
            'json' => ['api_key' => env('PAYMOB_API_KEY')]
        ]);
        $body = $response->getBody();
        // Explicitly cast the body to a string
        $stringBody = (string) $body;
        $jsonresponse=json_decode($stringBody);
        return $jsonresponse->token;
    }

    public function createOrder($token) {
        $items = [];

        $data = [
            "auth_token" =>   $token,
            "delivery_needed" =>"false",
            "amount_cents"=> "100",
            "currency"=> "EGP",
            "items"=> $items,
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://accept.paymob.com/api/ecommerce/orders', [
            'json' => $data
        ]);
        $body = $response->getBody();
        // Explicitly cast the body to a string
        $stringBody = (string) $body;
        $jsonresponse=json_decode($stringBody);

        return $jsonresponse;
    }

    public function getPaymentToken($order, $token)
    {
        $user=Auth::guard('customer')->user();
        $name = trim($user->name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );


        $billingData = [
            "apartment" => "NA",
            "email" => $user->email,
            "floor" => "NA",
            "first_name" => $first_name,
            "street" => "NA",
            "building" => "NA",
            "phone_number" => $user->phone,
            "shipping_method" => "NA",
            "postal_code" => "NA",
            "city" => "NA",
            "country" => "NA",
            "last_name" => $last_name,
            "state" => "NA",
        ];
        $user_data=["id"=>$user->id];
        $data = [
            "auth_token" => $token,
            "amount_cents" => "100",
            "expiration" => 3600,
            "order_id" => $order->id,
            "billing_data" => $billingData,
            "currency" => "EGP",
            "integration_id" => env('PAYMOB_INTEGRATION_ID'),
            "collector"=>$user_data
        ];


        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://accept.paymob.com/api/acceptance/payment_keys', [
            'json' => $data
        ]);
        $body = $response->getBody();
        // Explicitly cast the body to a string
        $stringBody = (string) $body;
        $jsonresponse=json_decode($stringBody);

        return $jsonresponse->token;
    }

    public function callback(Request $request)
    {
//        $user=Auth::guard('customer')->user();

        $data = $request->all();
        dd($data);

        ksort($data);
        $hmac = $data['hmac'];

        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',

        ];
        $connectedString = '';
        foreach ($data as $key => $element) {
            if(in_array($key, $array)) {
                $connectedString .= $element;
            }
        }
        $secret = env('PAYMOB_HMAC');
        $hased = hash_hmac('sha512', $connectedString, $secret);
        if ( $hased == $hmac) {

            if($data['success']==true){
                ///set memb=1
//                Customer::where('id', $user->id)->update(array('memb' => '1'));
//                redirect('https://admin.elkenany.com/');
                echo 'secure';
                exit;
            }
            else{
//                redirect('https://admin.elkenany.com/');
                echo 'not secure';
                exit;
            }
//            echo 'secure';

        }
        else{
            echo 'not secure'; exit;
        }
    }


}
