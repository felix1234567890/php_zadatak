<?php

namespace App\Http\Controllers;

use App\MovieShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    private $moviesOrShows = [];
    private $baseUrl = "https://api.themoviedb.org/3/";

    public function home()
    {
        return view('home');
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
     * @OA\JsonContent(
     *  @OA\Property(property="movie-show",type="array",
     *  @OA\Items(ref="#/components/schemas/MovieShow")),
     * )
     * )
     * )
     */
    public function search(Request $request)
    {
        $searchTerm = $request->query('searchTerm');
        $type = $request->query('type');
        switch ($type) {
            case "both":
                $this->moviesOrShows = MovieShow::where('title', 'like', '%' . $searchTerm . '%')->get()->all();
                break;
            case 'movies':
                $this->moviesOrShows =  MovieShow::where('title', 'like', '%' . $searchTerm . '%')->where('type', 'movie')->get()->all();
                break;
            case 'shows':
                $this->moviesOrShows =  MovieShow::where('title', 'like', '%' . $searchTerm . '%')->where('type', 'tv-show')->get()->all();
                break;
            default:
                break;
        }
        if (!$this->moviesOrShows) {
            switch ($type) {
                case "both":
                    $searchMovies = Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_movies') . $searchTerm)->json()['results'];
                    $movies = $this->transformMovies($searchMovies);
                    $searchTVShows = Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_shows') . $searchTerm)->json()['results'];
                    $shows = $this->transformTvShows($searchTVShows);
                    $searchItems = array_merge($movies->all(), $shows->all());
                    shuffle($searchItems);
                    foreach ($searchItems as $movieOrShow) {
                        $item = MovieShow::create($movieOrShow);
                        array_push($this->moviesOrShows, $item->getOriginal());
                    }
                    break;
                case "movies":
                    $movies =  Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_movies') . $searchTerm)->json()['results'];
                    $movies = $this->transformMovies($movies);
                    foreach ($movies as $movie) {
                        $item = MovieShow::create($movie);
                        dump($item->getOriginal());
                        array_push($this->moviesOrShows, $item->getOriginal());
                    }
                    break;
                case "shows":
                    $shows = Http::withToken(env('API_TOKEN'))->get($this->baseUrl . Config::get('constants.search_shows') . $searchTerm)->json()['results'];
                    $shows = $this->transformTvShows($shows);
                    foreach ($shows as $show) {
                        $item = MovieShow::create($show);
                        array_push($this->moviesOrShows, $item->getOriginal());
                    }
                    break;
                default:
                    break;
            }
        }
        return $this->moviesOrShows;
    }
    /**
     * @OA\Get(
     * path="/api/details/{id}",
     * summary="Movie-show details",
     * tags={"HomeController API routes"},
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
        $movieOrShow = MovieShow::where('id', $id)->firstOrFail();
        if ($movieOrShow['type'] == 'movie') {
            $data = Http::withToken(env('API_TOKEN'))->get($this->baseUrl . "movie/" . $movieOrShow['api_Id'])->json();
            return response()->json(['type' => 'movie', $data]);
        } else {
            $data =  Http::withToken(env('API_TOKEN'))->get($this->baseUrl . "tv/" . $movieOrShow['api_Id'])->json();
            return response()->json(['type' => 'tv-show', $data]);
        }
    }
    private function transformMovies($movies)
    {
        return collect($movies)->map(function ($movie) {
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
    }
    private function transformTvShows($shows)
    {
        return collect($shows)->map(function ($show) {
            return [
                "api_Id" => $show['id'],
                "title" => $show['name'],
                "description" => $show['overview'],
                "release_date" => $show['first_air_date'] ?? null,
                "vote_average" => $show['vote_average'],
                "poster_path" => $show['poster_path']
                    ? 'https://image.tmdb.org/t/p/w500/' . $show['poster_path']
                    : 'https://via.placeholder.com/500x750',
                "popularity" => $show['popularity'],
                "language" => $show['original_language'],
                "original_title" => $show['original_name'],
                "type" => 'tv-show'
            ];
        });
    }
}
