<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Airline;
use App\Models\Car;
use App\Models\Category;
use App\Models\Country;
use App\Models\Deployment;
use App\Models\Environment;
use App\Models\Flight;
use App\Models\Image;
use App\Models\Mechanic;
use App\Models\Order;
use App\Models\Owner;
use App\Models\Phone;
use App\Models\Podcast;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * This seeder creates comprehensive demo data for all Laravel documentation
     * examples including relationships, polymorphic relations, and more.
     */
    public function run(): void
    {
        // =====================================================================
        // ROLES (Many-to-Many with Users)
        // =====================================================================
        $roles = collect(['admin', 'editor', 'author', 'subscriber', 'moderator', 'manager'])
            ->map(fn ($name) => Role::create(['name' => $name, 'description' => "The {$name} role"]));

        // =====================================================================
        // TEST USER
        // =====================================================================
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $testUser->roles()->attach($roles->first()); // Attach admin role

        // =====================================================================
        // USERS WITH RELATIONSHIPS
        // =====================================================================
        User::factory(10)
            ->has(Post::factory(3)->hasComments(5)) // One-to-Many with Posts, Posts have Comments
            ->has(Phone::factory()) // One-to-One with Phone
            ->has(Order::factory(2)) // One-to-Many with Orders
            ->has(Address::factory(2)) // Polymorphic One-to-Many
            ->has(Image::factory()) // Polymorphic One-to-Many
            ->create()
            ->each(function ($user) use ($roles) {
                // Attach random roles (Many-to-Many)
                $user->roles()->attach(
                    $roles->random(rand(1, 3))->pluck('id')->toArray(),
                    ['assigned_at' => now()]
                );
            });

        // =====================================================================
        // TAGS (Polymorphic Many-to-Many)
        // =====================================================================
        $tags = Tag::factory(10)->create();

        // Attach tags to posts
        Post::all()->each(function ($post) use ($tags) {
            $post->tags()->attach($tags->random(rand(1, 4))->pluck('id')->toArray());
        });

        // =====================================================================
        // VIDEOS WITH TAGS (Polymorphic Many-to-Many)
        // =====================================================================
        Video::factory(15)->create()->each(function ($video) use ($tags) {
            $video->tags()->attach($tags->random(rand(1, 3))->pluck('id')->toArray());
        });

        // =====================================================================
        // PODCASTS
        // =====================================================================
        Podcast::factory(15)->create();

        // =====================================================================
        // AIRLINES AND FLIGHTS (One-to-Many)
        // =====================================================================
        Airline::factory(5)
            ->has(Flight::factory(5))
            ->create();

        // =====================================================================
        // MECHANICS, CARS, AND OWNERS (Has One Through)
        // Mechanic -> Car -> Owner
        // =====================================================================
        Mechanic::factory(10)->create()->each(function ($mechanic) {
            $car = Car::factory()->create(['mechanic_id' => $mechanic->id]);
            Owner::factory()->create(['car_id' => $car->id]);
        });

        // =====================================================================
        // COUNTRIES AND SUPPLIERS (For Has One/Many Through examples)
        // =====================================================================
        Country::factory(5)
            ->has(Supplier::factory(3)->has(Address::factory()))
            ->create();

        // =====================================================================
        // CATEGORIES AND PRODUCTS (Many-to-Many, Self-referential)
        // =====================================================================
        $parentCategories = Category::factory(5)->create();
        $childCategories = Category::factory(10)->create([
            'parent_id' => fn () => $parentCategories->random()->id,
        ]);

        $products = Product::factory(20)->create();
        $allCategories = $parentCategories->merge($childCategories);

        // Attach products to categories
        $products->each(function ($product) use ($allCategories) {
            $product->categories()->attach(
                $allCategories->random(rand(1, 3))->pluck('id')->toArray()
            );
            // Add images to products (Polymorphic)
            Image::factory(rand(1, 3))->create([
                'imageable_type' => Product::class,
                'imageable_id' => $product->id,
            ]);
        });

        // =====================================================================
        // PROJECTS, ENVIRONMENTS, DEPLOYMENTS (Has Many Through)
        // Project -> Environment -> Deployment
        // =====================================================================
        Project::factory(5)
            ->has(
                Environment::factory(3)
                    ->has(Deployment::factory(4))
            )
            ->has(Task::factory(5))
            ->create();
    }
}
