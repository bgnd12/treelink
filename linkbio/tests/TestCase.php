<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        // Never let a cached local config make RefreshDatabase touch MySQL.
        // Feature tests must always run against an isolated in-memory database.
        $app->make('config')->set('database.default', 'sqlite');
        $app->make('config')->set('database.connections.sqlite.database', ':memory:');

        return $app;
    }
}
