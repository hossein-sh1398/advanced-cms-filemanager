<?php

namespace App\Http\Middleware;

use App\Models\Config;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Maintenance
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $configs = Config::whereIn('key', ['general_offline', 'general_offline_text', 'general_access_ip'])->pluck('value', 'key');

        $generalAccessIp = array_filter(explode(',', $configs['general_access_ip']));

        $ip = $request->ip();

        if ((auth()->check() && auth()->user()->isAdministrator()) || in_array($ip, $generalAccessIp)) {
            return $next($request);
        }

        if ($configs['general_offline']) {
            abort(403, $configs['general_offline_text']);
        }

        return $next($request);
    }
}
