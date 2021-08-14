<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSale extends Model
{
    protected $guarded = ["id"];

    public function item(){
    	return $this->belongsTo(Item::class);
    }
}
