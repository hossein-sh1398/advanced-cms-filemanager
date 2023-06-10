<?php


use Carbon\Carbon;
use App\Models\Config;
use App\Rules\MobileRule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (!function_exists('getToday')) {
    function getToday($format, $type = 'shamsi'): string
    {
        if ($type === 'shamsi') {
            $date = verta()->format($format);
        } else {
            $date = Carbon::now()->format($format);
        }
        return $date;
    }
}

if (!function_exists('scopeSearchHandler')) {
    function scopeSearchHandler($query, $string, $columns)
    {
        $string = trim($string);
        foreach ($columns as $key => $column) {
            if ($key === 0) {
                $query->where($column, 'LIKE', '%' . $string . '%');
            } else {
                $query->orWhere($column, 'LIKE', '%' . $string . '%');
            }
        }
        return $query;
    }
}

if (!function_exists('checkCustomerRouteName')) {
    function checkCustomerRouteName($routeName): bool
    {
        if (strpos($routeName, 'admin.') !== false
            || strpos($routeName, 'api.') !== false
            || strpos($routeName, 'l5-swagger.default.api') !== false
        ) {
            return true;
        }
        return false;
    }
}

if (!function_exists('isMobile')) {
    function isMobile($value): bool
    {
        return preg_match(MobileRule::Pattern, $value);
    }
}

if (!function_exists(('checkRoute'))) {
    function checkRoute($route)
    {
        return Route::currentRouteName() == $route;
    }
}

if (!function_exists(('activeAdminMenu'))) {
    function activeAdminMenu($menus): bool
    {
        if (request()->routeIs('admin.index') && $menus['active'] == 'admin') {
            return true;
        }

        if (request()->is($menus['active'])) {
            return true;
        }

        if (request()->is($menus['active'].'/*') && $menus['active'] != 'admin') {
            return true;
        }

        return false;
    }
}
if (!function_exists(('activeAdminSubMenu'))) {
    function activeAdminSubMenu($menus): bool
    {
        $items = collect($menus['sub'])->pluck('active')->toArray();
        foreach ($items as $item) {
            if (request()->is($item)) {
                return true;
            }
            if (request()->is($item . "/*")) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('getConfigParam')) {
    function getConfigParam($items, $configs = false)
    {
        if ($configs === false) {
            $configs = Config::all();
        }

        if (is_array($items)) {
            return $configs->whereIn('key', $items)->all();
        } else {
            $config = $configs->where('key', $items)->first();
            if ($config) {
                return $config->value;
            }
        }
        return false;
    }
}

if (!function_exists('isSuperAdmin')) {
    function isSuperAdmin(): bool
    {
        if (auth()->check()) {
            if (auth()->user()->hasRole('superadmin')) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('redirectAfterSave')) {
    function redirectAfterSave($saveType, $model)
    {
        $table = Str::replace('_', '.', $model->getTable());

        return match ($saveType) {
            'edit' => to_route("admin.$table.edit", $model->id),
            'close' => to_route("admin.$table.index"),
            'create' => to_route("admin.$table.create"),
        };
    }
}

if (!function_exists('isLogs')) {
    function isLogs(): bool
    {
        $path = storage_path('logs');
        if (File::exists($path)) {
            $logs = File::allFiles($path);
            if (count($logs) > 0) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('stringToColor')) {
    function stringToColor($str): string
    {
        $code = dechex(crc32($str));
        return substr($code, 0, 6);
    }
}
