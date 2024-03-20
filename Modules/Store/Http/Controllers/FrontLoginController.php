<?php


namespace Modules\Store\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Store\Entities\Customer;
use App\Setting;
use Socialite;
use Exception;
use Session;
use Image;
use File;
use Auth;
use Str;
use Date;
use Validator;

class FrontLoginController extends Controller
{

    public function login(Request $request)
    {
      // Validate the form data
      $request->validate([
        'phone' => 'required|',
        'password' => 'required|'
      ]);

      // Attempt to log the user in
      if(is_numeric($request->get('phone'))){
        if (Auth::guard('customer')->attempt(['phone' => $request->phone, 'password' => $request->password], $request->remember)) {
            // if successful, then redirect to their intended location
            return redirect()->intended(route('fronts'));
        }
    }else {
        if (Auth::guard('customer')->attempt(['email' => $request->phone, 'password' => $request->password], $request->remember)) {
            // if successful, then redirect to their intended location
            return redirect()->intended(route('fronts'));
        }
    }

      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()->withInput($request->only('phone', 'remember'));
    }

    public function showLoginForm (){
      return view('store::fronts.customer_login');
    }	

    public function showcustomerRegisterForm()
    {
        return view('store::fronts.customer_register');
    }

    # register user 
    public function register(Request $request)
    {
        $validation = Validator::make( $request->all(), [
            'name'      => 'required',
            'email'     => 'required|unique:customers',
            'phone'     => 'required|min:11|numeric|unique:customers',
            'password'  => 'required|min:6',
            'avatar'    => 'nullable|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ( $validation->fails() ) {
            // change below as required
          
            return back()->withErrors($validation->errors());
        }else{
            $customer = new Customer;
            $customer->name     = $request->name;
            $customer->email    = $request->email;
            $customer->phone    = $request->phone;
            $customer->memb    = "1";
            $customer->password = bcrypt($request->password);


            if(!is_null($request->memb))
            {
                $customer->memb          = $request->memb;
            }

            $customer->save();
    
            if (Auth::guard('customer')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
    
                return redirect()->intended(route('fronts'));
            }
        }

     
    }

    public function redirectToGoogle()      // this function direct go to google
    {
        return Socialite::driver('google')->redirect();
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();

        $authUser = Customer::where('email', $user->email)->first();
        if($authUser){
          // Login
          Auth::guard('customer')->login($authUser);
        
          return redirect()->intended(route('fronts'));
        }
        else{
            $newUser = new Customer();
            $newUser->email = $user->email;
            $newUser->name = $user->name;
            $newUser->memb    = "1";
            $newUser->save();

            Auth::guard('customer')->login($newUser);
        
            return redirect()->intended(route('fronts'));
        }
    }

    public function handleGoogleCallback()  // this function get user login of googlre
    {
        

            $user = Socialite::driver('google')->user();
      
            $authUser = Customer::where('email', $user->email)->first();
            if($authUser){
              // Login
              Auth::guard('customer')->login($authUser);
            
              return redirect()->intended(route('fronts'));
            }
            else{
                $newUser = new Customer();
                $newUser->email = $user->email;
                $newUser->name = $user->name;
                $newUser->google_id = $user->id;
                $newUser->memb    = "1";
                $newUser->save();
    
                Auth::guard('customer')->login($newUser);
            
                return redirect()->intended(route('fronts'));
            }
    
    }

    # edit user
    public function EditCustomer()
    {
        $customer = Customer::where('id', Auth::guard('customer')->user()->id)->first();
        $setting       = Setting::first();
        return view('store::fronts.edit_customer',compact('customer','setting'));
    }

    # update user
    public function UpdateCustomer(Request $request)
    {
     
        $validation = Validator::make( $request->all(), [
            'email'     => 'required|unique:customers,email,'.$request->id,
            'phone'     => 'required|min:11|numeric|unique:customers,email,'.$request->id,
            'password'  => 'min:6',
            'avatar'    => 'nullable|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ( $validation->fails() ) {
            // change below as required
          
            return back()->withErrors($validation->errors());
        }else{

            $customer = Customer::where('id',$request->id)->first();
            if(!is_null($request->name))
            {
                $customer->name     = $request->name;
            }
            if(!is_null($request->email))
            {
                if($request->email != $customer->email)
                {
                    $customer->email    = $request->email;
                }
            }

            if(!is_null($request->phone))
            {
                if($request->phone != $customer->phone)
                {
                    $customer->phone    = $request->phone;
                }
            }

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
            return back();
        }
    }
    
}
