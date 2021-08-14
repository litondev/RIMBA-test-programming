<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = ["id"];

    public function item_sales(){
    	return $this->hasMany(ItemSale::class);
    }

    public function customer(){
    	return $this->belongsTo(Customer::class);
    }
}
