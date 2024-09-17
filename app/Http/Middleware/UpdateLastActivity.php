<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class UpdateLastActivity
{
    public function handle($request, Closure $next)
    {
        try {
            if ($user = JWTAuth::parseToken()->authenticate()) {
                Log::info('User authenticated', ['user_id' => $user->id]);
                $user->last_activity = now();
                $user->save();
                Log::info('Last activity updated', ['last_activity' => $user->last_activity]);
            }
        } catch (JWTException $e) {
            Log::error('Token parsing error', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Token could not be parsed from the request'], 401);
        }


        return $next($request);
    }
}