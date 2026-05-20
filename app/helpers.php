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

if (! function_exists('media_variant_path')) {
    function media_variant_path(?string $path, string $variant): ?string
    {
        if (blank($path) || str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:') || str_ends_with(strtolower($path), '.svg')) {
            return $path;
        }

        $isPublicPath = str_starts_with($path, '/');
        $directory = trim(dirname($path), '.\\/');
        $variantPath = ($isPublicPath ? '/' : '').($directory ? trim($directory, '/').'/' : '').pathinfo($path, PATHINFO_FILENAME).'-'.$variant.'.webp';

        $exists = $isPublicPath
            ? file_exists(public_path(ltrim($variantPath, '/')))
            : Storage::disk('public')->exists($variantPath);

        return $exists ? $variantPath : $path;
    }
}

if (! function_exists('media_variant_url')) {
    function media_variant_url(?string $path, string $variant, ?string $default = null): ?string
    {
        return media_url(media_variant_path($path, $variant), $default);
    }
}

if (! function_exists('media_srcset')) {
    function media_srcset(?string $path, array|string $variants = ['thumb', 'card', 'large']): ?string
    {
        if (blank($path) || str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:') || str_ends_with(strtolower($path), '.svg')) {
            return null;
        }

        $variants = is_array($variants) ? $variants : explode(',', $variants);
        $config = config('uploads.variants', []);

        return collect($variants)
            ->map(function (string $variant) use ($path, $config): ?string {
                $variant = trim($variant);
                $variantPath = media_variant_path($path, $variant);

                if ($variantPath === $path || ! isset($config[$variant]['width'])) {
                    return null;
                }

                return media_url($variantPath).' '.(int) $config[$variant]['width'].'w';
            })
            ->filter()
            ->implode(', ') ?: null;
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

if (! function_exists('show_dish_prices')) {
    function show_dish_prices(): bool
    {
        return (string) setting('show_dish_prices', '1') === '1';
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
