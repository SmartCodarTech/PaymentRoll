<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePremiumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premium', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('comment',60);
            $table->decimal('amount'6,0);
            $table->bigInteger('officers_id')->unsigned();
            $table->foreign('officers_id')->references('id')->on('officers');
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
        Schema::dropIfExists('premium');
    }
}
