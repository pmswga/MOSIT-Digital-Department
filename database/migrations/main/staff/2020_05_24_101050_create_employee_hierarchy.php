<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeHierarchy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Core\Config\ListDatabaseTable::TABLE_EMPLOYEE_HIERARCHY, function (Blueprint $table) {
            $table->bigIncrements('idEmployeeHierarchy');
            $table->integer('idEmployeeSuper');
            $table->integer('idEmployeeSub');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(\App\Core\Config\ListDatabaseTable::TABLE_EMPLOYEE_HIERARCHY);
    }
}