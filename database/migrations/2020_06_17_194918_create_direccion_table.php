<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDireccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direccion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono');
            $table->string('calle');
            $table->string('info');
            $table->string('ciudad');
            $table->string('departamento');
            $table->string('codigo');
            $table->bigInteger('orden_id')->unsigned();
            $table->foreign('orden_id')->references('id')->on('ordenes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('direccion');
    }
}
