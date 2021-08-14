<?php

use Illuminate\Database\Seeder;
use App\{
	Customer,
	Item,
	ItemSale,
	Sale
};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	for($i=0;$i<10;$i++){
        	Customer::create([
        		"nama" => "nama".$i,
        		"contact" => "contact".$i,
        		"alamat" => "alamat".$i,
        		"ktp" => "default.png"
        	]);
        }      

    	for($i=0;$i<10;$i++){    		 
    		$item = Item::create([
    			"nama_item" => "item".$i,
    			"stock" => 10,
    			"harga_satuan" => 10,
    			"barang" => "default.jpg"
    		]);

    		$sale = Sale::create([
    			"code_transaksi" => $i,
    			"tanggal_transaksi" => now()->toDateTimeString(),
    			"customer_id" => 1,
    			"qty" => 5,
    			"total_diskon" => 10000,
    			"total_harga" => 20000,
    			"total_bayar" => 40000
    		]);

    		ItemSale::create([
    			"sale_id" => $sale->id,
    			"item_id" => $item->id
    		]);
    	}
    }
}
