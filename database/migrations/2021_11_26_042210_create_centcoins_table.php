<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentcoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centcoins', function (Blueprint $table) {
            $table->id();
            $table->string('type_id');
            $table->string('description');
            $table->integer('quantity');
            $table->integer('total');
            $table->integer('user_id');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('created_by');
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('centcoins');
    }
}
