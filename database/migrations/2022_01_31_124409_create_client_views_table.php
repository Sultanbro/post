<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
      CREATE VIEW views_client_info AS
      (
        SELECT u.id, u.client_id, u.department_id, u.foreign_id, u.company_id, u.type_id, c.first_name, c.last_name, c.parent_name, c.short_name, c.full_name, c.birthday
        FROM clients c
          JOIN users u ON u.client_id=c.id
      )
    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS views_client_info');
    }
}
