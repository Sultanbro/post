<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->string('username')->unique();
            $table->string('email', 55);
            $table->string('password', 128);
            $table->integer('type_id');
            $table->unsignedBigInteger('foreign_id')->index()->nullable();
            $table->unsignedBigInteger('department_id')->index();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('departments');
            $table->index(['foreign_id', 'company_id']);
            $table->unsignedBigInteger('duty_id')->comment('duty->id');
            $table->boolean('active')->default(true);
            $table->date('date_end')->index()->nullable();
            $table->unsignedBigInteger('updated_by')->index();
            $table->unsignedBigInteger('created_by')->index();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_foreign_id_index');
            $table->dropForeign( 'users_department_id_foreign') ;
            $table->dropIndex('users_department_id_index');
            $table->dropForeign('users_company_id_foreign');
            $table->dropIndex('users_company_id_index');
            $table->dropIndex('users_foreign_id_company_id_index');
            $table->dropIndex('users_date_end_index');
            $table->dropIndex('users_updated_by_index');
            $table->dropIndex('users_created_by_index');
        });
        Schema::dropIfExists('users');
    }
}
