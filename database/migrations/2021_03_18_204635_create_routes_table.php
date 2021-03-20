<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('airline');
            $table->string('airline_id');
            $table->unsignedBigInteger('source_airport_id');
            $table->unsignedBigInteger('destination_airport_id');
            $table->string('codeshare', 1);
            $table->integer('stops');
            $table->string('equipment', 3);
            $table->float('price');
            $table->timestamps();

            $table->foreign('source_airport_id')
                ->references('id')
                ->on('airports')
                ->onDelete('cascade');

            $table->foreign('destination_airport_id')
                ->references('id')
                ->on('airports')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
