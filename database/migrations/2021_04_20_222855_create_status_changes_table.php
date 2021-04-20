<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_changes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
			$table->unsignedBigInteger('ticket_id');
			$table->foreign('ticket_id')->references('id')->on('tickets');
			$table->unsignedBigInteger('changed_by_tech_id');
			$table->foreign('changed_by_tech_id')->references('id')->on('users');
			$table->unsignedBigInteger('status_id');
			$table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_changes');
    }
}
