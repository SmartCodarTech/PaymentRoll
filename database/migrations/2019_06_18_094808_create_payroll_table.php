<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('over_time')->default(0);
            $table->boolean('notified')->default(0);
            $table->integer('hours')->nullable();
            $table->integer('rate')->nullable();
            $table->integer('gross')->nullable();
            $table->bigInteger('civilian_id')->unsigned();
            $table->foreign('civilian_id')->references('id')->on('civilian');  
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
        Schema::dropIfExists('payroll');
    }
}
