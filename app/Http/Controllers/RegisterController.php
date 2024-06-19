<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function redirect(){
        return view('register');
    }

    public function register(Request $request){
        $validData = $request->validate([
            'name' => 'required|max:255',
            'email' => ['required','email','unique:users'],
            'password' => 'required|min:3|max:255'
        ]);

        User::create($validData);

        return redirect('/login');

    }

}