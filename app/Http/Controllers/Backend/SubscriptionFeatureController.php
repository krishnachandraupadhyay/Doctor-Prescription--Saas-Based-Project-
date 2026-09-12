<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionFeature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubscriptionFeatureController extends Controller
{
    public function index(): View
    {
        $features = SubscriptionFeature::orderBy('category')->orderBy('sort_order')->get()->groupBy('category');
        $totalFeatures = SubscriptionFeature::count();
        $activeFeatures = SubscriptionFeature::where('is_active', true)->count();

        return view('backend.Admin.subscriptions.features.index', compact('features', 'totalFeatures', 'activeFeatures'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:50', 'unique:subscription_features,code'],
            'category' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $code = !empty($validated['code']) ? Str::slug($validated['code'], '_') : Str::slug($validated['name'], '_');

        SubscriptionFeature::create([
            'name' => $validated['name'],
            'code' => $code,
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => true,
        ]);

        return redirect()->route('admin.subscriptions.features.index')
            ->with('success', "Feature [{$validated['name']}] added to catalog.");
    }

    public function update(Request $request, SubscriptionFeature $feature): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $feature->update($validated);

        return redirect()->route('admin.subscriptions.features.index')
            ->with('success', "Feature [{$feature->name}] updated.");
    }

    public function toggleStatus(SubscriptionFeature $feature): RedirectResponse
    {
        $feature->update(['is_active' => !$feature->is_active]);

        return redirect()->back()->with('success', "Feature [{$feature->name}] status toggled.");
    }

    public function destroy(SubscriptionFeature $feature): RedirectResponse
    {
        $feature->delete();

        return redirect()->route('admin.subscriptions.features.index')
            ->with('success', "Feature removed from catalog.");
    }
}
