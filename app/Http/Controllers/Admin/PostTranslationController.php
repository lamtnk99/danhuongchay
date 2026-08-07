<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BlogTranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostTranslationController extends Controller
{
    public function __invoke(Request $request, BlogTranslationService $translator): JsonResponse
    {
        $data = $request->validate([
            'fields' => ['required', 'array', 'min:1'],
            'fields.title' => ['nullable', 'string', 'max:220'],
            'fields.excerpt' => ['nullable', 'string', 'max:1000'],
            'fields.content' => ['nullable', 'string', 'max:100000'],
            'fields.meta_title' => ['nullable', 'string', 'max:255'],
            'fields.meta_description' => ['nullable', 'string', 'max:500'],
            'fields.focus_keyword' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            return response()->json(['ok' => true] + $translator->translate($data['fields']));
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json(['ok' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
