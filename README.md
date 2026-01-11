<h1>UL12DRIP <br/>
<small>(Unofficial Laravel 12 Documentation Reference Implementation/Playground) </small>
</h1>

The most extra Laravel 12 reference app ever — because why implement one feature when you can do them all?

![Laravel 12](https://img.shields.io/badge/Laravel-12.x-red?logo=laravel)
![PHP 8.4](https://img.shields.io/badge/PHP-8.4-blue?logo=php)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/deanhowe/ul12drip.svg?style=flat-square)](https://packagist.org/packages/deanhowe/moof-folio-markdown)
[![Tests](https://github.com/deanhowe/ul12drip/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/deanhowe/ul12drip/actions/workflows/run-tests.yml)
[![Code Style](https://github.com/deanhowe/ul12drip/actions/workflows/fix-php-code-style-issues.yml/badge.svg?branch=main)](https://github.com/deanhowe/ul12drip/actions/workflows/fix-php-code-style-issues.yml)
[![PHPStan](https://github.com/deanhowe/ul12drip/actions/workflows/phpstan.yml/badge.svg?branch=main)](https://github.com/deanhowe/ul12drip/actions/workflows/phpstan.yml)
[![Dependabot Auto-Merge](https://github.com/deanhowe/ul12drip/actions/workflows/dependabot-auto-merge.yml/badge.svg?branch=main)](https://github.com/deanhowe/ul12drip/actions/workflows/dependabot-auto-merge.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/deanhowe/ul12drip.svg?style=flat-square)](https://packagist.org/packages/deanhowe/ul12drip)


> **This project was built as a comprehensive reference implementation of Laravel 12.x features using [JetBrains Junie](https://www.jetbrains.com/junie/) + [Laravel Boost](https://github.com/laravel/boost), aiming for maximum documentation coverage.**

Unofficial, but comprehensive Laravel 12.x reference/showcase application demonstrating nearly all documented Laravel features, relationships, and ecosystem packages. This app serves as a working reference for Laravel documentation examples and achieves an **87% documentation coverage**.

📊 **[View Full Audit Report](docs/audit-report.md)**

---

## 🎯 Project Purpose

This repository serves as:
- **Learning Resource**: Explore real-world implementations of Laravel features
- **Reference Implementation**: Copy patterns and code for your own projects
- **Documentation Companion**: See how official Laravel docs translate to working code
- **AI-Assisted Development Showcase**: Demonstrates what's possible with JetBrains Junie + Laravel Boost

---

## ✨ Features Demonstrated

- **All Eloquent Relationships**: One-to-One, One-to-Many, Many-to-Many, Has One Through, Has Many Through, Polymorphic relations
- **API Resources**: Conditional attributes, collections with pagination, nested relationships
- **Form Requests**: Validation with custom messages and authorization
- **Model Observers**: Event handling and audit logging
- **Query Scopes**: Reusable query constraints
- **Factory States**: Multiple model states for testing
- **Enum Casts**: Status fields with helper methods
- **Soft Deletes**: On User, Post, Product, Order models
- **Traits**: Commentable, Taggable for polymorphic relationships
- **Pennant Feature Flags**: Class-based and closure-based features
- **Caching Service**: Comprehensive caching patterns
- **Activity Log**: Audit trail with separate SQLite database
- **Rate Limiting**: Multiple rate limit strategies
- **Billing (Laravel Cashier)**: Stripe subscriptions, invoices, payment methods, webhooks

## Installation

```bash
git clone <repository>
cd laravel_db_factories
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
touch database/activity_log.sqlite
php artisan migrate:fresh --seed
```

## Tinker Examples

Start Tinker with `php artisan tinker` and try these examples:

### Eloquent Relationships

```php
// One-to-Many: User has many Posts
$user = User::with('posts')->first();
$user->posts;

// One-to-One: User has one Phone
$user = User::with('phone')->first();
$user->phone;

// Many-to-Many: User has many Roles
$user = User::with('roles')->first();
$user->roles;

// Has One Through: Mechanic -> Car -> Owner
$mechanic = Mechanic::with('carOwner')->first();
$mechanic->carOwner;

// Has Many Through: Project -> Environment -> Deployment
$project = Project::with('deployments')->first();
$project->deployments;

// Polymorphic One-to-Many: Post/Video/Product has many Comments
$post = Post::with('comments')->first();
$post->comments;

// Polymorphic Many-to-Many: Post/Video has many Tags
$post = Post::with('tags')->first();
$post->tags;
```

### Query Scopes

```php
// User scopes
User::active()->get();
User::suspended()->get();
User::premium()->get();
User::admins()->get();
User::verified()->get();

// Post scopes
Post::published()->get();
Post::draft()->get();
Post::scheduled()->get();
Post::recent(7)->get();

// Product scopes
Product::active()->get();
Product::inStock()->get();
Product::outOfStock()->get();
Product::onSale()->get();

// Order scopes
Order::pending()->get();
Order::processing()->get();
Order::completed()->get();
Order::cancelled()->get();

// Task scopes
Task::highPriority()->get();
Task::overdue()->get();
Task::dueSoon(3)->get();
```

### Factory States

```php
// User states
User::factory()->verified()->create();
User::factory()->suspended()->create();
User::factory()->premium()->create();
User::factory()->admin()->create();
User::factory()->premiumAdmin()->create();

// Post states
Post::factory()->published()->create();
Post::factory()->draft()->create();
Post::factory()->scheduled()->create();

// Order states
Order::factory()->pending()->create();
Order::factory()->processing()->create();
Order::factory()->completed()->create();
Order::factory()->cancelled()->create();
Order::factory()->highValue()->create();

// Product states
Product::factory()->active()->create();
Product::factory()->inactive()->create();
Product::factory()->onSale()->create();
Product::factory()->outOfStock()->create();

// Task states
Task::factory()->pending()->create();
Task::factory()->inProgress()->create();
Task::factory()->completed()->create();
Task::factory()->urgent()->create();
```

### Enum Casts

```php
use App\Enums\OrderStatus;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;

// Order status
$order = Order::first();
$order->status;              // OrderStatus enum
$order->status->value;       // 'pending'
$order->status->label();     // 'Pending'
$order->status->color();     // 'yellow'

// Task status
$task = Task::first();
$task->status->isActive();   // true/false
$task->priority->sortOrder(); // 1, 2, or 3
```

### Soft Deletes

```php
// Soft delete a user
$user = User::first();
$user->delete();

// Query only trashed
User::onlyTrashed()->get();

// Query with trashed
User::withTrashed()->get();

// Restore
$user->restore();

// Force delete
$user->forceDelete();
```

### Pennant Feature Flags

```php
use Laravel\Pennant\Feature;
use App\Features\NewApi;
use App\Features\TeamBilling;

// Closure-based features
$user = User::first();
Feature::for($user)->active('dark-mode');
Feature::for($user)->active('beta-tester');
Feature::for($user)->value('homepage-variant'); // 'control', 'variant-a', 'variant-b'

// Global features
Feature::active('maintenance-mode');

// Class-based features
Feature::for($user)->active(NewApi::class);
Feature::for($user)->value(TeamBilling::class); // 'basic', 'premium', 'enterprise'

// Activate/deactivate
Feature::for($user)->activate('dark-mode');
Feature::for($user)->deactivate('dark-mode');
```

### Caching Service

```php
use App\Services\CacheService;

$cache = new CacheService();

// Get popular posts (cached)
$cache->getPopularPosts(10);

// Get user stats (cached with tags)
$cache->getUserStats(1);

// Clear user cache
$cache->clearUserCache(1);

// Increment view count
$cache->incrementViewCount('post', 1);
```

### Activity Log

```php
use App\Models\ActivityLog;

// Log an activity
$user = User::first();
$post = Post::first();
ActivityLog::log($user, $post, 'viewed', 'User viewed the post');

// Log model events
ActivityLog::logCreated($user, $post);
ActivityLog::logUpdated($user, $post);
ActivityLog::logDeleted($user, $post);

// Query activities
ActivityLog::forSubject($post)->get();
ActivityLog::byUser($user)->recent()->get();
ActivityLog::ofEvent('updated')->get();
```

### API Resources

```php
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\PostResource;

// Single resource
$user = User::with(['posts', 'roles'])->first();
(new UserResource($user))->toArray(request());

// Collection with pagination
$users = User::paginate(15);
(new UserCollection($users))->toArray(request());

// Post with nested relationships
$post = Post::with(['user', 'comments', 'tags'])->first();
(new PostResource($post))->toArray(request());
```

### Billing (Laravel Cashier + Stripe)

```php
use App\Models\User;
use App\Models\Plan;

// Get available plans
Plan::active()->get();
Plan::monthly()->get();
Plan::yearly()->get();
Plan::featured()->first();

// Plan details
$plan = Plan::where('slug', 'pro-monthly')->first();
$plan->formatted_price;  // "$29.99"
$plan->interval_label;   // "per month"
$plan->features;         // ['Feature 1', 'Feature 2', ...]

// Check subscription status
$user = User::first();
$user->subscribed('default');           // true/false
$user->subscribedToPrice('price_xxx');  // Check specific price
$user->subscription('default');         // Get subscription model

// Subscription details
$subscription = $user->subscription('default');
$subscription->active();        // Is active?
$subscription->onTrial();       // On trial?
$subscription->onGracePeriod(); // Cancelled but still active?
$subscription->cancelled();     // Is cancelled?
$subscription->ended();         // Has ended?

// Create subscription (requires payment method)
$user->newSubscription('default', 'price_xxx')->create($paymentMethodId);

// Create subscription with trial
$user->newSubscription('default', 'price_xxx')
    ->trialDays(14)
    ->create($paymentMethodId);

// Swap plans (with proration)
$user->subscription('default')->swap('price_new');

// Cancel subscription (at period end)
$user->subscription('default')->cancel();

// Cancel immediately
$user->subscription('default')->cancelNow();

// Resume cancelled subscription (during grace period)
$user->subscription('default')->resume();

// Get invoices
$user->invoices();
$user->invoicesIncludingPending();

// Download invoice PDF
$user->downloadInvoice($invoiceId);

// Payment methods
$user->paymentMethods();
$user->defaultPaymentMethod();
$user->addPaymentMethod($paymentMethodId);
$user->updateDefaultPaymentMethod($paymentMethodId);

// Create Setup Intent (for collecting payment method)
$user->createSetupIntent();

// Redirect to Stripe Customer Portal
$user->redirectToBillingPortal(route('billing.index'));

// Apply coupon to subscription
$user->subscription('default')->applyCoupon('COUPON_CODE');

// Report metered usage
$user->subscription('default')->reportUsage(100);
```

## Billing Routes

All billing routes require authentication (`/billing/*`):

| Route | Method | Description |
|-------|--------|-------------|
| `/billing` | GET | Billing dashboard |
| `/billing/plans` | GET | View available plans |
| `/billing/checkout/{plan}` | GET | Stripe Checkout for plan |
| `/billing/subscribe` | POST | Subscribe with payment method |
| `/billing/swap` | POST | Switch to different plan |
| `/billing/cancel` | POST | Cancel subscription |
| `/billing/resume` | POST | Resume cancelled subscription |
| `/billing/invoices` | GET | View all invoices |
| `/billing/invoices/{id}/download` | GET | Download invoice PDF |
| `/billing/portal` | GET | Redirect to Stripe Portal |
| `/billing/payment-method` | POST | Update payment method |
| `/billing/coupon` | POST | Apply coupon code |
| `/stripe/webhook` | POST | Stripe webhook endpoint |

## Billing Setup

To use billing features with real Stripe integration:

1. **Add Stripe keys to `.env`:**
```env
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx
```

2. **Create products/prices in Stripe Dashboard** and update `PlanSeeder` with real price IDs.

3. **Configure webhook in Stripe Dashboard:**
    - URL: `https://your-domain.com/stripe/webhook`
    - Events: `customer.subscription.*`, `invoice.*`, `payment_method.*`

4. **Or use Stripe CLI for local testing:**
```bash
stripe listen --forward-to localhost:8000/stripe/webhook
```

## API Endpoints

### Public Endpoints
- `GET /api/posts` - List posts (paginated)
- `GET /api/posts/{post}` - Show post
- `GET /api/products` - List products (paginated)
- `GET /api/products/{product}` - Show product
- `GET /api/search?q=term` - Search posts and products

### Authenticated Endpoints (requires Sanctum)
- `POST /api/posts` - Create post
- `PUT /api/posts/{post}` - Update post
- `DELETE /api/posts/{post}` - Delete post
- `GET /api/users` - List users
- `POST /api/users` - Create user
- `GET /api/users/{user}` - Show user
- `PUT /api/users/{user}` - Update user
- `DELETE /api/users/{user}` - Delete user

## Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ModelsTest.php

# Run with filter
php artisan test --filter=testUserCanBeCreatedWithFactory
```

## Code Style

```bash
# Format code with Pint
vendor/bin/pint

# Check only (no changes)
vendor/bin/pint --test
```

## Project Structure

```
app/
├── Enums/           # OrderStatus, TaskStatus, TaskPriority, DeploymentStatus
├── Features/        # Pennant class-based features
├── Http/
│   ├── Controllers/
│   │   ├── Api/     # API controllers
│   │   ├── BillingController.php      # Subscription & billing management
│   │   └── StripeWebhookController.php # Stripe webhook handling
│   ├── Requests/    # Form request validation
│   └── Resources/   # API resources and collections
├── Listeners/
│   └── StripeEventListener.php  # Cashier webhook event listener
├── Models/
│   ├── Plan.php     # Subscription plans model
│   └── ...          # Other Eloquent models
├── Observers/       # Model event observers
├── Services/        # CacheService
└── Traits/          # Commentable, Taggable

resources/views/
├── billing/
│   ├── index.blade.php    # Billing dashboard
│   ├── plans.blade.php    # Subscription plans page
│   └── invoices.blade.php # Invoice history
└── ...

tests/Feature/Billing/
├── BillingTest.php   # Billing routes & functionality tests
├── PlanTest.php      # Plan model tests
└── WebhookTest.php   # Webhook handling tests
```

## License

The UL12DRIP application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
