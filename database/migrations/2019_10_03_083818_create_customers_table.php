<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(false);
            $table->string('email')->nullable(false);
            $table->string('phone')->nullable(false);
            $table->string('address')->nullable(false);
            $table->date('birthday')->nullable(true);
            $table->enum('gender', ['male', 'female'])->nullable(false);
            $table->string('facebook')->nullable(true)->default(null);
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
        Schema::dropIfExists('customers');
    }
}
