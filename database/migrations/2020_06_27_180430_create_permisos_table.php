<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permiso', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('menu_titulo_id')->default(0);
            $table->string('titulo');
            $table->string('icono')->nullable();
            $table->string('ruta_cliente')->nullable();
            $table->string('ruta_api')->nullable();
            $table->enum('visibilidad', ['visible', 'oculto']);
            $table->text('descripcion');
            $table->integer('orden')->default(0);
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
        Schema::dropIfExists('permiso');
    }
}
