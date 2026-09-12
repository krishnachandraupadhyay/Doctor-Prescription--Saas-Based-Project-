<?php

namespace App\Http\Controllers\Backend;

use App\Enums\BillingCycle;
use App\Enums\PlanStatus;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionFeature;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    public function index(Request $request): View
    {
        $query = SubscriptionPlan::withCount(['subscriptions' => function ($q) {
            $q->where('subscriber_type', 'clinic')->whereIn('status', ['active', 'trial']);
        }]);

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($cycle = $request->input('cycle')) {
            $query->where('billing_cycle', $cycle);
        }

        $plans = $query->orderBy('sort_order')->orderBy('id')->get();

        $totalPlans = SubscriptionPlan::count();
        $activePlans = SubscriptionPlan::where('status', PlanStatus::ACTIVE)->count();
        $totalSubscribers = \App\Models\Subscription::where('subscriber_type', 'clinic')
            ->whereIn('status', ['active', 'trial'])
            ->count();

        return view('backend.Admin.subscriptions.plans.index', compact(
            'plans', 'totalPlans', 'activePlans', 'totalSubscribers'
        ));
    }

    public function create(): View
    {
        $features = SubscriptionFeature::active()->get()->groupBy('category');
        return view('backend.Admin.subscriptions.plans.create', compact('features'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:subscription_plans,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'billing_cycle' => ['required', 'string', Rule::in(array_column(BillingCycle::cases(), 'value'))],
            'trial_days' => ['required', 'integer', 'min:0', 'max:365'],
            'status' => ['required', 'string', Rule::in(array_column(PlanStatus::cases(), 'value'))],
            'is_popular' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'limits' => ['nullable', 'array'],
            'limits.max_patients_per_month' => ['required', 'integer', 'min:-1', 'max:1000000'],
            'limits.max_prescriptions_per_month' => ['required', 'integer', 'min:-1', 'max:1000000'],
            'limits.max_staff' => ['required', 'integer', 'min:-1', 'max:10000'],
            'limits.max_doctors' => ['required', 'integer', 'min:-1', 'max:1000'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string'],
        ]);

        $slug = SubscriptionPlan::generateUniqueSlug($validated['name']);

        $plan = SubscriptionPlan::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'billing_cycle' => BillingCycle::from($validated['billing_cycle']),
            'trial_days' => (int) $validated['trial_days'],
            'status' => PlanStatus::from($validated['status']),
            'is_popular' => (bool) ($request->input('is_popular') ? 1 : 0),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'limits' => $validated['limits'] ?? [],
            'features' => $validated['features'] ?? [],
            'created_by' => auth()->user()->name ?? 'Super Admin',
        ]);

        return redirect()->route('admin.subscriptions.plans.index')
            ->with('success', "Subscription Plan [{$plan->name}] created successfully.");
    }

    public function edit(SubscriptionPlan $plan): View
    {
        $features = SubscriptionFeature::active()->get()->groupBy('category');
        return view('backend.Admin.subscriptions.plans.edit', compact('plan', 'features'));
    }

    public function update(Request $request, SubscriptionPlan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('subscription_plans', 'name')->ignore($plan->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'billing_cycle' => ['required', 'string', Rule::in(array_column(BillingCycle::cases(), 'value'))],
            'trial_days' => ['required', 'integer', 'min:0', 'max:365'],
            'status' => ['required', 'string', Rule::in(array_column(PlanStatus::cases(), 'value'))],
            'is_popular' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'limits' => ['nullable', 'array'],
            'limits.max_patients_per_month' => ['required', 'integer', 'min:-1', 'max:1000000'],
            'limits.max_prescriptions_per_month' => ['required', 'integer', 'min:-1', 'max:1000000'],
            'limits.max_staff' => ['required', 'integer', 'min:-1', 'max:10000'],
            'limits.max_doctors' => ['required', 'integer', 'min:-1', 'max:1000'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string'],
        ]);

        $plan->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'billing_cycle' => BillingCycle::from($validated['billing_cycle']),
            'trial_days' => (int) $validated['trial_days'],
            'status' => PlanStatus::from($validated['status']),
            'is_popular' => (bool) ($request->input('is_popular') ? 1 : 0),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'limits' => $validated['limits'] ?? [],
            'features' => $validated['features'] ?? [],
            'updated_by' => auth()->user()->name ?? 'Super Admin',
        ]);

        return redirect()->route('admin.subscriptions.plans.index')
            ->with('success', "Subscription Plan [{$plan->name}] updated successfully.");
    }

    public function toggleStatus(SubscriptionPlan $plan): RedirectResponse
    {
        $newStatus = $plan->status === PlanStatus::ACTIVE ? PlanStatus::INACTIVE : PlanStatus::ACTIVE;
        $plan->update(['status' => $newStatus, 'updated_by' => auth()->user()->name ?? 'Super Admin']);

        return redirect()->back()->with('success', "Plan status updated to {$newStatus->label()}.");
    }

    public function destroy(SubscriptionPlan $plan): RedirectResponse
    {
        if ($plan->subscriptions()->whereIn('status', ['active', 'trial'])->exists()) {
            return redirect()->back()->with('error', "Cannot delete plan [{$plan->name}] because it currently has active subscribers.");
        }

        $plan->delete();
        return redirect()->route('admin.subscriptions.plans.index')
            ->with('success', "Subscription Plan deleted successfully.");
    }
}
