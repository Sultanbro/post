<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 400);
            $table->integer('user_id')->index();
            $table->unsignedBigInteger('foreign_id')->index()->nullable();
            $table->unsignedBigInteger('parent_id')->index();
            $table->foreign('parent_id')->references('id')->on('departments');
            $table->unsignedBigInteger('role_id')->index();
            $table->boolean('is_human')->default(true);
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
        Schema::table('branches', function (Blueprint $table) {
//            $table->dropIndex('branches_foreign_id_index');
            $table->dropIndex('branches_user_id_index');
            $table->dropForeign('branches_parent_id_foreign');
            $table->dropIndex('branches_parent_id_index');
            $table->dropIndex('branches_updated_by_index');
            $table->dropIndex('branches_created_by_index');
        });
        Schema::dropIfExists('branches');
    }
}
