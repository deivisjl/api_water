<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRechazoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rechazo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('solicitud_id')->unsigned();
            $table->text('motivo');
            $table->foreign('solicitud_id')->references('id')->on('servicio');
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
        Schema::dropIfExists('rechazo');
    }
}
