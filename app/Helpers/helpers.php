<?php

if (! function_exists('format_currency')) {
    /**
     * Format a number as currency.
     */
    function format_currency(float $amount, string $currency = 'USD'): string
    {
        return $currency.' '.number_format($amount, 2);
    }
}

if (! function_exists('is_admin')) {
    /**
     * Check if the current authenticated user is an admin.
     */
    function is_admin(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }
}
