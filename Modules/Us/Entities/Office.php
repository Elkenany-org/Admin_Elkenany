<?php

namespace Modules\Us\Entities;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table = 'offices';


   # show count
   public function emails()
   {
       $email     = $this->emails;
       $emails    = json_decode($email);
       return $emails;
   }

   # show count
   public function phones()
   {
       $phone     = $this->phones;
       $phones    = json_decode($phone);
       return $phones;
   }

    # show count
    public function mobiles()
    {
        $mobile     = $this->mobiles;
        $mobiles    = json_decode($mobile);
        return $mobiles;
    }

       # show count
    public function faxs()
    {
        $fax     = $this->faxs;
        $faxs    = json_decode($fax);
        return $faxs;
    }

   
}
