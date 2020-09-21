<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('usuario_id')->unsigned();
            $table->bigInteger('comite_id')->nullable()->unsigned();
            $table->bigInteger('sector_id')->unsigned();
            $table->bigInteger('estado_servicio_id')->unsigned();
            $table->text('direccion');
            $table->text('referencia_direccion')->nullable();
            $table->string('no_convenio')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->date('fecha_solicitud');
            $table->date('fecha_aprobacion')->nullable();
            $table->date('fecha_visita')->nullable();
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('sector_id')->references('id')->on('sector');
            $table->foreign('estado_servicio_id')->references('id')->on('estado_servicio');
            $table->foreign('comite_id')->references('id')->on('comite');
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
        Schema::dropIfExists('servicio');
    }
}
