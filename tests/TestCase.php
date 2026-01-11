<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * The database connections that should have their transactions started.
     *
     * @var array
     */
    protected $connectionsToTransact = ['sqlite', 'activity_log'];
}
