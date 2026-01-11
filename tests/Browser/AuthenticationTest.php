<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Browser tests for authentication flows.
 *
 * Demonstrates:
 * - Form interactions with Dusk
 * - User authentication testing
 * - Database migrations in browser tests
 * - Typing and clicking elements
 */
class AuthenticationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test that the login page loads.
     */
    public function test_login_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Log in')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]');
        });
    }

    /**
     * Test that the registration page loads.
     */
    public function test_registration_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee('Register')
                ->assertPresent('input[name="name"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]');
        });
    }

    /**
     * Test login with invalid credentials shows error.
     */
    public function test_login_with_invalid_credentials_shows_error(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'invalid@example.com')
                ->type('password', 'wrongpassword')
                ->press('Log in')
                ->assertSee('These credentials do not match our records');
        });
    }

    /**
     * Test successful user login.
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Log in')
                ->assertPathIs('/home');
        });
    }

    /**
     * Test user can navigate from login to register.
     */
    public function test_can_navigate_to_register_from_login(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->clickLink('Register')
                ->assertPathIs('/register');
        });
    }
}
