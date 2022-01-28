<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('foreign_id')->index()->nullable()->comment('Первичный ключ из внеших таблиц');
            $table->integer('parent_id')->index()->nullable()->comment('Предыдущая должность');
            $table->foreignId('user_id')->index()->comment('Сотрудник')->constrained('users');
            $table->foreignId('staff_user_id')->index()->comment('(FK_staff_user) Штатная еденица')->constrained('staff_users');
            $table->boolean('sign')->default(1)->comment('Тип записи карьеры');
            $table->integer('rate')->nullable()->default(1)->comment('Ставка');
            $table->integer('pay')->default(0)->comment('Сумма оклада');
            $table->integer('extra_pay')->default(0)->comment('Ежемесячная надбавка');
            $table->foreignId('eorder_beg_id')->index()->nullable()->comment('(FK_eorder) Приказ об открытии штатной единицы)')->constrained('eorders')->nullOnDelete();
            $table->date('date_beg')->nullable()->comment('Дата открытия штатной еденицы');
            $table->foreignId('eorder_end_id')->index()->nullable()->comment('(FK_eorder) Приказ об открытии штатной единицы')->constrained('eorders')->nullOnDelete();
            $table->date('date_end')->nullable()->comment('Дата закрития штатной еденицы');
            $table->boolean('active')->nullable()->comment('Признак активности');
            $table->string('id1c')->nullable()->comment('Уникальный код в 1с');
            $table->foreignId('release_id')->index()->nullable()->comment('(FK_dictis) Причина увольнения')->constrained('dictis')->nullOnDelete();
            $table->unsignedBigInteger('empl_type_id')->index()->comment('(FK_dictis) Тип сотрудника (штатный, нештатный, агент)');
            $table->foreign('empl_type_id')->references('id')->on('dictis');
            $table->integer('extra_pay1')->nullable()->comment('Второя надбавка');
            $table->integer('extra_pay2')->nullable()->comment('Третья надбавка');
            $table->integer('agreement_id')->nullable()->comment('Трудовой договор');
            $table->integer('company_id')->comment('Компания');
            $table->integer('created_by')->index();
            $table->integer('updated_by')->comment('автор изменения')->index();
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
        Schema::table('career_users', function (Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->dropForeign(['staff_user_id']);
            $table->dropForeign(['eorder_beg_id']);
            $table->dropForeign(['eorder_end_id']);
            $table->dropForeign(['release_id']);
            $table->dropForeign(['empl_type_id']);
            $table->dropIndex(['foreign_id']);
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['staff_user_id']);
            $table->dropIndex(['eorder_beg_id']);
            $table->dropIndex(['eorder_end_id']);
            $table->dropIndex(['release_id']);
            $table->dropIndex(['empl_type_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
        });
        Schema::dropIfExists('career_users');
    }
}
