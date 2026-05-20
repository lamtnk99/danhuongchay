<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\Translation\DeepLTranslationService;
use App\Services\Translation\MicrosoftTranslatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class TranslationController extends Controller
{
    public function settings(): View
    {
        return view('admin.translations.settings', [
            'hasDeepLKey' => filled(setting('deepl_api_key')),
            'hasMicrosoftKey' => filled(setting('microsoft_translator_key')),
            'usage' => setting('translation_provider', 'deepl') === 'deepl' ? app(DeepLTranslationService::class)->safeUsage() : null,
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'translation_enabled' => ['nullable', 'boolean'],
            'translation_provider' => ['required', 'in:deepl,microsoft'],
            'deepl_api_key' => ['nullable', 'string', 'max:500'],
            'clear_deepl_api_key' => ['nullable', 'boolean'],
            'deepl_api_plan' => ['required', 'in:free,pro'],
            'deepl_source_lang' => ['required', 'in:VI'],
            'deepl_target_lang' => ['required', 'in:EN-US,EN-GB'],
            'microsoft_translator_key' => ['nullable', 'string', 'max:500'],
            'clear_microsoft_translator_key' => ['nullable', 'boolean'],
            'microsoft_translator_region' => ['nullable', 'string', 'max:120'],
            'microsoft_translator_endpoint' => ['nullable', 'url', 'max:255'],
            'microsoft_translator_target_lang' => ['required', 'in:en'],
            'translation_monthly_limit_warning' => ['nullable', 'integer', 'min:1000', 'max:10000000'],
        ]);

        SiteSetting::set('translation_enabled', $request->boolean('translation_enabled') ? '1' : '0', 'boolean', 'translation');
        SiteSetting::set('translation_provider', $data['translation_provider'], 'text', 'translation');
        SiteSetting::set('deepl_api_plan', $data['deepl_api_plan'], 'text', 'translation');
        SiteSetting::set('deepl_source_lang', $data['deepl_source_lang'], 'text', 'translation');
        SiteSetting::set('deepl_target_lang', $data['deepl_target_lang'], 'text', 'translation');
        SiteSetting::set('microsoft_translator_region', $data['microsoft_translator_region'] ?? null, 'text', 'translation');
        SiteSetting::set('microsoft_translator_endpoint', $data['microsoft_translator_endpoint'] ?: 'https://api.cognitive.microsofttranslator.com', 'text', 'translation');
        SiteSetting::set('microsoft_translator_target_lang', $data['microsoft_translator_target_lang'], 'text', 'translation');
        SiteSetting::set('translation_monthly_limit_warning', $data['translation_monthly_limit_warning'] ?? 450000, 'number', 'translation');

        if ($request->boolean('clear_deepl_api_key')) {
            SiteSetting::set('deepl_api_key', null, 'password', 'translation');
        } elseif ($request->filled('deepl_api_key')) {
            SiteSetting::set('deepl_api_key', Crypt::encryptString($data['deepl_api_key']), 'password', 'translation');
        }

        if ($request->boolean('clear_microsoft_translator_key')) {
            SiteSetting::set('microsoft_translator_key', null, 'password', 'translation');
        } elseif ($request->filled('microsoft_translator_key')) {
            SiteSetting::set('microsoft_translator_key', Crypt::encryptString($data['microsoft_translator_key']), 'password', 'translation');
        }

        return back()->with('success', 'Đã cập nhật cài đặt dịch tự động.');
    }

    public function usage(): JsonResponse
    {
        try {
            $service = $this->service();
            $usage = method_exists($service, 'usage') ? $service->usage() : null;

            return response()->json([
                'ok' => true,
                'usage' => $usage,
                'message' => $usage ? null : 'Provider này không có API đọc quota trực tiếp. Vui lòng kiểm tra trong dashboard nhà cung cấp.',
            ]);
        } catch (\Throwable $exception) {
            return response()->json(['ok' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function test(): JsonResponse
    {
        try {
            $result = $this->service()->translateFields(['description' => 'Đàn Hương Chay là nhà hàng chay tại Hải Phòng.']);

            return response()->json([
                'ok' => true,
                'message' => 'Kết nối dịch tự động thành công.',
                'sample' => $result['translations']['description'] ?? null,
                'usage' => $result['usage'],
            ]);
        } catch (\Throwable $exception) {
            return response()->json(['ok' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function translate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'fields' => ['required', 'array', 'min:1'],
            'fields.*' => ['nullable', 'string', 'max:12000'],
        ]);

        try {
            return response()->json(['ok' => true] + $this->service()->translateFields($data['fields']));
        } catch (\Throwable $exception) {
            return response()->json(['ok' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    private function service(): DeepLTranslationService|MicrosoftTranslatorService
    {
        return setting('translation_provider', 'deepl') === 'microsoft'
            ? app(MicrosoftTranslatorService::class)
            : app(DeepLTranslationService::class);
    }
}
