<?php

namespace App\Http\Controllers;

use App\MovieShow;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public $genres;
    public function __construct()
    {
        $this->genres =  Http::withToken(env('API_TOKEN'))->get(Config::get('constants.genres'))->json()['genres'];
    }
    public function genres($allGenres)
    {
        return collect($this->genres)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });
    }

    public function home()
    {
        $movies = Http::withToken(env('API_TOKEN'))->get(Config::get('constants.popular_movies'))->json()['results'];
        $tv_shows  = Http::withToken(env('API_TOKEN'))->get(Config::get('constants.popular_tv_shows'))->json()['results'];
        $movies = collect($movies)->map(function ($movie) {
            $genresFormatted = collect($movie['genre_ids'])->mapWithKeys(function($value) {
                return [$value => $this->genres($this->genres)->get($value)];
            })->implode(', ');
            return [
                "title" => $movie['title'],
                "description" => $movie['overview'],
                "release_date" => $movie['release_date'],
                "vote_average" => $movie['vote_average'],
                "poster_path" => 'https://image.tmdb.org/t/p/w500/'.$movie['poster_path'],
                "popularity" => $movie['popularity'],
                "genres" => $genresFormatted,
                "language" =>  $movie['original_language'],
                "original_title" => $movie['original_title'],
                "type" => 'movie'
            ];
        });
        $tv_shows = collect($tv_shows)->map(function ($show) {
            $genresFormatted = collect($show['genre_ids'])->mapWithKeys(function($value) {
                return [$value => $this->genres($this->genres)->get($value)];
            })->implode(', ');
            return [
                "title" => $show['name'],
                "description" => $show['overview'],
                "release_date" => $show['first_air_date'],
                "vote_average" => $show['vote_average'],
                "poster_path" => 'https://image.tmdb.org/t/p/w500/'.$show['poster_path'],
                "popularity" => $show['popularity'],
                "genres" => $genresFormatted,
                "language" => $show['original_language'],
                "original_title" => $show['original_name'],
                "type" => 'tv-show'
            ];
        });
 $data = array_merge($movies->all(), $tv_shows->all());
 shuffle($data);
        // foreach ($movies as $movie) {
        //     MovieShow::create($movie);
        // }
        // foreach ($tv_shows as $show) {
        //     MovieShow::create($show);
        // }

        return view('welcome',['items' => $data]);
    }
}
