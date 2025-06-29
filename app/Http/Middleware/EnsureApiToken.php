<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureApiToken
{
    public function handle(Request $request, Closure $next)
    {
        // Извлечь токен из заголовка Authorization (Bearer)
        $token = $request->bearerToken(); // вернет токен или null
        // Проверить соответствие с секретным токеном из .env
        if (!$token || $token !== config('app.api_stats_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
