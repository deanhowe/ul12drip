<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

/**
 * Seeder for subscription billing plans.
 *
 * Creates example plans that demonstrate Laravel Cashier integration.
 * These plans use placeholder Stripe Price IDs - replace with real
 * IDs from your Stripe Dashboard in production.
 *
 * To create matching prices in Stripe:
 * 1. Go to Stripe Dashboard > Products
 * 2. Create a product for each tier (Basic, Pro, Enterprise)
 * 3. Add monthly and yearly prices to each product
 * 4. Copy the price IDs (price_xxx) and update this seeder
 *
 * Run with: php artisan db:seed --class=PlanSeeder
 */
class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            // Basic Tier - Entry level
            [
                'name' => 'Basic Monthly',
                'slug' => 'basic-monthly',
                'stripe_price_id' => 'price_basic_monthly',  // Replace with real Stripe Price ID
                'price' => 999,  // $9.99
                'interval' => 'month',
                'interval_count' => 1,
                'description' => 'Perfect for individuals and small projects.',
                'features' => [
                    '5 projects',
                    '1 GB storage',
                    'Email support',
                    'Basic analytics',
                ],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Basic Yearly',
                'slug' => 'basic-yearly',
                'stripe_price_id' => 'price_basic_yearly',  // Replace with real Stripe Price ID
                'price' => 9990,  // $99.90 (2 months free)
                'interval' => 'year',
                'interval_count' => 1,
                'description' => 'Perfect for individuals and small projects. Save 17% with yearly billing!',
                'features' => [
                    '5 projects',
                    '1 GB storage',
                    'Email support',
                    'Basic analytics',
                ],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2,
            ],

            // Pro Tier - Most popular
            [
                'name' => 'Pro Monthly',
                'slug' => 'pro-monthly',
                'stripe_price_id' => 'price_pro_monthly',  // Replace with real Stripe Price ID
                'price' => 2999,  // $29.99
                'interval' => 'month',
                'interval_count' => 1,
                'description' => 'Best for growing teams and businesses.',
                'features' => [
                    'Unlimited projects',
                    '50 GB storage',
                    'Priority email support',
                    'Advanced analytics',
                    'Team collaboration',
                    'API access',
                ],
                'is_active' => true,
                'is_featured' => true,  // Highlight as recommended
                'sort_order' => 3,
            ],
            [
                'name' => 'Pro Yearly',
                'slug' => 'pro-yearly',
                'stripe_price_id' => 'price_pro_yearly',  // Replace with real Stripe Price ID
                'price' => 29990,  // $299.90 (2 months free)
                'interval' => 'year',
                'interval_count' => 1,
                'description' => 'Best for growing teams and businesses. Save 17% with yearly billing!',
                'features' => [
                    'Unlimited projects',
                    '50 GB storage',
                    'Priority email support',
                    'Advanced analytics',
                    'Team collaboration',
                    'API access',
                ],
                'is_active' => true,
                'is_featured' => true,  // Highlight as recommended
                'sort_order' => 4,
            ],

            // Enterprise Tier - Full featured
            [
                'name' => 'Enterprise Monthly',
                'slug' => 'enterprise-monthly',
                'stripe_price_id' => 'price_enterprise_monthly',  // Replace with real Stripe Price ID
                'price' => 9999,  // $99.99
                'interval' => 'month',
                'interval_count' => 1,
                'description' => 'For large organizations with advanced needs.',
                'features' => [
                    'Unlimited everything',
                    '500 GB storage',
                    '24/7 phone & email support',
                    'Custom analytics & reporting',
                    'Advanced team management',
                    'Full API access',
                    'SSO / SAML integration',
                    'Dedicated account manager',
                    'Custom integrations',
                ],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'Enterprise Yearly',
                'slug' => 'enterprise-yearly',
                'stripe_price_id' => 'price_enterprise_yearly',  // Replace with real Stripe Price ID
                'price' => 99990,  // $999.90 (2 months free)
                'interval' => 'year',
                'interval_count' => 1,
                'description' => 'For large organizations with advanced needs. Save 17% with yearly billing!',
                'features' => [
                    'Unlimited everything',
                    '500 GB storage',
                    '24/7 phone & email support',
                    'Custom analytics & reporting',
                    'Advanced team management',
                    'Full API access',
                    'SSO / SAML integration',
                    'Dedicated account manager',
                    'Custom integrations',
                ],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 6,
            ],

            // Metered Plan Example - Usage-based billing
            [
                'name' => 'Pay As You Go',
                'slug' => 'metered',
                'stripe_price_id' => 'price_metered',  // Replace with real Stripe metered Price ID
                'price' => 0,  // Base price is $0, charged per usage
                'interval' => 'month',
                'interval_count' => 1,
                'description' => 'Pay only for what you use. $0.01 per API call.',
                'features' => [
                    'No monthly commitment',
                    'Pay per API call ($0.01/call)',
                    'All features included',
                    'Email support',
                    'Usage dashboard',
                ],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 7,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
