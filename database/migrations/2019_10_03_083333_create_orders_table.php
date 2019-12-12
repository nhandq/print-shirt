<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->integer('user_id')->nullable(false);
            $table->integer('customer_id')->nullable(false);
            $table->integer('shipping_id')->nullable(false);
            $table->enum('status', ['new', 'printing', 'printed', 'done'])->nullable(false);
            $table->integer('index')->nullable(false)->default(0);
            $table->float('total_money')->nullable(false)->default(0);
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
