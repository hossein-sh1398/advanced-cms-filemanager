<?php

namespace App\Http\Middleware;

use App\Models\History;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HistoryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        History::create([
            'user_id' => auth()->id(),
            'action' => Route::currentRouteName(),
        ]);

        return $next($request);
    }
}
