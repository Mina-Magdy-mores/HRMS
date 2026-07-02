<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $subMenu, string $action): Response
    {
        if (!check_permission($subMenu, $action)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'عفواً، لا تملك الصلاحية للقيام بهذا الإجراء.'], 403);
            }
            return redirect()->route('admin.dashboard')->with('error', 'عفواً، لا تملك الصلاحية للوصول لهذه الصفحة.');
        }

        return $next($request);
    }
}
