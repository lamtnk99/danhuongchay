<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Testimonial;
use App\Services\UploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function __construct(private readonly UploadService $uploads) {}

    public function index(Request $request): View
    {
        $testimonials = Testimonial::query()
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->q.'%'))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('admin.testimonials.create', ['testimonial' => new Testimonial(['rating' => 5, 'is_active' => true])]);
    }

    public function store(TestimonialRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->uploads->uploadImage($request->file('avatar'), 'testimonials');
        }

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Đã thêm review khách hàng.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('avatar')) {
            $oldAvatar = $testimonial->avatar;
            $data['avatar'] = $this->uploads->uploadImage($request->file('avatar'), 'testimonials');
            $this->uploads->deleteImage($oldAvatar);
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Đã cập nhật review khách hàng.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->uploads->deleteImage($testimonial->avatar);
        $testimonial->delete();

        return back()->with('success', 'Đã xóa review khách hàng.');
    }
}
