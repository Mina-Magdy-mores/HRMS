<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
       if(auth()->guard('admin')->attempt($validated)){
           return redirect()->route('admin.dashboard');
       }else{
           return redirect()->back()->with('error', 'بيانات الدخول غير صحيحة');
       }

    }
    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
