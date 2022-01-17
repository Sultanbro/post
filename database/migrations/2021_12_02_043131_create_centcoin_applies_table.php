<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentcoinAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centcoin_applies', function (Blueprint $table) {
            $table->id();
            $table->string('type_id');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('total');
            $table->integer('quantity');
            $table->enum('status',['Ожидает','Исполнено','Отказано'])->default('Ожидает');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('centcoin_applies');
    }
}
