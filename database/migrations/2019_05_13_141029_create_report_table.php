<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('credit_id')->unsigned();
            $table->bigInteger('debit_id')->unsigned();
            $table->bigInteger('premuim_id')->unsigned();
            $table->bigInteger('penalty_id')->unsigned();
            $table->foreign('credit_id')->references('id')->on('credit');
            $table->foreign('debit_id')->references('id')->on('debit');
            $table->foreign('premuim_id')->references('id')->on('premuim');
            $table->foreign('employee_id')->references('id')->on('employees');
             $table->foreign('penalty_id')->references('id')->on('penalty');
            $table->softDeletes();
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
        Schema::dropIfExists('report');
    }
}
