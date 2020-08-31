<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('primer_nombre');
            $table->string('segundo_nombre')->nullable();
            $table->string('tercer_nombre')->nullable();
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->string('email')->unique();
            $table->string('correo_electronico')->unique()->nullable();
            $table->text('direccion_residencia');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
