<?php

namespace App\Traits;
use Image;
trait ApiResponse
{
    public function ErrorMsg($msg ,$status = null)
    {
        $status =  $status ?  $status : 400;
        return response()->json(['message'  => null, 'error' => $msg],$status);
    }

    public function ReturnData($data,$msg = null)
    {
        return response()->json(['message'  => $msg, 'error'=> null, 'data'=> $data],200);
    }

    public function ReturnAuthData($data){
        return response()->json(['message'  => "تم التسجيل", 'error'=> null, 'data' => $data],200);
    }

    public function ErrorMsgWithStatus($data)
    {
        return response()->json(['status'   => '0', 'message'  => null, 'error'=> $data],400);
    }

    public function ReturnMsg($msg){
        return response()->json(['message'  => $msg, 'error'    => null,],200);
    }

    /**
     * @return array[]
     */
    public function SortRecentAlpha(){
        $result = [
            [
            'id'  => 1,
            'name'=> 'الأبجدي',
            'value'=> 0
            ],[
            'id'  => 2,
            'name'=> 'الأكثر تداولاً',
            'value'=> 1
            ],
        ];
        return $result;
    }

    public function SortRecentAlphaWithSelected($sort1,$sort2){
        $result = [
            [
                'id'  => 1,
                'name'=> 'الأبجدي',
                'value'=> 0,
                'selected'=>$sort1
            ],[
                'id'  => 2,
                'name'=> 'الأكثر تداولاً',
                'value'=> 1,
                'selected'=>$sort2
            ],
        ];
        return $result;
    }

    public function storeImage($file,$folder)
    {
        $fileName = date('d-m-y').time().rand().'.'.$file->getClientOriginalExtension();


        $image = Image::make($file);
        $image->save('uploads/'.$folder.'/'.$fileName);

        $destinationPath = 'uploads/'.$folder.'/thumbnail/';
        $file->move($destinationPath,$fileName);
        $image->resize(300,300, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$fileName);
//        $uplaod = $file->move($destinationPath,$fileName);
        return $fileName;
    }

    public function StoreImageBase64($file,$folder,$header=""){
        $folderPath = "uploads/".$folder."/";

        if($header == "android"){
            $image_parts = explode(";base64,", $file);
        }else{
            $image_parts = explode(";base64,", $file['fileResult']);
        }
        
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $image_name = date('d-m-y') . time() . rand() . '.'.$image_type;
        $file = $folderPath . $image_name;

        file_put_contents($file, $image_base64);
        return $image_name;
    }
}