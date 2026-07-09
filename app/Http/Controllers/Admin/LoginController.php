<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;

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
            $user = auth()->guard('admin')->user();
            if ($user->is_employee == 1 && $user->allow_login == 0) {
                auth()->guard('admin')->logout();
                return redirect()->back()->with('error', 'تم إيقاف صلاحية دخولك إلى النظام من قبل الإدارة.');
            }
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