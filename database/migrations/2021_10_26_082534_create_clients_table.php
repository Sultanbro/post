<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('parent_name', 255)->nullable();
            $table->string('short_name', 255);

            $table->string('full_name', 400);
            $table->string('iin', 12)->index()->nullable();
            $table->integer('sex')->comment('0-жен, 1-муж')->nullable();
            $table->boolean('resident_bool')->nullable();
            $table->boolean('juridical_bool');
            $table->json('address')->nullable();
            $table->unsignedBigInteger('type_id')->index();
            $table->date('birthday')->index()->nullable();
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
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex('clients_iin_index');
            $table->dropIndex('clients_type_id_index');
            $table->dropIndex('clients_birthday_index');
            $table->dropIndex('clients_updated_by_index');
            $table->dropIndex('clients_created_by_index');
        });
        Schema::dropIfExists('clients');
    }
}
