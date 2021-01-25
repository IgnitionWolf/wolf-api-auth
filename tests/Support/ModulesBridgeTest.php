<?php

namespace IgnitionWolf\API\Auth\Tests\Support;

use IgnitionWolf\API\Auth\Support\ModulesBridge;
use IgnitionWolf\API\Auth\Tests\TestCase;

class ModulesBridgeTest extends TestCase
{
    protected static ?ModulesBridge $modules = null;

    public function setUp(): void
    {
        parent::setUp();
        if (!static::$modules) {
            static::$modules = app(ModulesBridge::class);
        }
    }

    public function testGetNamespaceMethod()
    {
        $this->assertEquals('Modules', static::$modules->getNamespace());
        config(['modules.namespace' => 'Test']);
        $this->assertEquals('Test', static::$modules->getNamespace());
    }
}
