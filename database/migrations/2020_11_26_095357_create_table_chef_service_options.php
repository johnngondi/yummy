<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableChefServiceOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chef_service_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chef_service_id');
            $table->string('title');
            $table->float('price');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('chef_service_id')
                ->references('id')
                ->on('chef_services')
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
        Schema::dropIfExists('chef_service_options');
    }
}
