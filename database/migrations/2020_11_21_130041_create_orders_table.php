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
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('chef_id');
            $table->unsignedBigInteger('courier_id');
            $table->unsignedBigInteger('payment_mode_id')->nullable();
            $table->unsignedBigInteger('user_address_id')->nullable();
            $table->float('sub_total');
            $table->float('discount');
            $table->float('shipping_fee');
            $table->float('total');
            $table->string('order_origin');
            $table->string('order_destination');
            $table->integer('status'); // 0-New | 1-Paid | 2-PayOnDelivery | 3-Dispatched | 4-Cleared | 5-Cancelled
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
