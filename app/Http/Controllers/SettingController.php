<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use App\Configuration;
use App\Social;
use View;
use Session;
use Image;
use File;

class SettingController extends Controller
{

    public function __construct()
    {
        $setting       = Setting::first();
        $configration  = Configuration::first();
      
        View::share([
            'setting'       => $setting,
            'configration'  => $configration,
           
        ]);
    }

    # setting page
    public function Index()
    {
        $social = Social::latest()->get();
    	return view('setting.setting',compact('social'));
    }

    # update main setting
    public function UpdateMainSetting(Request $request)
    {
        $this->validate($request,[
            'name'      =>'required'
        ]);

        $setting = Setting::first();
        $setting->name  = $request->name;
        $setting->phone = $request->phone;
        $setting->email = $request->email;
        # upload avatar
        if(!is_null($request->image))
        {
            # delete avatar
            if($setting->image != 'home_bg.png')
            {
                File::delete('uploads/home/'.$setting->image);
            }

            # upload new avatar
            $photo=$request->image;
            $name =date('d-m-y').time().rand().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->save('uploads/home/'.$name);
            $setting->image=$name;
        }
        $setting->save();
        MakeReport('بتحديث الإعدادات الاساسية');
        Session::flash('success','تم حفظ الإعدادات');
        return back();
    }

    # update copyrigth
    public function UpdateMainCopyrigth(Request $request)
    {
        $setting = Setting::first();
        $setting->copyrigth  = $request->copyrigth;
        $setting->tagged     = $request->tagged;
        $setting->save();
        MakeReport('بتحديث الحقوق');
        Session::flash('success','تم حفظ الإعدادات');
        return back();
    }

    # update about app
    public function UpdateMainAboutApp(Request $request)
    {
        $setting = Setting::first();
        $setting->about_ar  = $request->about_ar;
        $setting->about_en  = $request->about_en;
        $setting->save();
        MakeReport('بتحديث عن التطبيق');
        Session::flash('success','تم حفظ الإعدادات');
        return back();
    }

    # update SMTP
    public function UpdateSMTP(Request $request)
    {
        $this->validate($request,[
            'smtp_type'        =>'nullable|max:190',
            'smtp_username'    =>'nullable|max:190',
            'smtp_sender_email'=>'nullable|max:190',
            'smtp_sender_name' =>'nullable|max:190',
            'smtp_password'    =>'nullable|max:190',
            'smtp_port'        =>'nullable|max:190',
            'smtp_host'        =>'nullable|max:190',
            'smtp_encryption'  =>'nullable|max:190',
        ]);

        $configuration = Configuration::first();
        $configuration->smtp_type         = $request->smtp_type;
        $configuration->smtp_username     = $request->smtp_username;
        $configuration->smtp_sender_email = $request->smtp_sender_email;
        $configuration->smtp_sender_name  = $request->smtp_sender_name;
        $configuration->smtp_password     = $request->smtp_password;
        $configuration->smtp_port         = $request->smtp_port;
        $configuration->smtp_host         = $request->smtp_host;
        $configuration->smtp_encryption   = $request->smtp_encryption;
        $configuration->save();
         MakeReport('بتحديث عن ال smtp');
        Session::flash('success','تم حفظ التعديلات');
        return back();
    }

    # update sms
    public function UpdateSmS(Request $request)
    {
        $configuration = Configuration::first();
        $configuration->sms_number      = $request->sms_number;
        $configuration->sms_password    = $request->sms_password;
        $configuration->sms_sender_name = $request->sms_sender_name;
        $configuration->save();
        MakeReport('بتحديث عن ال sms');
        Session::flash('success','تم حفظ الإعدادات');
        return back();
    }

    # update onesignal
    public function UpdateOneSignal(Request $request)
    {
        $configuration = Configuration::first();
        $configuration->oneSignal_application_id = $request->oneSignal_application_id;
        $configuration->oneSignal_authorization  = $request->oneSignal_authorization;
        $configuration->save();
        MakeReport('بتحديث عن ال onesignal');
        Session::flash('success','تم حفظ التعديلات');
        return back(); 
    }

    # update FCM
    public function UpdateFCM(Request $request)
    {
        $configuration = Configuration::first();
        $configuration->fcm_server_key = $request->fcm_server_key;
        $configuration->fcm_sender_id  = $request->fcm_sender_id;
        $configuration->save();
        MakeReport('بتحديث عن ال fcm');
        Session::flash('success','تم حفظ التعديلات');
        return back(); 
    }

    # store dynamic setting
    public function StoreDynamicSetting(Request $request)
    {
        $this->validate($request,[
            'key'      =>'required|max:190',
            'value'    =>'required|max:190'
        ]);

        $setting = new Dynamic_Setting;
        $setting->key   = $request->key;
        $setting->value = $request->value;
        $setting->save();
        MakeReport('بإضافة إعدادات إضافية '. $setting->key);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # update dynamic setting
    public function UpdateDynamicSetting(Request $request)
    {
        $this->validate($request,[
            'edit_id'       =>'required',
            'edit_key'      =>'required|max:190',
            'edit_value'    =>'required|max:190'
        ]);

        $setting = Dynamic_Setting::where('id',$request->edit_id)->first();
        $setting->key   = $request->edit_key;
        $setting->value = $request->edit_value;
        $setting->save();
        MakeReport('بتعديل إعدادات إضافية '. $setting->key);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete dynamic setting
    public function DeleteDynamicSetting(Request $request)
    {
        $this->validate($request,[
            'id'     =>'required'
        ]);

        $setting = Dynamic_Setting::where('id',$request->id)->first();
        MakeReport('بحذف إعدادات إضافية '. $setting->key);
        $setting->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # add social
    public function Storesocial(Request $request)
    {
        $this->validate($request,[
            'social_name'   => 'required|max:190',
            'social_icon'   => 'required|max:500',

        ]);

        $social = new Social;
        $social->social_name = $request->social_name;
        $social->social_icon = $request->social_icon;
        $social->save();

        Session::flash('success','تم الحفظ');
        MakeReport('بإضافة  موقع تواصل '.$social->social_name);
        return back();
    }

    # update social
    public function socialUpdate(Request $request)
    {
        $this->validate($request,[
            'edit_social_name'   => 'required|max:190',
            'edit_social_icon'   => 'required|max:500',
        ]);

        $social = Social::findOrFail($request->edit_social_id);
        $social->social_name = $request->edit_social_name;
        $social->social_icon = $request->edit_social_icon;
        $social->save();

        Session::flash('success','تم التحديث');
        MakeReport('بتحديث  موقع تواصل '.$social->social_name);
        return back();
    }

    # delete social
    public function Deletesocial(Request $request)
    {
        $social = Social::where('id',$request->social_id)->first();
        MakeReport('بحذف  موقع تواصل '.$social->social_name);
        $social->delete();
        Session::flash('success','تم الحذف');
        return back();
    }
}
