<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('playlist_videos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('playlist_id')->unsigned();
            $table->bigInteger('video_id')->unsigned();
            $table->foreign('playlist_id')->references('id')->on('playlists');
            $table->foreign('video_id')->references('id')->on('videos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlist_videos');
    }
};
