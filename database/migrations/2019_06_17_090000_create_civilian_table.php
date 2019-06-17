<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCivilianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('civilian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lastname', 60);
            $table->string('firstname', 60);
            $table->string('service_id', 60);
            $table->string('email', 60);
            $table->string('type', 120);
            $table->decimal('salary',6,0);
            $table->string('gender');
            $table->date('date_hired');
            $table->string('picture', 60);
            $table->bigInteger('department_id')->unsigned();
            $table->bigInteger('bank_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('department');
            $table->foreign('bank_id')->references('id')->on('bank');
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
        Schema::dropIfExists('civilian');
    }
}
