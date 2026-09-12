<?php

namespace Database\Seeders;

use App\Enums\BillingCycle;
use App\Enums\PlanStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Subscription;
use App\Models\SubscriptionFeature;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Features Catalog
        $features = [
            [
                'name' => 'Prescription Writing & PDF Generation',
                'code' => 'prescriptions',
                'category' => 'Clinical',
                'description' => 'Create, edit, design and download patient medical prescriptions in PDF.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Patient Management & Medical History',
                'code' => 'patients',
                'category' => 'Clinical',
                'description' => 'Comprehensive patient records, demographic tracking, and revisit history.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Vitals & Physical Examination Tracking',
                'code' => 'vitals_exam',
                'category' => 'Clinical',
                'description' => 'Record patient vitals (BP, Pulse, Temperature, SpO2, Blood Sugar).',
                'sort_order' => 3,
            ],
            [
                'name' => 'Custom Medicine & Formulary Master',
                'code' => 'medicines_master',
                'category' => 'Clinical',
                'description' => 'Access and configure custom drugs, dosages, units, and intervals.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Staff & Receptionist Accounts',
                'code' => 'staff_management',
                'category' => 'Staff & Security',
                'description' => 'Add and manage clinic staff, receptionists, and permissions.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Consultation Fees & Payment Categories',
                'code' => 'payment_categories',
                'category' => 'Billing',
                'description' => 'Custom billing categories, doctor fees, and payment tracking.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Patient Revisit Rules & Discounts',
                'code' => 'revisit_rules',
                'category' => 'Billing',
                'description' => 'Automated validity rules for free or discounted follow-up visits.',
                'sort_order' => 7,
            ],
            [
                'name' => 'Clinic Branding & Prescription Customization',
                'code' => 'custom_branding',
                'category' => 'Branding',
                'description' => 'Custom clinic logo, digital signature, clinic stamp, and header/footer design.',
                'sort_order' => 8,
            ],
            [
                'name' => 'Clinic & Doctor Document Vault',
                'code' => 'document_storage',
                'category' => 'Storage',
                'description' => 'Upload and verify medical licenses, clinic registration certificates.',
                'sort_order' => 9,
            ],
            [
                'name' => 'Practice Performance & Analytics Reports',
                'code' => 'analytics_reports',
                'category' => 'Analytics',
                'description' => 'Daily/monthly patient trends, revenue summaries, and doctor performance.',
                'sort_order' => 10,
            ],
        ];

        foreach ($features as $f) {
            SubscriptionFeature::updateOrCreate(
                ['code' => $f['code']],
                [
                    'name' => $f['name'],
                    'category' => $f['category'],
                    'description' => $f['description'],
                    'sort_order' => $f['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        $allFeatureCodes = array_column($features, 'code');

        // 2. Seed Default Clinic Plans
        $plans = [
            [
                'name' => 'Clinic Free Trial',
                'slug' => 'clinic-trial',
                'description' => '14-day full access trial for new clinics and health centers to test clinical workflows.',
                'price' => 0.00,
                'billing_cycle' => BillingCycle::MONTHLY,
                'trial_days' => 14,
                'status' => PlanStatus::ACTIVE,
                'is_popular' => false,
                'sort_order' => 1,
                'limits' => [
                    'max_patients_per_month' => 150,
                    'max_prescriptions_per_month' => 150,
                    'max_staff' => 2,
                    'max_doctors' => 2,
                ],
                'features' => ['prescriptions', 'patients', 'vitals_exam', 'medicines_master', 'payment_categories'],
            ],
            [
                'name' => 'Clinic Starter',
                'slug' => 'clinic-starter',
                'description' => 'Ideal for small clinics and solo practices with up to 2 consulting doctors.',
                'price' => 999.00,
                'billing_cycle' => BillingCycle::MONTHLY,
                'trial_days' => 0,
                'status' => PlanStatus::ACTIVE,
                'is_popular' => false,
                'sort_order' => 2,
                'limits' => [
                    'max_patients_per_month' => 500,
                    'max_prescriptions_per_month' => 500,
                    'max_staff' => 3,
                    'max_doctors' => 2,
                ],
                'features' => ['prescriptions', 'patients', 'vitals_exam', 'medicines_master', 'payment_categories', 'document_storage'],
            ],
            [
                'name' => 'Clinic Professional',
                'slug' => 'clinic-pro',
                'description' => 'Comprehensive clinic suite with full receptionist desk, unlimited patients, and up to 6 doctors.',
                'price' => 1999.00,
                'billing_cycle' => BillingCycle::MONTHLY,
                'trial_days' => 0,
                'status' => PlanStatus::ACTIVE,
                'is_popular' => true,
                'sort_order' => 3,
                'limits' => [
                    'max_patients_per_month' => -1,
                    'max_prescriptions_per_month' => -1,
                    'max_staff' => 6,
                    'max_doctors' => 6,
                ],
                'features' => [
                    'prescriptions', 'patients', 'vitals_exam', 'medicines_master',
                    'staff_management', 'payment_categories', 'revisit_rules',
                    'custom_branding', 'document_storage', 'analytics_reports'
                ],
            ],
            [
                'name' => 'Clinic Enterprise',
                'slug' => 'clinic-enterprise',
                'description' => 'Built for multi-specialty hospitals and polyclinics with large teams and unlimited capacity.',
                'price' => 3999.00,
                'billing_cycle' => BillingCycle::MONTHLY,
                'trial_days' => 0,
                'status' => PlanStatus::ACTIVE,
                'is_popular' => false,
                'sort_order' => 4,
                'limits' => [
                    'max_patients_per_month' => -1,
                    'max_prescriptions_per_month' => -1,
                    'max_staff' => -1,
                    'max_doctors' => 25,
                ],
                'features' => $allFeatureCodes,
            ],
        ];

        $createdPlans = [];
        foreach ($plans as $p) {
            $createdPlans[$p['slug']] = SubscriptionPlan::updateOrCreate(
                ['slug' => $p['slug']],
                $p
            );
        }

        // 3. Auto-assign Active Subscription to Existing Clinics
        $enterprisePlan = $createdPlans['clinic-enterprise'];
        $clinics = Clinic::where('isdeleted', 0)->get();
        foreach ($clinics as $clinic) {
            $exists = Subscription::where('subscriber_type', 'clinic')
                ->where('subscriber_id', $clinic->id)
                ->whereIn('status', ['active', 'trial'])
                ->exists();

            if (!$exists) {
                Subscription::create([
                    'subscriber_type' => 'clinic',
                    'subscriber_id' => $clinic->id,
                    'subscription_plan_id' => $enterprisePlan->id,
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addYear()->toDateString(),
                    'status' => SubscriptionStatus::ACTIVE,
                    'payment_reference' => 'SYSTEM-INITIAL-GRANT',
                    'notes' => 'Complimentary 1-Year Enterprise Subscription auto-granted upon system upgrade.',
                    'created_by' => 'Super Admin',
                ]);
            }
        }

        // 4. Attach any unattached doctors to the primary clinic so they belong to a clinic
        $defaultClinic = $clinics->first();
        if ($defaultClinic) {
            Doctor::where('isdeleted', 0)->whereNull('clinic_id')->update([
                'clinic_id' => $defaultClinic->id,
            ]);
        }
    }
}
