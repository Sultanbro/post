<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->comment('Связка с clients_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('type_id')->comment('Вид связи')->index()->nullable()->constrained('dictis')->nullOnDelete();
            $table->string('phone')->comment('Номер телефона факса итд.');
            $table->string('contact')->comment('Контактное лицо')->nullable();
            $table->foreignId('contact_id')->comment('Контактное лицо. Связка с clients_id')->index()->constrained('clients')->OnDelete();
            $table->string('remark')->comment('Примечание')->nullable();
            $table->integer('created_by')->index();
            $table->integer('updated_by')->index();
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
        Schema::table('employees', function (Blueprint $table){
            $table->dropForeign(['client_id']);
            $table->dropForeign(['type_id']);
            $table->dropForeign(['contact_id']);
            $table->dropIndex(['client_id']);
            $table->dropIndex(['foreign_id']);
            $table->dropIndex(['type_id']);
            $table->dropIndex(['contact_id']);
            $table->dropIndex(['updated_by']);
            $table->dropIndex(['created_by']);
        });
        Schema::dropIfExists('client_contacts');
    }
}
