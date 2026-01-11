<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Browser tests for the home page.
 *
 * Demonstrates:
 * - Basic Dusk browser testing
 * - Page navigation and assertions
 * - Element visibility checks
 * - Text content assertions
 */
class HomePageTest extends DuskTestCase
{
    /**
     * Test that the home page loads successfully.
     */
    public function test_home_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertStatus(200)
                ->assertSee('Laravel');
        });
    }

    /**
     * Test that the navigation is visible.
     */
    public function test_navigation_is_visible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertPresent('nav')
                ->assertSee('Laravel Demo');
        });
    }

    /**
     * Test that login link is present for guests.
     */
    public function test_login_link_is_present(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSeeLink('Log in');
        });
    }

    /**
     * Test that register link is present for guests.
     */
    public function test_register_link_is_present(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSeeLink('Register');
        });
    }
}
