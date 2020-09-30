<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutorizacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autorizacion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre_comite');
            $table->string('municipio_departamento');
            $table->string('autorizacion');
            $table->string('registro_contraloria');
            $table->date('fecha');
            $table->integer('activo')->default(0);
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
        Schema::dropIfExists('autorizacion');
    }
}
