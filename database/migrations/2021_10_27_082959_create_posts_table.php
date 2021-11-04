<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignId('user_id')->index()->constrained();
            $table->unsignedBigInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('departments');
            $table->unsignedBigInteger('group_id')->index()->nullable();
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
        Schema::table('posts', function (Blueprint $table) {
            $table->dropPrimary('id');
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['group_id']);
            $table->dropIndex(['company_id']);
            $table->dropForeign(['company_id']);
        });
        Schema::dropIfExists('posts');
    }
}
