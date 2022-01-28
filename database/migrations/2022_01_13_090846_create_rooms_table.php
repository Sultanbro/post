<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->index()->references('id')->on('clients')->onDelete('cascade');
            $table->string('name',255);
            $table->integer('capacity')->comment('Вместимость помещения');
            $table->string('address',255);
            $table->integer('floor')->nullable()->comment('Этаж');
            $table->integer('cabinet')->nullable();
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
        Schema::table('rooms', function (Blueprint $table){
            $table->dropForeign(['company_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
        });
        Schema::dropIfExists('rooms');
    }
}
