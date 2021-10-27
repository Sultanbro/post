<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->index();
            $table->string('short_name', 255);
            $table->string('full_name', 255);
            $table->string('constant', 40);
            $table->string('constant1', 40);
            $table->integer('has_child')->index();
            $table->boolean('active')->default(true);
            $table->string('text_code')->index();
            $table->integer('num_code')->index();
            $table->unsignedBigInteger('updated_by')->index();
            $table->unsignedBigInteger('created_by')->index();
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
        Schema::table('dictis', function (Blueprint $table){
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['has_child']);
            $table->dropIndex(['text_code']);
            $table->dropIndex(['num_code']);
            $table->dropIndex(['updated_by']);
            $table->dropIndex(['created_by']);
        });
        Schema::dropIfExists('dictis');
    }
}
