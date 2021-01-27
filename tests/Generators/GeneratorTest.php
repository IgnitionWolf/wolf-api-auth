<?php

namespace IgnitionWolf\API\Auth\Tests\Generators;

use IgnitionWolf\API\Auth\Generators\Generator;
use IgnitionWolf\API\Auth\Tests\TestCase;

class GeneratorTest extends TestCase
{
    public function testGenerateMethod()
    {
        $generator = app(Generator::class);
        $generator->setBaseDirectory(base_path());
        $generator->generate();

        foreach (self::$config['stubs']['files'] as $path) {
            $this->assertFileExists($generator->getBaseDirectory() . '/' . $path . '.php');
        }
    }

    public function testPathToNamespaceMethod()
    {
        $generator = app(Generator::class);
        $generator->setBaseDirectory(base_path());

        $this->assertEquals(
            'App\\Events',
            $generator->pathToNamespace('Events/MyEvent')
        );

        $this->assertEquals(
            'App\\Events',
            $generator->pathToNamespace('Events/MyEvent')
        );

        $generator->setBaseNamespace('Modules\\User');

        $this->assertEquals(
            'Modules\\User\\Events',
            $generator->pathToNamespace('Events/MyEvent')
        );
    }
}
