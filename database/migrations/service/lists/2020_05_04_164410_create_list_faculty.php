<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListFaculty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Core\Config\ListDatabaseTable::TABLE_LIST_FACULTY, function (Blueprint $table) {
            $table->bigIncrements('idFaculty');
            $table->unsignedBigInteger('idInstitute')->index();
            $table->string('caption')->unique();
            $table->foreign('idInstitute')->references('idInstitute')->on(\App\Core\Config\ListDatabaseTable::TABLE_LIST_INSTITUTE)
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(\App\Core\Config\ListDatabaseTable::TABLE_LIST_FACULTY);
    }
}
