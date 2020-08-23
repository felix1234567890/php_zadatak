<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <script src="https://kit.fontawesome.com/7c29817dd5.js" crossorigin="anonymous"></script>
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css')  }}">

</head>

<body>
    <div class="container-md" id="app">
        <search-component></search-component>
        <div class="row">
            @foreach($items as $item)
            <div class="col-md-4 mt-5">
                <div class="card" style="width: 18rem;">
                <a href="{{route('home.show', $item['id'])}}">
                <img src="{{ $item['poster_path'] }}" class="card-img-top" alt="...">
                </a>
                   
                    <div class="card-body">
                    <a href="{{route('home.show', $item['id'])}}">
                        <h5 class="card-title">{{$item['title']}}</h5></a>
                        <p class="card-text">
                            @if(strlen($item['description'])>150)
                            {{substr(strip_tags($item['description']),0,150)}}...
                            @else
                            {{$item['description']}}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <script src="{{asset('js/app.js') }}"></script>
</body>

</html>
