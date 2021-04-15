<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowingUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('following_users', function (Blueprint $table) {
            $table->id();
            $table->string('self_id', 100);
            $table->foreign('self_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('following_id', 100);
            $table->foreign('following_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('following_users');
    }
}
