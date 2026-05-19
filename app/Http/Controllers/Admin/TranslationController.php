<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\Translation\DeepLTranslationService;
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
            'usage' => app(DeepLTranslationService::class)->safeUsage(),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'translation_enabled' => ['nullable', 'boolean'],
            'deepl_api_key' => ['nullable', 'string', 'max:500'],
            'clear_deepl_api_key' => ['nullable', 'boolean'],
            'deepl_api_plan' => ['required', 'in:free,pro'],
            'deepl_source_lang' => ['required', 'in:VI'],
            'deepl_target_lang' => ['required', 'in:EN-US,EN-GB'],
            'translation_monthly_limit_warning' => ['nullable', 'integer', 'min:1000', 'max:10000000'],
        ]);

        SiteSetting::set('translation_enabled', $request->boolean('translation_enabled') ? '1' : '0', 'boolean', 'translation');
        SiteSetting::set('deepl_api_plan', $data['deepl_api_plan'], 'text', 'translation');
        SiteSetting::set('deepl_source_lang', $data['deepl_source_lang'], 'text', 'translation');
        SiteSetting::set('deepl_target_lang', $data['deepl_target_lang'], 'text', 'translation');
        SiteSetting::set('translation_monthly_limit_warning', $data['translation_monthly_limit_warning'] ?? 450000, 'number', 'translation');

        if ($request->boolean('clear_deepl_api_key')) {
            SiteSetting::set('deepl_api_key', null, 'password', 'translation');
        } elseif ($request->filled('deepl_api_key')) {
            SiteSetting::set('deepl_api_key', Crypt::encryptString($data['deepl_api_key']), 'password', 'translation');
        }

        return back()->with('success', 'Đã cập nhật cài đặt dịch tự động.');
    }

    public function usage(DeepLTranslationService $deepL): JsonResponse
    {
        try {
            return response()->json(['ok' => true, 'usage' => $deepL->usage()]);
        } catch (\Throwable $exception) {
            return response()->json(['ok' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function test(DeepLTranslationService $deepL): JsonResponse
    {
        try {
            $result = $deepL->translateFields(['description' => 'Đàn Hương Chay là nhà hàng chay tại Hải Phòng.']);

            return response()->json([
                'ok' => true,
                'message' => 'Kết nối DeepL thành công.',
                'sample' => $result['translations']['description'] ?? null,
                'usage' => $result['usage'],
            ]);
        } catch (\Throwable $exception) {
            return response()->json(['ok' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function translate(Request $request, DeepLTranslationService $deepL): JsonResponse
    {
        $data = $request->validate([
            'fields' => ['required', 'array', 'min:1'],
            'fields.*' => ['nullable', 'string', 'max:12000'],
        ]);

        try {
            return response()->json(['ok' => true] + $deepL->translateFields($data['fields']));
        } catch (\Throwable $exception) {
            return response()->json(['ok' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
