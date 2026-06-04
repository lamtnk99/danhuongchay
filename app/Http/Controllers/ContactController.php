<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Branch;
use App\Models\Contact;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(Request $request): View
    {
        $branches = Branch::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $selectedBranch = $branches->first(fn (Branch $branch): bool => $branch->slug === $request->query('branch') || (string) $branch->id === (string) $request->query('branch'));

        $seo = SeoService::page(
            __('site.seo_fallback.contact_title'),
            __('site.seo_fallback.contact_description'),
            __('site.seo_fallback.contact_keywords'),
            localized_route('contact')
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('contact', compact('seo', 'schemas', 'branches', 'selectedBranch'));
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        Contact::create($request->validated());

        return redirect()
            ->to(localized_route('contact'))
            ->with('success', __('site.contact.success'));
    }
}
