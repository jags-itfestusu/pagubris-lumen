<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaFeedResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_feed_resources', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('feed_id', 100);
            $table->foreign('feed_id')->references('id')->on('feeds');
            $table->text('filepath');
            $table->text('url');
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
        Schema::dropIfExists('media_feed_resources');
    }
}
