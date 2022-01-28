<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->integer('foreign_id')->index()->comment('Первичный ключ от КИАС');
            $table->integer('parent_id')->index()->nullable()->comment('Родительски id');
            $table->string('name')->comment('Название региона');
            $table->string('socr')->nullable()->comment('Тип региона');
            $table->boolean('active')->default(true)->comment('Признак активности');
            $table->json('codes')->nullable()->comment('Почтовый индекс и остальные коды');
            $table->boolean('is_federal')->default(0)->comment('Данный регион фиктивный - это город федерального значения (=1)');
            $table->integer('company_id')->index();
            $table->integer('created_by')->index();
            $table->integer('updated_by')->index();
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
        Schema::table('regions', function (Blueprint $table){
            $table->dropIndex(['foreign_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
        });
        Schema::dropIfExists('regions');
    }
}
