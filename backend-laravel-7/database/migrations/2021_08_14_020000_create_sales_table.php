<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string("code_transaksi");
            $table->timestamp("tanggal_transaksi");
            $table->bigInteger("customer_id")->unsigned(); 
            $table->integer("qty");
            $table->integer("total_diskon");
            $table->integer("total_harga");
            $table->integer("total_bayar");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
