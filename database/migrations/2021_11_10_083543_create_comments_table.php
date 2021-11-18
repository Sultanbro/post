<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->index()->nullable();
            $table->foreignId('post_id')->index()->constrained()->onDelete('cascade');
            $table->text('content');
            $table->foreignId('user_id')->index()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('created_by');
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
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['parent_id']);
        });
        Schema::dropIfExists('comments');
    }
}
