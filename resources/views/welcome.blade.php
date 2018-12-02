@extends('includes.layout')
@section('content')
    <style>
        .card-overflow{
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
    <div class="container mt-5">
        <div class="row">
            @if(Auth::user()->role->id===1)
                <div class="col-md-4 card bg-dark text-light">
                    <div class="card-header" align="middle">
                        <h3>Администраторы</h3>
                        <p>Колличество: {{$admins->count()}}</p>
                    </div>
                    <div class="card-body">
                        @foreach($admins as $admin)
                            <p class="card-text">Имя: {{$admin->name}}</p>
                            <p class="card-text">Почта: {{$admin->email}}</p>
                            <p class="card-text" data-toggle="tooltip" data-placement="left"
                               title="{{$admin->role->description}}">Права: {{$admin->role->name}}</p>
                            <hr class="bg-light">
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="col-md-4 card bg-dark text-light">
                <div class="card-header" align="middle">
                    <h3>Пользователи</h3>
                    <p>Колличество: {{$users->count()}}</p>
                </div>
                <div class="card-body card-overflow">
                    @foreach($users as $user)
                        <p class="card-text">Имя: {{$user->first_name}}</p>
                        <p class="card-text">Фамилия: {{$user->last_name}}</p>
                        <p class="card-text">Почта: {{$user->email}}</p>
                        <p class="card-text">Участвует в группах: {{$user->groups->count()}}</p>
                        <hr class="bg-light">
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 card bg-dark text-light">
                <div class="card-header" align="middle">
                    <h3>Группы</h3>
                    <p>Колличество: {{$groups->count()}}</p>
                </div>
                <div class="card-body" style="max-height: 200px;">
                    @foreach($groups as $group)
                        <p class="card-text">Название: {{$group->name}}</p>
                        <p class="card-text">Пользователей: {{$group->users->count()}}</p>
                        <hr class="bg-light">
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
