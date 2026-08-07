<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostMedia;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostMediaController extends Controller
{
    public function __construct(private readonly UploadService $uploads) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:'.config('uploads.max_image_kb')],
            'post_id' => ['nullable', 'integer', 'exists:posts,id'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:500'],
            'layout' => ['nullable', 'in:normal,wide'],
        ]);

        $path = $this->uploads->uploadImage($request->file('image'), 'posts/content', 'content');
        $disk = config('uploads.disk', 'public');

        try {
            $absolutePath = Storage::disk($disk)->path($path);
            $dimensions = is_file($absolutePath) ? @getimagesize($absolutePath) : false;
            $media = PostMedia::create([
                'post_id' => $data['post_id'] ?? null,
                'uploaded_by' => $request->user()?->getKey(),
                'disk' => $disk,
                'path' => $path,
                'mime_type' => $dimensions['mime'] ?? $request->file('image')->getMimeType(),
                'file_size' => Storage::disk($disk)->size($path),
                'width' => $dimensions[0] ?? null,
                'height' => $dimensions[1] ?? null,
                'alt_text' => filled($data['alt_text'] ?? null) ? trim($data['alt_text']) : null,
                'caption' => filled($data['caption'] ?? null) ? trim($data['caption']) : null,
                'layout' => $data['layout'] ?? 'normal',
            ]);
        } catch (\Throwable $exception) {
            $this->uploads->deleteImage($path);
            throw $exception;
        }

        return response()->json([
            'ok' => true,
            'media' => [
                'id' => $media->getKey(),
                'url' => media_url($media->path),
                'srcset' => media_srcset($media->path, ['card', 'large']),
                'alt_text' => $media->alt_text,
                'caption' => $media->caption,
                'layout' => $media->layout,
                'width' => $media->width,
                'height' => $media->height,
            ],
        ], 201);
    }
}
