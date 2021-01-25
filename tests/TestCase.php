<?php

namespace IgnitionWolf\API\Auth\Tests;

use IgnitionWolf\API\Auth\AuthServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public static array $config = [];

    public function setUp(): void
    {
        parent::setUp();

        if (!self::$config) {
            self::$config = require 'config/config.php';
            $this->assertNotEmpty(self::$config);
        }
    }

    protected function getPackageProviders($app): array
    {
        return [AuthServiceProvider::class];
    }
}
