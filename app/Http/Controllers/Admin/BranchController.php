<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BranchRequest;
use App\Models\Branch;
use App\Services\UploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function __construct(private readonly UploadService $uploads) {}

    public function index(Request $request): View
    {
        $branches = Branch::query()
            ->when($request->filled('q'), function ($query) use ($request): void {
                $query->where(function ($query) use ($request): void {
                    $query->where('name', 'like', '%'.$request->q.'%')
                        ->orWhere('city', 'like', '%'.$request->q.'%')
                        ->orWhere('address', 'like', '%'.$request->q.'%');
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->status === 'active'))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.branches.index', compact('branches'));
    }

    public function create(): View
    {
        return view('admin.branches.create', [
            'branch' => new Branch([
                'is_active' => true,
                'reservation_time_slots' => setting('reservation_time_slots', '09:00-14:00,16:00-21:00'),
                'reservation_last_booking_time' => setting('reservation_last_booking_time', '20:30'),
                'reservation_last_order_buffer_minutes' => setting('reservation_last_order_buffer_minutes', 30),
            ]),
        ]);
    }

    public function store(BranchRequest $request): RedirectResponse
    {
        $data = $this->normalizedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploads->uploadImage($request->file('image'), 'branches');
        }

        Branch::create($data);

        return redirect()->route('admin.branches.index')->with('success', 'Đã thêm cơ sở.');
    }

    public function edit(Branch $branch): View
    {
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(BranchRequest $request, Branch $branch): RedirectResponse
    {
        $data = $this->normalizedData($request);

        if ($request->hasFile('image')) {
            $oldImage = $branch->image;
            $data['image'] = $this->uploads->uploadImage($request->file('image'), 'branches');
            $this->uploads->deleteImage($oldImage);
        }

        $branch->update($data);

        return redirect()->route('admin.branches.index')->with('success', 'Đã cập nhật cơ sở.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $this->uploads->deleteImage($branch->image);
        $branch->delete();

        return back()->with('success', 'Đã xóa cơ sở.');
    }

    private function normalizedData(BranchRequest $request): array
    {
        $sortOrderInput = $request->input('sort_order');
        $sortOrder = is_numeric($sortOrderInput)
            ? (int) $sortOrderInput
            : ((int) Branch::query()->max('sort_order')) + 1;

        return $request->safe()
            ->except(['image'])
            + [
                'is_active' => $request->boolean('is_active'),
                'sort_order' => $sortOrder,
            ];
    }
}
