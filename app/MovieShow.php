<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * @OA\Xml(name="MovieShow"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="title", type="string", description="Title of a movie or show"),
 * @OA\Property(property="description", type="string", description="Description of a movie or show"),
 * @OA\Property(property="release_date", type="string", description="Release date of a movie or show"),
 * @OA\Property(property="vote_average", type="number", format="float", description="Average vote of a movie or show"),
 * @OA\Property(property="poster_path", type="string", description="Path of a poster image"),
 * @OA\Property(property="popularity", type="number", format="float", description="Popularity of a particular movie or show"),
 * @OA\Property(property="language", type="string", description="Spoken language of a movie or show"),
 * @OA\Property(property="original_title", type="string", description="Title in native language"),
 * @OA\Property(property="type", type="string",enum="[movie,tv-show]", description="Either movie or tv-show"),
 * @OA\Property(property="api_Id", type="integer", description="Id of a movie or show from external API"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", readOnly="true"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly="true"),
 * )
 */
class MovieShow extends Model
{
    protected $fillable = [
        "title", "description", "release_date", "vote_average", "poster_path", "popularity", "language", "original_title", "type", "api_Id"
    ];
}
