<?php

namespace App\Providers;

use App\Interfaces\SmsServiceInterface;
use App\Listeners\StripeEventListener;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use App\Observers\AuditObserver;
use App\Observers\OrderObserver;
use App\Observers\PostObserver;
use App\Observers\UserObserver;
use App\Reports\InventoryReport;
use App\Reports\SalesReport;
use App\Services\LogSmsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Lottery;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Events\WebhookHandled;
use Laravel\Cashier\Events\WebhookReceived;
use Laravel\Pennant\Feature;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Demonstrates:
     * - Interface binding (SmsServiceInterface → LogSmsService)
     * - Container tagging (report generators tagged as 'reports')
     *
     * Usage for tagged services:
     *   $reports = app()->tagged('reports');
     *   foreach ($reports as $report) {
     *       $data = $report->generate();
     *   }
     */
    public function register(): void
    {
        // Interface binding
        $this->app->bind(SmsServiceInterface::class, LogSmsService::class);

        /*
        |--------------------------------------------------------------------------
        | Container Tagging
        |--------------------------------------------------------------------------
        |
        | Tag multiple implementations with a common tag for batch resolution.
        | This is useful for collecting all implementations of a contract.
        |
        */
        $this->app->tag([
            SalesReport::class,
            InventoryReport::class,
        ], 'reports');
    }

    /**
     * Bootstrap any application services.
     *
     * This method demonstrates:
     * - Model observer registration
     * - Closure-based Pennant feature definitions
     *
     * Usage in Tinker:
     *   use Laravel\Pennant\Feature;
     *   Feature::active('dark-mode'); // Check if dark mode is active
     *   Feature::for($user)->active('beta-tester'); // Check for specific user
     *   Feature::value('homepage-variant'); // Get A/B test variant
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Eloquent Strictness
        |--------------------------------------------------------------------------
        |
        | Instruct Eloquent to be strict in non-production environments.
        | This prevents lazy loading, prevents silent mass assignment,
        | and prevents accessing missing attributes.
        |
        */
        Model::shouldBeStrict(! $this->app->isProduction());

        /*
        |--------------------------------------------------------------------------
        | Model Observers
        |--------------------------------------------------------------------------
        |
        | Register model observers for event handling and audit logging.
        |
        */
        Order::observe(OrderObserver::class);
        Order::observe(AuditObserver::class);
        Post::observe(PostObserver::class);
        Post::observe(AuditObserver::class);
        User::observe(UserObserver::class);
        Product::observe(AuditObserver::class);

        /*
        |--------------------------------------------------------------------------
        | Custom Blade Directives
        |--------------------------------------------------------------------------
        |
        | Register custom blade directives for the application.
        |
        */
        Blade::directive('money', function ($expression) {
            return "<?php echo number_format($expression, 2); ?>";
        });

        /*
        |--------------------------------------------------------------------------
        | Authorization Gates
        |--------------------------------------------------------------------------
        |
        | Define custom authorization gates for the application.
        |
        */
        Gate::define('access-admin', function (User $user) {
            return $user->is_admin;
        });

        /*
        |--------------------------------------------------------------------------
        | Closure-Based Feature: dark-mode
        |--------------------------------------------------------------------------
        |
        | A simple boolean feature flag. Demonstrates the most basic usage
        | of Pennant with a closure that returns true/false.
        |
        */
        Feature::define('dark-mode', fn (User $user) => $user->id % 2 === 0);

        /*
        |--------------------------------------------------------------------------
        | Closure-Based Feature: beta-tester
        |--------------------------------------------------------------------------
        |
        | Feature flag for beta testing. Uses Lottery to randomly select
        | 20% of users for beta features.
        |
        */
        Feature::define('beta-tester', fn (User $user) => Lottery::odds(1, 5)->choose());

        /*
        |--------------------------------------------------------------------------
        | Closure-Based Feature: homepage-variant (Rich Value / A/B Testing)
        |--------------------------------------------------------------------------
        |
        | Demonstrates rich feature values for A/B testing. Returns a variant
        | name instead of a boolean, allowing multiple test variations.
        |
        */
        Feature::define('homepage-variant', function (User $user) {
            return match ($user->id % 3) {
                0 => 'control',
                1 => 'variant-a',
                2 => 'variant-b',
            };
        });

        /*
        |--------------------------------------------------------------------------
        | Closure-Based Feature: maintenance-mode (Global Feature)
        |--------------------------------------------------------------------------
        |
        | A global feature that doesn't depend on user scope. Useful for
        | site-wide toggles like maintenance mode.
        |
        */
        Feature::define('maintenance-mode', fn () => false);

        /*
        |--------------------------------------------------------------------------
        | Stripe Webhook Event Listeners (Laravel Cashier)
        |--------------------------------------------------------------------------
        |
        | Register listeners for Cashier's webhook events. These provide an
        | alternative to extending the WebhookController for handling
        | Stripe webhook events.
        |
        | WebhookReceived: Fired BEFORE Cashier processes the webhook
        | WebhookHandled: Fired AFTER Cashier processes the webhook
        |
        | Usage in Tinker:
        |   event(new \Laravel\Cashier\Events\WebhookReceived(['type' => 'test']));
        |
        */
        Event::listen(
            WebhookReceived::class,
            [StripeEventListener::class, 'handleWebhookReceived']
        );

        Event::listen(
            WebhookHandled::class,
            [StripeEventListener::class, 'handleWebhookHandled']
        );
    }
}
