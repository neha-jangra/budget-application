<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$permission): Response
    {
        if (!auth()->user()->roles->flatMap->permissions->pluck('name')->contains($permission)) 
        {
            //abort(403, 'You do not have permission to access this resource');
            return response()->view('components.errors.403', [], 403);
        }

        return $next($request);
    }
}
