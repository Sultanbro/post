<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->integer('foreign_id')->index()->comment('Первичный ключ от КИАС');
            $table->foreignId('region_id')->nullable()->index()->comment('Регион')->constrained()->nullOnDelete();
            $table->foreignId('country_id')->index()->comment('Страна')->constrained('dictis')->onDelete('cascade');
            $table->boolean('active')->default(true)->comment('Признак активности');
            $table->json('codes')->nullable()->comment('Почтовый индекс и остальные коды');
            $table->string('name')->nullable()->comment('Полное наименование');
            $table->string('score')->nullable()->comment('Сокрашение типа населенного пункта');
            $table->integer('population')->index()->nullable()->comment('Численность населения в тыс.чел.');
            $table->integer('company_id')->index();
            $table->integer('created_by')->index();
            $table->integer('updated_by')->index();
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
        Schema::table('cities', function (Blueprint $table){
            $table->dropForeign(['region_id']);
            $table->dropForeign(['country_id']);
            $table->dropIndex(['foreign_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
            $table->dropIndex(['region_id']);
            $table->dropIndex(['country_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['population']);
        });
        Schema::dropIfExists('cities');
    }
}
