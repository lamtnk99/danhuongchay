<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use App\Services\UploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function __construct(private readonly UploadService $uploads) {}

    public function index(Request $request): View
    {
        $banners = Banner::query()
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->q.'%'))
            ->orderBy('position')
            ->orderBy('sort_order')
            ->paginate(10)
            ->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.banners.create', ['banner' => new Banner]);
    }

    public function store(BannerRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $this->uploads->uploadImage($request->file('image'), 'banners');
        $data['is_active'] = $request->boolean('is_active');

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Đã thêm banner.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(BannerRequest $request, Banner $banner): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $oldImage = $banner->image;
            $data['image'] = $this->uploads->uploadImage($request->file('image'), 'banners');
            $this->uploads->deleteImage($oldImage);
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Đã cập nhật banner.');
    }

    public function toggle(Banner $banner): RedirectResponse
    {
        $banner->update(['is_active' => ! $banner->is_active]);

        return back()->with('success', 'Đã đổi trạng thái banner.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->uploads->deleteImage($banner->image);
        $banner->delete();

        return back()->with('success', 'Đã xóa banner.');
    }
}
