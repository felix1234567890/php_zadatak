<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public $genres;
    private $baseUrl = "https://api.themoviedb.org/3/";
    public function __construct()
    {
        $this->genres =  Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.genres'))->json()['genres'];
    }
    public function genres($allGenres)
    {
        return collect($this->genres)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });
    }

    public function home()
    {
        // $movies = Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.popular_movies'))->json()['results'];
        // $tv_shows  = Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.popular_tv_shows'))->json()['results'];
        // $movies = collect($movies)->map(function ($movie) {
        //     $genresFormatted = collect($movie['genre_ids'])->mapWithKeys(function ($value) {
        //         return [$value => $this->genres($this->genres)->get($value)];
        //     })->implode(', ');
        //     return [
        //         "id" => $movie['id'],
        //         "title" => $movie['title'],
        //         "description" => $movie['overview'],
        //         "release_date" => $movie['release_date'],
        //         "vote_average" => $movie['vote_average'],
        //         "poster_path" => 'https://image.tmdb.org/t/p/w500/' . $movie['poster_path'],
        //         "popularity" => $movie['popularity'],
        //         "genres" => $genresFormatted,
        //         "language" =>  $movie['original_language'],
        //         "original_title" => $movie['original_title'],
        //         "type" => 'movie'
        //     ];
        // });
        // $tv_shows = collect($tv_shows)->map(function ($show) {
        //     $genresFormatted = collect($show['genre_ids'])->mapWithKeys(function ($value) {
        //         return [$value => $this->genres($this->genres)->get($value)];
        //     })->implode(', ');
        //     return [
        //         "id" => $show['id'],
        //         "title" => $show['name'],
        //         "description" => $show['overview'],
        //         "release_date" => $show['first_air_date'],
        //         "vote_average" => $show['vote_average'],
        //         "poster_path" => 'https://image.tmdb.org/t/p/w500/' . $show['poster_path'],
        //         "popularity" => $show['popularity'],
        //         "genres" => $genresFormatted,
        //         "language" => $show['original_language'],
        //         "original_title" => $show['original_name'],
        //         "type" => 'tv-show'
        //     ];
        // });
        // $data = array_merge($movies->all(), $tv_shows->all());
        // shuffle($data);
        // foreach ($movies as $movie) {
        //     MovieShow::create($movie);
        // }
        // foreach ($tv_shows as $show) {
        //     MovieShow::create($show);
        // }

        return view('home',);
    }
    public function show($itemId)
    {
        $movie = Http::withToken(env('API_TOKEN'))->get($this->baseUrl . 'movie/' . $itemId)->json();
        return view('show', compact('movie'));
    }
    public function search(Request $request)
    {
        $searchTerm = $request->query('searchTerm');
        $type = $request->query('type');
        switch ($type) {
            case "both":
                $searchMovies = Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_movies') . $searchTerm)->json();
                $searchTVShows = Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_shows') . $searchTerm)->json();
                $searchItems = array_merge($searchMovies['results'], $searchTVShows['results']);
                shuffle($searchItems);
                return $searchItems;
            case "movies":
                return Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_movies') . $searchTerm)->json()['results'];
            case "shows":
                return Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_shows') . $searchTerm)->json()['results'];
            default:
                break;
        }
    }
}
