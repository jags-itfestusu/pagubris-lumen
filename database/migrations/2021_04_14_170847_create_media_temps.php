<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTemps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_temps', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->text('filepath');
            $table->timestamp('expired');
            $table->string('owner_id', 100);
            $table->foreign('owner_id')->references('id')->on('users');
            $table->string('disposition');
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
        Schema::dropIfExists('media_temps');
    }
}
