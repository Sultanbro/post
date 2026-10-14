<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eorders', function (Blueprint $table) {
            $table->id();
            $table->integer('foreign_id')->index()->comment('isn kias связка');
            $table->integer('company_id')->index()->comment('id компани');
            $table->foreignId('type_id')->index()->comment('Вид приказа')->nullable()->constrained('dictis')->nullOnDelete();
            $table->foreignId('department_id')->index()->comment('Подразделение-куратор')->nullable()->constrained('clients')->nullOnDelete();
            $table->string('doc_num')->comment('Номер приказа');
            $table->date('doc_date')->comment('Дата приказа');
            $table->boolean('active')->default(1)->comment('признак - проведен (y) или нет (n) приказ');
            $table->foreignId('client_id')->index()->comment('Сотрудник')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name')->comment('ФИО')->nullable();
            $table->string('short_name')->comment('фамилия, инициалы')->nullable();
            $table->integer('tab_num')->comment('табельный номер')->nullable();
            $table->boolean('place_work')->nullable()->comment('место работы - о-основное, н-неосновное');
            $table->date('date_beg')->nullable()->comment('Дата начала');
            $table->date('date_end')->nullable()->comment('Дата окончания');
            $table->integer('staff_old_id')->nullable()->comment('предыдущая штатная единица');
            $table->integer('staff_new_id')->nullable()->comment('новая штатная единица');
            $table->boolean('sign')->nullable()->comment('тип записи карьеры (основная или по совместительству - y/n)');
            $table->foreignId('release_id')->index()->comment('причина увольнения')->nullable()->constrained('dictis')->nullOnDelete();
            $table->integer('rate')->nullable()->comment('ставка');
            $table->integer('pay')->nullable()->comment('оклад');
            $table->integer('extra_pay')->nullable()->comment('надбавка к окладу');
            $table->foreignId('client_type')->index()->comment('(fk_dicti) класс сотрудника (штатный, нештатный, агент)')->nullable()->constrained('dictis')->nullOnDelete();
            $table->integer('trial_period')->nullable()->comment('испытательный срок (в месяцах)');
            $table->foreignId('agr_type_id')->index()->comment('(fk_dicti) тип трудового договора (контракта)')->nullable()->constrained('dictis')->nullOnDelete();
            $table->string('agr_num')->nullable()->comment('номер трудового договора');
            $table->date('agr_date')->nullable()->comment('дата трудового договора');
            $table->date('agr_date_beg')->nullable()->comment('дата начала трудового (агентского) договора');
            $table->date('agr_date_end')->nullable()->comment('дата окончания трудового (агентского) договора');
            $table->foreignId('vacation_type_id')->index()->comment('(fk_dicti) вид отпуска')->nullable()->constrained('dictis')->nullOnDelete();
            $table->date('period_beg')->nullable()->comment('начало периода, за который предоставлен отпуск');
            $table->date('period_end')->nullable()->comment('окончание периода, за который предоставлен отпуск');
            $table->integer('duration')->nullable()->comment('колличество дней отпуска');
            $table->date('child_birthday')->nullable()->comment('дата рождения ребенка (для отпуска по уходом за ребенком)');
            $table->foreignId('mission_type_id')->index()->comment('(fk_dicti) вид коммандировки')->nullable()->constrained('dictis')->nullOnDelete();
            $table->string('mission_purpose')->nullable()->comment('цель коммандировки');
            $table->foreignId('country_id')->index()->comment('(fk_dicti) страна коммандировки')->nullable()->constrained('dictis')->nullOnDelete();
            $table->foreignId('city_id')->index()->comment('(fk_city) город командировки')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('firm_id')->index()->comment('(fk_clients ) фирма коммандировки')->nullable()->constrained('clients')->nullOnDelete();
            $table->text('doc_text')->nullable()->comment('текст приказа');
            $table->string('remark')->nullable()->comment('примечание');
            $table->foreignId('thanks_id')->index()->comment('(fk_dicti) вид поощрения')->nullable()->constrained('dictis')->nullOnDelete();
            $table->integer('extra_pay_1')->nullable()->comment('вторая надбавка к окладу');
            $table->integer('extra_pay_2')->nullable()->comment('третья надбавка к окладу');
            $table->foreignId('career_reason_id')->index()->comment('(fk_dicti) причина перевода')->nullable()->constrained('dictis')->nullOnDelete();
            $table->date('request_date')->nullable()->comment('дата заявления сотрудника');
            $table->date('date_denounce')->nullable()->comment('дата досрочного выхода из отпуска');
            $table->string('id1c')->nullable()->comment('уникальный код в системе расчета зарплаты');
            $table->string('first_name')->nullable()->comment('имя');
            $table->string('last_name')->nullable()->comment('фамилия');
            $table->string('parent_name')->nullable()->comment('отчество');
            $table->integer('doc_id')->index()->comment('(fk_docs) приказ в таблице с документами')->nullable();
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
        Schema::table('eorders', function (Blueprint $table){
            $table->dropForeign(['type_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['client_id']);
            $table->dropForeign(['release_id']);
            $table->dropForeign(['client_type']);
            $table->dropForeign(['agr_type_id']);
            $table->dropForeign(['vacation_type_id']);
            $table->dropForeign(['mission_type_id']);
            $table->dropForeign(['country_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['firm_id']);
            $table->dropForeign(['thanks_id']);
            $table->dropForeign(['career_reason_id']);
            $table->dropIndex(['type_id']);
            $table->dropIndex(['department_id']);
            $table->dropIndex(['client_id']);
            $table->dropIndex(['release_id']);
            $table->dropIndex(['client_type']);
            $table->dropIndex(['agr_type_id']);
            $table->dropIndex(['vacation_type_id']);
            $table->dropIndex(['mission_type_id']);
            $table->dropIndex(['country_id']);
            $table->dropIndex(['city_id']);
            $table->dropIndex(['firm_id']);
            $table->dropIndex(['thanks_id']);
            $table->dropIndex(['career_reason_id']);
            $table->dropIndex(['doc_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
            $table->dropIndex(['foreign_id']);
            $table->dropIndex(['company_id']);
        });
        Schema::dropIfExists('eorders');
    }
}
