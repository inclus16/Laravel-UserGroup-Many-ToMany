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
    <div class="container mt-5" align="middle">
        @if($errors->has('denied'))
            <div class="alert alert-danger col-md-3">{{$errors->first('denied')}}</div>
            @endif
        <form class="col-md-3 card bg-dark text-light" method="POST" action="{{route('enter.post')}}">
            @csrf
            <div class="form-group">
            <label for="email">Почта</label>
                <input id="email" type="email" class="{{$errors->has('email')?'is-invalid':''}} form-control" required name="email"
                       placeholder="Example@mail.com">
                <div class="invalid-feedback">{{$errors->first('email')}}</div>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" class="{{$errors->has('password')?'is-invalid':''}} form-control"  id="password" name="password">
                <div class="invalid-feedback">{{$errors->first('password')}}</div>
            </div>
            <button class="btn btn-dark">Войти</button>
        </form>
    </div>
</body>
</html>