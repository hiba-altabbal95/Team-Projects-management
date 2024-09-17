<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
class CheckTesterRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   
        public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $projectId = $request->route('project'); // Assuming project ID is in the route

        $isTester = DB::table('project_user')
            ->where('user_id', $user->id)
            ->where('project_id', $projectId)
            ->where('role', 'tester')
            ->exists();

        if (!$isTester) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
    }

