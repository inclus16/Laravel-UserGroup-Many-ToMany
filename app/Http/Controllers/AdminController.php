<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function enterGet()
    {
        return view('enter');
    }

    public function enterPost(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
               'email'=>'required|email',
               'password'=>'required'
            ],
            [
                'email.required'=>'Заполните поле',
                'email.email'=>'Введите валидный email адресс',
                'password.required'=>'Заполните поле'
            ]);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }
        $checkAuth = Auth::attempt($request->only('email','password'));
        if(!$checkAuth){
            return redirect()->back()->withErrors(['denied'=>'В доступе отказано']);
        }else{
            return redirect()->route('index');
        }
    }

    public function index()
    {
        $data=[
          'users'=>User::all(),
          'groups'=>Group::all()
        ];
        if(Auth::user()->role->id===1){
            $data['admins']=Admin::all();
        }
        return view('welcome',$data);
    }
}
