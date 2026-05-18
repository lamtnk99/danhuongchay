<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryImageRequest;
use App\Models\GalleryImage;
use App\Services\UploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryImageController extends Controller
{
    public function __construct(private readonly UploadService $uploads) {}

    public function index(Request $request): View
    {
        $galleryImages = GalleryImage::query()
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->q.'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->status === 'active'))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.gallery.index', compact('galleryImages'));
    }

    public function create(): View
    {
        return view('admin.gallery.create', [
            'galleryImage' => new GalleryImage([
                'location' => 'space',
                'is_featured' => true,
                'is_active' => true,
            ]),
        ]);
    }

    public function store(GalleryImageRequest $request): RedirectResponse
    {
        $data = $this->normalizedData($request);
        $data['image'] = $this->uploads->uploadImage($request->file('image'), 'gallery');

        GalleryImage::create($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Đã thêm ảnh không gian quán.');
    }

    public function edit(GalleryImage $gallery): View
    {
        return view('admin.gallery.edit', ['galleryImage' => $gallery]);
    }

    public function update(GalleryImageRequest $request, GalleryImage $gallery): RedirectResponse
    {
        $data = $this->normalizedData($request);

        if ($request->hasFile('image')) {
            $oldImage = $gallery->image;
            $data['image'] = $this->uploads->uploadImage($request->file('image'), 'gallery');
            $this->uploads->deleteImage($oldImage);
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Đã cập nhật ảnh không gian quán.');
    }

    public function destroy(GalleryImage $gallery): RedirectResponse
    {
        $this->uploads->deleteImage($gallery->image);
        $gallery->delete();

        return back()->with('success', 'Đã xóa ảnh không gian quán.');
    }

    private function normalizedData(GalleryImageRequest $request): array
    {
        return $request->safe()
            ->except(['image'])
            + [
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active'),
            ];
    }
}
