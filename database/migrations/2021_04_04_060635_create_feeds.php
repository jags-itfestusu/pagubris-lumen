<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feeds', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->text('content');
            $table->string('owner_id', 100);
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('parent_feed_id', 100)->nullable();
            $table->foreign('parent_feed_id')->references('id')->on('feeds')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('feeds');
    }
}
