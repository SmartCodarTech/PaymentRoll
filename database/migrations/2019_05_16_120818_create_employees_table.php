<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->string('lastname', 60);
            $table->string('firstname', 60);
            $table->string('email', 60);
            $table->string('type', 120);
            $table->string('gender');
            $table->date('date_hired');
            $table->date('date_retired');
            $table->string('picture', 60);
            $table->bigInteger('department_id')->unsigned();
            $table->bigInteger('division_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('department');
            $table->foreign('division_id')->references('id')->on('division');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
