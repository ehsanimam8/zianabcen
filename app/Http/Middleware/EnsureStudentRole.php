<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureStudentRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && !$user->hasRole('Student')) {
            if ($user->hasRole(['Admin', 'Super Admin', 'admin', 'super_admin'])) {
                return redirect()->to('/admin');
            }
            if ($user->hasRole(['Instructor', 'Teacher', 'instructor', 'teacher'])) {
                return redirect()->to('/teacher');
            }
        }

        return $next($request);
    }
}
