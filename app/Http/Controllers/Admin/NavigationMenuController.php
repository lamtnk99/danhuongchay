<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NavigationMenuRequest;
use App\Models\NavigationMenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavigationMenuController extends Controller
{
    public function index(Request $request): View
    {
        $menus = NavigationMenu::query()
            ->with('parent')
            ->when($request->filled('location'), fn ($query) => $query->where('location', $request->location))
            ->orderBy('location')
            ->orderBy('sort_order')
            ->paginate(10)
            ->withQueryString();

        return view('admin.menus.index', compact('menus'));
    }

    public function create(): View
    {
        return view('admin.menus.create', [
            'menu' => new NavigationMenu(['is_active' => true]),
            'parents' => $this->parents(),
        ]);
    }

    public function store(NavigationMenuRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['open_new_tab'] = $request->boolean('open_new_tab');

        NavigationMenu::create($data);

        return redirect()->route('admin.menus.index')->with('success', 'Đã thêm menu.');
    }

    public function edit(NavigationMenu $menu): View
    {
        return view('admin.menus.edit', [
            'menu' => $menu,
            'parents' => $this->parents($menu),
        ]);
    }

    public function update(NavigationMenuRequest $request, NavigationMenu $menu): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['open_new_tab'] = $request->boolean('open_new_tab');

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Đã cập nhật menu.');
    }

    public function destroy(NavigationMenu $menu): RedirectResponse
    {
        $menu->delete();

        return back()->with('success', 'Đã xóa menu.');
    }

    private function parents(?NavigationMenu $current = null)
    {
        return NavigationMenu::query()
            ->whereNull('parent_id')
            ->when($current, fn ($query) => $query->whereKeyNot($current->getKey()))
            ->orderBy('location')
            ->orderBy('sort_order')
            ->get();
    }
}
