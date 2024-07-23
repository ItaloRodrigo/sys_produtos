<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', length: 50);
            $table->string('description', length: 200);
            $table->double('price')->unsigned();
            $table->timestamp('date_expiration')->nullable();
            $table->string('image')->unique();
            $table->bigInteger('id_categoria')->unsigned();
            $table->foreign('id_categoria')->references('id')->on('categoria');
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
        Schema::dropIfExists('produto');
    }
};
