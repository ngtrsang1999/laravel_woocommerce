<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('data');
            $table->text('title');
            $table->text('url');
            $table->string('status');
            $table->string('create_product')->nullable();
            $table->string('sync_order')->nullable();
            $table->string('tracking')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->datetime('sync_at')->nullable();
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
        Schema::dropIfExists('stores');
    }
}
