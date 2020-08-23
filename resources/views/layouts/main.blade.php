<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie-Show Finder</title>
    <script src="https://kit.fontawesome.com/7c29817dd5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css')  }}">
</head>
<body>
    <div class="container-md" id="app">
        @yield('content')
    </div>
    <script src="{{asset('js/app.js') }}"></script>
</body>
</html>