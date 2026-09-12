<?php

namespace App\Http\Controllers\Backend;

use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriberController extends Controller
{
    public function index(Request $request): View
    {
        $query = Subscription::with(['plan'])->where('subscriber_type', 'clinic');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($planId = $request->input('plan_id')) {
            $query->where('subscription_plan_id', $planId);
        }

        $subscriptions = $query->latest('id')->paginate(15);

        // Populate clinic details on each item
        $subscriptions->getCollection()->transform(function ($sub) {
            $sub->subscriber_model = Clinic::find($sub->subscriber_id);
            return $sub;
        });

        $activeClinicsCount = Subscription::where('subscriber_type', 'clinic')
            ->whereIn('status', ['active', 'trial'])
            ->distinct('subscriber_id')
            ->count('subscriber_id');

        $totalClinics = Clinic::where('isdeleted', 0)->count();

        $subscribedClinicIds = Subscription::where('subscriber_type', 'clinic')
            ->whereIn('status', ['active', 'trial'])
            ->pluck('subscriber_id');

        $totalDoctors = Doctor::where('isdeleted', 0)
            ->whereIn('clinic_id', $subscribedClinicIds)
            ->count();

        $expiringSoon = Subscription::where('subscriber_type', 'clinic')
            ->whereIn('status', ['active', 'trial'])
            ->whereBetween('end_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->count();

        $plans = SubscriptionPlan::where('status', 'active')->orderBy('name')->get();
        $clinics = Clinic::where('isdeleted', 0)->orderBy('name')->get();

        return view('backend.Admin.subscriptions.subscribers.index', compact(
            'subscriptions', 'totalClinics', 'activeClinicsCount', 'totalDoctors', 'expiringSoon',
            'plans', 'clinics'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subscriber_id' => ['required', 'exists:clinics,id'],
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'string', 'in:trial,active,expired,cancelled,suspended'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // If active subscription exists for this clinic, expire older ones
        Subscription::where('subscriber_type', 'clinic')
            ->where('subscriber_id', $validated['subscriber_id'])
            ->whereIn('status', ['active', 'trial'])
            ->update(['status' => SubscriptionStatus::EXPIRED]);

        Subscription::create([
            'subscriber_type' => 'clinic',
            'subscriber_id' => $validated['subscriber_id'],
            'subscription_plan_id' => $validated['subscription_plan_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'trial_ends_at' => $validated['status'] === 'trial' ? $validated['end_date'] : null,
            'status' => SubscriptionStatus::from($validated['status']),
            'payment_reference' => $validated['payment_reference'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->user()->name ?? 'Super Admin',
        ]);

        return redirect()->route('admin.subscriptions.subscribers.index')
            ->with('success', 'Clinic subscription assigned successfully.');
    }

    public function extend(Request $request, Subscription $subscription): RedirectResponse
    {
        $request->validate([
            'days' => ['required', 'integer', 'min:1', 'max:3650'],
        ]);

        $days = (int) $request->input('days');
        $currentEnd = Carbon::parse($subscription->end_date);
        $newEnd = $currentEnd->isPast() ? now()->addDays($days) : $currentEnd->addDays($days);

        $subscription->update([
            'end_date' => $newEnd->toDateString(),
            'status' => SubscriptionStatus::ACTIVE,
            'updated_by' => auth()->user()->name ?? 'Super Admin',
            'notes' => ($subscription->notes ? $subscription->notes . ' | ' : '') . "Extended by {$days} days on " . now()->toFormattedDateString(),
        ]);

        return redirect()->back()->with('success', "Subscription extended by {$days} days until {$newEnd->format('d M Y')}.");
    }

    public function updateStatus(Request $request, Subscription $subscription): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'string', 'in:active,trial,expired,suspended,cancelled'],
        ]);

        $newStatus = SubscriptionStatus::from($request->input('status'));
        $subscription->update([
            'status' => $newStatus,
            'updated_by' => auth()->user()->name ?? 'Super Admin',
        ]);

        return redirect()->back()->with('success', "Subscription status updated to {$newStatus->label()}.");
    }
}
