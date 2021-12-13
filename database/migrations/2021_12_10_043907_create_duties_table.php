<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDutiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duties', function (Blueprint $table) {
            $table->id();
            $table->integer('foreign_id')->index()->nullable()->comment('Первичный ключ в kias');
            $table->foreignId('position_id')->index()->nullable()->comment('Категория должности')->constrained('dictis')->nullOnDelete();
            $table->string('full_name')->nullable()->comment('Наименнование должности');
            $table->integer('rank')->comment('Ранг для упорядочивания');
            $table->boolean('active')->comment('Признак активности');
            $table->integer('company_id')->comment('Компания');
            $table->integer('created_by')->index();
            $table->integer('updated_by')->index();
            $table->date('id1c')->comment('Уникальный код в 1с');
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
        Schema::table('duties', function (Blueprint $table){
            $table->dropForeign(['position_id']);
            $table->dropIndex(['foreign_id']);
            $table->dropIndex(['position_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
        });
        Schema::dropIfExists('duties');
    }
}
