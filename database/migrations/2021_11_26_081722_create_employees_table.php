<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->foreignId('id')->comment('Связка с clients id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('nation_id')->index()->comment('Нацианальность.  Связка с dictis_id')->nullable()->constrained('dictis')->nullOnDelete();
            $table->foreignId('science_id')->index()->comment('Ученая степень.  Связка с dictis_id')->nullable()->constrained('dictis')->nullOnDelete();
            $table->string('personal_file')->nullable()->comment('Личное дело');
            $table->date('begin_work')->nullable()->comment('Дата начало трудовой деятельности');
            $table->date('perman_work')->nullable()->comment('Дата начало непрерывного трудового стажа');
            $table->boolean('place_work')->default(false)->comment('Место работы - Основное\Неосновное');
            $table->integer('trial_period')->nullable()->comment('Дата окончание испытательного срока');
            $table->string('old_name')->nullable()->comment('Фамилия до смены');
            $table->date('date_change_family')->nullable()->comment('Дата смены фамилии');
            $table->foreignId('country_id')->nullable()->comment('Страна.  Связка с dictis_id')->constrained('dictis')->nullOnDelete();
            $table->foreignId('region_id')->nullable()->comment('Регион.  Связка с regions_id')->constrained('regions')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->comment('Город.  Связка с cities_id')->constrained('cities')->nullOnDelete();
            $table->string('district')->nullable()->comment('Район');
            $table->date('date_release')->nullable()->comment('Дата увольнения');
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
            $table->dropForeign(['id']);
            $table->dropForeign(['nation_id']);
            $table->dropForeign(['science_id']);
            $table->dropForeign(['country_id']);
            $table->dropForeign(['region_id']);
            $table->dropForeign(['city_id']);
            $table->dropIndex(['nation_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
            $table->dropIndex(['science_id']);
        });
        Schema::dropIfExists('employees');
    }
}
