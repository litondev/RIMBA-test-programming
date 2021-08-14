<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = ["id"];

    public function getBarangAttribute($value){
        return asset("/images/items/" . $value);
    }

    public function getBarangOriginalAttribute($value){
        return $this->attributes["barang"];
    }
}
