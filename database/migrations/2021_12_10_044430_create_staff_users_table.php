<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('foreign_id')->index()->nullable()->comment('Первичный ключ из внеших таблиц');
            $table->foreignId('department_id')->index()->comment('(FK_Department) Подразделение')->constrained('departments');
            $table->integer('duty_id')->index()->comment('Должность');
            $table->integer('pay')->nullable()->default(0)->comment('Оклад (тарифная ставка)');
            $table->foreignId('eorder_beg_id')->index()->nullable()->comment('(FK_eorder) Приказ об открытии штатной единицы)')->constrained('eorders')->nullOnDelete();
            $table->date('date_beg')->nullable()->comment('Дата открытия штатной еденицы');
            $table->foreignId('eorder_end_id')->index()->nullable()->comment('(FK_eorder) Приказ об открытии штатной единицы')->constrained('eorders')->nullOnDelete();
            $table->date('date_end')->nullable()->comment('Дата закрития штатной еденицы');
            $table->boolean('active')->nullable()->comment('Признак активности');
            $table->string('id1c')->nullable()->comment('Уникальный код в 1с');
            $table->integer('company_id')->comment('Компания');
            $table->integer('created_by')->index();
            $table->integer('updated_by')->comment('автор изменения')->index();
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
        Schema::table('staff_users', function (Blueprint $table){
            $table->dropForeign(['department_id']);
            $table->dropForeign(['eorder_beg_id']);
            $table->dropForeign(['eorder_end_id']);
            $table->dropIndex(['foreign_id']);
            $table->dropIndex(['eorder_beg_id']);
            $table->dropIndex(['eorder_end_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
        });
        Schema::dropIfExists('staff_users');
    }
}
