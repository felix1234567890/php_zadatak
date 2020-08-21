<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_shows', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('release_date');
            $table->float('vote_average', 8, 2);
            $table->string('poster_path');
            $table->float('popularity',8,3);
            $table->string('genres')->nullable();
            $table->string('language')->nullable();
            $table->string('original_title');
            $table->enum('type', ['movie', 'tv-show']);
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
        Schema::dropIfExists('movie_shows');
    }
}
