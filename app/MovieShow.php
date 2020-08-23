<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovieShow extends Model
{
    protected $fillable = [
        "title", "description", "release_date", "vote_average", "poster_path", "popularity", "genres", "language", "original_title", "type", "api_Id"
    ];
}
