<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('source')->nullable();
            $table->string('source_product_id')->nullable();
            $table->string('variant')->nullable();
            $table->string('mockup')->nullable();
            $table->string('design')->nullable();
            $table->unsignedBigInteger('catalog')->nullable();
            $table->unsignedBigInteger('store')->nullable();
            // $table->foreign('store')->references('id')->on('stores');
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
        Schema::dropIfExists('products');
    }
}
