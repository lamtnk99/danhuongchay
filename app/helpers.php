<?php

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        $settings = Cache::rememberForever('site_settings', fn () => SiteSetting::query()
            ->pluck('value', 'key')
            ->all());

        return $settings[$key] ?? $default;
    }
}

if (! function_exists('media_url')) {
    function media_url(?string $path, ?string $default = null): ?string
    {
        if (blank($path)) {
            return $default;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }

        if (str_starts_with($path, '/')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}

if (! function_exists('localized_setting')) {
    function localized_setting(string $key, mixed $default = null): mixed
    {
        if (is_english()) {
            $englishValue = setting($key.'_en');

            if (filled($englishValue)) {
                return $englishValue;
            }
        }

        return setting($key, $default);
    }
}

if (! function_exists('current_locale')) {
    function current_locale(): string
    {
        return app()->getLocale() ?: config('locales.default', 'vi');
    }
}

if (! function_exists('is_english')) {
    function is_english(): bool
    {
        return current_locale() === 'en';
    }
}

if (! function_exists('localized_route')) {
    function localized_route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        if (is_english() && \Illuminate\Support\Facades\Route::has("localized.{$name}")) {
            return route("localized.{$name}", $parameters, $absolute);
        }

        if (is_array($parameters) && isset($parameters['slug'])) {
            $parameterNames = [
                'menu.show' => 'dish',
                'blog.show' => 'post',
                'pages.show' => 'page',
            ];

            if (isset($parameterNames[$name])) {
                $parameters = [$parameterNames[$name] => $parameters['slug']];
            }
        }

        return route($name, $parameters, $absolute);
    }
}

if (! function_exists('localized_url')) {
    function localized_url(string $path): string
    {
        $path = '/'.ltrim($path, '/');

        if (is_english()) {
            return url('/en'.($path === '/' ? '' : $path));
        }

        return url($path);
    }
}

if (! function_exists('localized_field')) {
    function localized_field(object $model, string $field, mixed $default = null): mixed
    {
        if (method_exists($model, 'localized')) {
            return $model->localized($field, $default);
        }

        return data_get($model, $field, $default);
    }
}
