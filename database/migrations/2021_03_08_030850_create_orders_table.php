<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('origin_id')->nullable();
            $table->longText('store')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_phone')->nullable(); // billing phone
            $table->string('shipping_address')->nullable(); // address 1
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_zip')->nullable();   // postcode
            $table->string('shipping_country')->nullable();
            $table->string('tracking_code')->nullable();  // ko cần
            $table->string('status')->nullable();    // ko cần
            $table->longText('note')->nullable();  //customer_note
            $table->unsignedBigInteger('user_id')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
