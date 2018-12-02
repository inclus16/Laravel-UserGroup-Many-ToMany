<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
    <script src="{{asset('js/app.js')}}"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">{{Auth::user()->name}}</a>
    <div class="navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            @if(Auth::user()->role->id===1)
            <li class="nav-item active">
                <a class="nav-link btn btn-dark" href="#">Администраторы</a>
            </li>
                @endif

        </ul>
    </div>
</nav>
@yield('content')
</body>
</html>