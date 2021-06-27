<?php

namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        if (Auth::check()) {

            return view('main');
        }

        return view('login.login');
    }

    public function login1(Request $request)
    {

        $user = Auth::attempt(['name' => $request->name, 'password' => $request->password]);
        if ($user) {
            return redirect('/');
        }

        return back()->withErrors("Ошибка при авторизации");
    }
}
