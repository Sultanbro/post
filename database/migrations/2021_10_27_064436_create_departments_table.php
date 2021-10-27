<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->foreign('id')->references('id')->on('clients');
            $table->unsignedBigInteger('parent_id')->index();
            $table->string('short_name', 255);
            $table->string('full_name', 255);
            $table->boolean('active')->default(true);
            $table->boolean('is_company')->default(false);
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
        Schema::table('departments', function (Blueprint $table){
            $table->dropUnique(['id']);
            $table->dropForeign(['id']);
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
        });
        Schema::dropIfExists('departments');
    }
}
