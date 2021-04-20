<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_changes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
			$table->unsignedBigInteger('ticket_id');
			$table->foreign('ticket_id')->references('id')->on('tickets');
			$table->unsignedBigInteger('changed_by_tech_id');
			$table->foreign('changed_by_tech_id')->references('id')->on('users');
			$table->unsignedBigInteger('new_tech_id');
			$table->foreign('new_tech_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignment_changes');
    }
}
