<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PostSeoAnalyzer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostSeoController extends Controller
{
    public function __invoke(Request $request, PostSeoAnalyzer $analyzer): JsonResponse
    {
        $data = $request->validate([
            'post_id' => ['nullable', 'integer', 'exists:posts,id'],
            'title' => ['nullable', 'string', 'max:220'],
            'slug' => ['nullable', 'string', 'max:240'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string', 'max:100000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'focus_keyword' => ['nullable', 'string', 'max:255'],
            'thumbnail_present' => ['nullable', 'boolean'],
            'related_post_ids' => ['nullable', 'array', 'max:6'],
            'related_post_ids.*' => ['integer', 'exists:posts,id'],
        ]);

        return response()->json(['ok' => true] + $analyzer->analyze($data, $data['post_id'] ?? null));
    }
}
