<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = ["id"];

    public function getKtpAttribute($value){
        return asset("/images/ktps/" . $value);
    }

    public function getKtpOriginalAttribute($value){
        return $this->attributes["ktp"];
    }
}
