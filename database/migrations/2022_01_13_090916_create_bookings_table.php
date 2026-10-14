<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->index()->references('id')->on('rooms')->onDelete('cascade');
            $table->dateTime('begin');
            $table->dateTime('end');
            $table->string('description',255)->nullable();
            $table->unsignedBigInteger('created_by')->index();
            $table->unsignedBigInteger('updated_by')->index();
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
        Schema::table('bookings', function (Blueprint $table){
            $table->dropForeign(['room_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
        });
        Schema::dropIfExists('bookings');
    }
}
