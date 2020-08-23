<?php

namespace App\Http\Controllers;

use App\MovieShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public $genres;
    private $movies = [];
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
        return view('home',);
    }

    /**
     * @OA\Get(
     * path="/api/search",
     * summary="Search",
     * tags={"HomeController API routes"},
     * description="Search for movies and tv-shows or indiviadually",
     * @OA\Parameter(
     *    description="Search term",
     *    in="query",
     *    name="searchTerm",
     *    required=true,
     *    example="Friends",
     * ),
     * @OA\Response(
     * response=200,
     * description="Data retrieved from an external API",
     * )
     * )
     */
    public function search(Request $request)
    {
        $searchTerm = $request->query('searchTerm');
        $type = $request->query('type');
        $this->movies = MovieShow::where('title', 'like', '%' . $searchTerm . '%')->get();
        dump($this->movies->all());
        if (!$this->movies->all()) {
            dump("Hello");
            $movies =  Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_movies') . $searchTerm)->json()['results'];
            $movies = collect($movies)->map(function ($movie) {
                return [
                    "api_Id" => $movie['id'],
                    "title" => $movie['title'],
                    "description" => $movie['overview'],
                    "release_date" => $movie['release_date'] ?? null,
                    "vote_average" => $movie['vote_average'],
                    "poster_path" =>  $movie['poster_path']
                        ? 'https://image.tmdb.org/t/p/w500/' . $movie['poster_path']
                        : 'https://via.placeholder.com/500x750',
                    "popularity" => $movie['popularity'],
                    "language" =>  $movie['original_language'],
                    "original_title" => $movie['original_title'],
                    "type" => 'movie'
                ];
            });
            $this->movies = $movies;
            foreach ($this->movies as $movie) {
                MovieShow::create($movie);
            }
        }
        return $this->movies;
        // switch ($type) {
        //     case "both":
        //         $searchMovies = Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_movies') . $searchTerm)->json();
        //         $searchTVShows = Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_shows') . $searchTerm)->json();
        //         $searchItems = array_merge($searchMovies['results'], $searchTVShows['results']);
        //         shuffle($searchItems);
        //         return $searchItems;
        //     case "movies":
        //         $movies =  Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_movies') . $searchTerm)->json()['results'];
        //         $movies = collect($movies)->map(function ($movie) {
        //             return [
        //                 "api_Id" => $movie['id'],
        //                 "title" => $movie['title'],
        //                 "description" => $movie['overview'],
        //                 "release_date" => $movie['release_date'] ?? null,
        //                 "vote_average" => $movie['vote_average'],
        //                 "poster_path" => 'https://image.tmdb.org/t/p/w500/' . $movie['poster_path'],
        //                 "popularity" => $movie['popularity'],
        //                 "language" =>  $movie['original_language'],
        //                 "original_title" => $movie['original_title'],
        //                 "type" => 'movie'
        //             ];
        //         });
        //         foreach ($movies as $movie) {
        //             dump($movie);
        //             MovieShow::create($movie);
        //         }
        //     return $movies;
        // case "shows":
        //     return Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_shows') . $searchTerm)->json()['results'];
        // default:
        //     break;
    }

    /**
     * @OA\Get(
     * path="/api/details/{id}",
     * summary="Movie-show details",
     * `tags={"HomeController API routes"},
     * description="Details of a particular movie or tv-show",
     * @OA\Parameter(
     *    description="Id of movie/show",
     *    in="path",
     *    name="id",
     *    required=true,
     *    example="176890",
     * ),
     * @OA\Response(
     * response=200,
     * description="Item retrieved from an external API",
     * )
     * )
     */
    public function findDetails($id)
    {
        $movie = Http::withToken(env('API_TOKEN'))->get($this->baseUrl . "movie/" . $id)->json();
        if ($movie) {
            return $movie;
        } else {
            return Http::withToken(env('API_TOKEN'))->get($this->baseUrl . "tv/" . $id)->json();
        }
    }
}
