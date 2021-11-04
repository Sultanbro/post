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
            $table->bigIncrements('id')->unique();
            $table->foreign('id')->references('id')->on('clients');
            $table->string('email', 55)->unique();
            $table->string('password', 128);
            $table->unsignedBigInteger('foreign_id')->index()->nullable();
            $table->unsignedBigInteger('department_id')->index();
            //$table->foreign('dept_id')->references('id')->on('departments');
            $table->unsignedBigInteger('company_id')->index();
            //$table->foreign('company_id')->references('id')->on('departments');
            $table->index(['foreign_id', 'company_id']);
            $table->unsignedBigInteger('duty_id')->comment('dictis->id');
            $table->boolean('active')->default(true);
            $table->date('date_end')->index()->nullable();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['id']);
            $table->dropForeign(['id']);
            $table->dropUnique(['email']);
            $table->dropIndex('users_foreign_id_index');
            $table->dropIndex('users_department_id_index');
            //$table->dropForeign( 'users_dept_id_foreign') ;
            //$table->dropIndex('users_company_id_index');
            $table->dropIndex(['company_id']);
            //$table->dropForeign('users_company_id_foreign');
            $table->dropIndex('users_foreign_id_company_id_index');
            $table->dropIndex('users_date_end_index');
            $table->dropIndex('users_updated_by_index');
            $table->dropIndex('users_created_by_index');
        });
        Schema::dropIfExists('users');
    }
}
