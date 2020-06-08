<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(\App\Core\Config\ListDatabaseTable::TABLE_TICKETS, function (Blueprint $table) {
            $table->bigIncrements('idTicket');
            $table->integer('idAuthor');
            $table->integer('idTicketType');
            $table->string('caption', 255);
            $table->text('description');
            $table->dateTime('startDate');
            $table->dateTime('endDate');
            $table->integer('idTicketStatus');
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
        Schema::dropIfExists(\App\Core\Config\ListDatabaseTable::TABLE_TICKETS);
    }
}