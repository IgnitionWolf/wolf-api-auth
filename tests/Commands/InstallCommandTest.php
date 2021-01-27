<?php

namespace IgnitionWolf\API\Auth\Tests\Commands;

use IgnitionWolf\API\Auth\Support\ModulesBridge;
use IgnitionWolf\API\Auth\Tests\TestCase;

class InstallCommandTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();

        foreach (self::$config['stubs']['files'] as $path) {
            @unlink(base_path() . '/app/' . $path . '.php');
            @unlink(base_path() . '/Modules/User/' . str_replace('app', '', $path) . '.php');
        }
    }

    public function testCreatesFiles()
    {
        $this->artisan('api:auth')->assertExitCode(0);

        foreach (self::$config['stubs']['files'] as $path) {
            $this->assertFileExists(base_path() . '/' . $path . '.php');
        }
    }

    public function testWithLaravelModules()
    {
        $this->partialMock(ModulesBridge::class, function ($mock) {
            return $mock->shouldReceive('isInstalled')->once()->andReturn(true);
        });

        $this->artisan('api:auth')
            ->expectsConfirmation(
                'The laravel-modules package was detected. Do you want to install in a specific module?',
                'yes'
            )
            ->expectsQuestion('Please type the name of the module. (case-sensitive)', 'User')
            ->assertExitCode(0);

        foreach (self::$config['stubs']['files'] as $path) {
            $this->assertFileExists(base_path() . '/Modules/User/' . str_replace('app', '', $path) . '.php');
        }
    }

    public function testShouldNotAllowSecondInstallation()
    {
        $this->artisan('api:auth')->assertExitCode(0);
        $this->artisan('api:auth')->assertExitCode(E_ERROR);
    }

    public function testShouldAllowSecondInstallationWithForce()
    {
        $this->artisan('api:auth')->assertExitCode(0);
        $this->artisan('api:auth', ['--force' => true])->assertExitCode(0);
    }
}
