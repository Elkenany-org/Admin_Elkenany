<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Session;
use Image;
use File;
use Auth;

class UsersController extends Controller
{
    # index
    public function Index()
    {
    	$users = User::with('Role')->latest()->get();
    	return view('users.users',compact('users'));
    }

    # add user page
    public function AddUserPage()
    {
    	$roles = Role::latest()->get();
    	return view('users.add_user',compact('roles'));
    }

    # store user 
    public function StoreUser(Request $request)
    {
        $this->validate($request,[
            'name'      => 'required',
            'email'     => 'required|unique:users',
            'password'  => 'required',
            'active'    => 'required',
            'role'      => 'required',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $user = new User;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->active   = $request->active;
        $user->role     = $request->role;
        $user->password = bcrypt($request->password);

        # upload avatar
        if(!is_null($request->avatar))
        {
            $photo=$request->avatar;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/users/avatar/'.$name);
            $user->avatar=$name;
        }

        $user->save();
        MakeReport('بإضافة مستخدم جديد ' .$user->name);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # edit user
    public function EditUser($id)
    {
		$roles = Role::latest()->get();
    	$user = User::with('Role')->where('id',$id)->first();
    	return view('users.edit_user',compact('user','roles'));
    }

    # update user
    public function UpdateUser(Request $request)
    {
        $this->validate($request,[
            'name'      => 'required',
            'email'     => 'required|unique:users,email,'.$request->id,
            'active'    => 'required',
            'role'      => 'required',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $user = User::where('id',$request->id)->first();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->active   = $request->active;
        $user->role     = $request->role;

        # password
        if(!is_null($request->password))
        {
            $user->password = bcrypt($request->password);
        }

        # upload avatar
        if(!is_null($request->avatar))
        {
        	# delete avatar
	    	if($user->avatar != 'default.png')
	    	{
	   			File::delete('uploads/users/avatar/'.$user->avatar);
	    	}

	    	# upload new avatar
            $photo=$request->avatar;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/users/avatar/'.$name);
            $user->avatar=$name;
        }

        $user->save();
        MakeReport('بتحديث مستخدم ' .$user->name);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete user
    public function DeleteUser(Request $request)
    {
    	if(Auth::user()->id == $request->id)
    	{
    		Session::flash('warning','لا يمكن حذف الحساب اثناء تسجيل الدخول به ! ');
    		return back();
    	}
    	$user = User::where('id',$request->id)->first();
    	if($user->avatar != 'default.png')
    	{
   			File::delete('uploads/users/avatar/'.$user->avatar);
    	}
    	MakeReport('بحذف مستخدم '.$user->name);
    	$user->delete();
    	Session::flash('success','تم الحذف');
    	return back();
    }	
}
