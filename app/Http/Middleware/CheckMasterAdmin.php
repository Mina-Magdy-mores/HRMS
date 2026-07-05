<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMasterAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth('admin')->user();
        if (!$admin || !$admin->is_master_admin) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'عفواً، هذا الإجراء متاح فقط لمدير النظام الرئيسي.'], 403);
            }
            return redirect()->back()->with('error', 'عفواً، هذا الإجراء متاح فقط لمدير النظام الرئيسي.');
        }

        return $next($request);
    }
}
