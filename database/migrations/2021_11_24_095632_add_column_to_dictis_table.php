<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToDictisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dictis', function (Blueprint $table) {
            $table->integer('company_id')->index();
            $table->integer('has_child')->nullable()->change();
            $table->integer('foreign_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dictis', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropColumn('company_id');
        });
    }
}
