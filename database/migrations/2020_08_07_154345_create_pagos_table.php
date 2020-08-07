<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('mes_id')->unsigned();
            $table->bigInteger('servicio_id')->unsigned();
            $table->bigInteger('tipo_pago_id')->unsigned();
            $table->decimal('monto');
            $table->string('no_boleta');
            $table->string('year');
            $table->foreign('mes_id')->references('id')->on('mes');
            $table->foreign('servicio_id')->references('id')->on('servicio');
            $table->foreign('tipo_pago_id')->references('id')->on('tipo_pago');
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
        Schema::dropIfExists('pago');
    }
}
