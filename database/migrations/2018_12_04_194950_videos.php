<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Videos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category');
            $table->string('type')->nullable();
            $table->integer('duration')->default(0);
            $table->integer('views')->default(0);
            $table->string('thumbnail')->nullable();
            $table->string('path');
            $table->string('file_name');
            $table->boolean('is_valid')->default(false);
            $table->boolean('has_gif')->default(false);
            $table->timestamp('recorded_at')->nullable();
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
        Schema::dropIfExists('videos');
    }
}
