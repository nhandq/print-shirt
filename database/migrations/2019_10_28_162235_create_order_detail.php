<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->nullable(false);
            $table->enum('type', ['ao', 'ly', 'op'])->nullable(false);
            $table->string('size', 10)->nullable(false);
            $table->integer('number')->nullable(false);
            $table->string('image', 5000)->nullable(true)->default(null);
            $table->integer('price')->nullable(false);
            $table->longText('note')->nullable(true)->default(null);
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
        Schema::dropIfExists('order_detail');
    }
}
