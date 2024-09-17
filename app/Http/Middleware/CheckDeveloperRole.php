<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CheckDeveloperRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       $user = Auth::user();
        $projectId = $request->route('project'); // Assuming project ID is in the route

        $isDeveloper = DB::table('project_user')
            ->where('user_id', $user->id)
            ->where('project_id', $projectId)
            ->where('role', 'developer')
            ->exists();

        if (!$isDeveloper) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
