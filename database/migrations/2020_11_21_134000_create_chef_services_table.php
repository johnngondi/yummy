<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChefServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chef_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chef_id');
            $table->text('options'); //json with available options and prices e.g.
            $table->unsignedBigInteger('service_id');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('chef_id')
                ->references('id')
                ->on('chefs')
                ->onDelete('cascade');

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chef_services');
    }
}
