<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderShippingStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
		    $table->enum('shipping_status', ['pending', 'processing', 'done'])->nullable(false)->default('pending')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('orders', 'shipping_status'))
        {
            Schema::table('orders', function (Blueprint $table)
            {
                $table->dropColumn('shipping_status');
            });
        }
    }
}
