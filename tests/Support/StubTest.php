<?php

namespace IgnitionWolf\API\Auth\Tests\Support;

use IgnitionWolf\API\Auth\Support\Stub;
use IgnitionWolf\API\Auth\Tests\TestCase;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * @author Nicolas Widart
 */
class StubTest extends TestCase
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function setUp(): void
    {
        parent::setUp();
        $this->filesystem = app(Filesystem::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->filesystem->delete([
            base_path('my-command.php'),
            base_path('stub-override-exists.php'),
            base_path('stub-override-not-exists.php'),
        ]);
    }

    public function testItInitialisesAStubInstance()
    {
        $stub = new Stub('/controllers/authentication.stub', [
            'CLASSNAME' => 'ClassName',
        ]);

        $this->assertTrue(Str::contains($stub->getPath(), 'Commands/stubs/controllers/authentication.stub'));
        $this->assertEquals(['CLASSNAME' => 'ClassName'], $stub->getReplaces());
    }

    public function testItSetsNewReplacesArray()
    {
        $stub = new Stub('/controllers/authentication.stub', [
            'NAMESPACE' => 'Http\\Controllers\\',
            'CLASSNAME' => 'ClassName',
        ]);

        $stub->replace(['VENDOR' => 'MyVendor',]);
        $this->assertEquals(['VENDOR' => 'MyVendor',], $stub->getReplaces());
    }

    public function testItStoresStubToSpecificPath()
    {
        $stub = new Stub('/controllers/authentication.stub', [
            'NAMESPACE' => 'Http\\Controllers\\',
            'CLASSNAME' => 'ClassName',
        ]);

        $stub->saveTo(base_path(), 'my-command.php');

        $this->assertTrue($this->filesystem->exists(base_path('my-command.php')));
    }

    public function testItSetsNewPath()
    {
        $stub = new Stub('/controllers/authentication.stub', [
            'NAMESPACE' => 'Http\\Controllers\\',
            'CLASSNAME' => 'ClassName',
        ]);

        $stub->setPath('/new-path/');

        $this->assertTrue(Str::contains($stub->getPath(), 'Commands/stubs/new-path/'));
    }

    public function testUseDefaultStubIfOverrideNotExists()
    {
        $stub = new Stub('/controllers/authentication.stub', [
            'NAMESPACE' => 'Http\\Controllers\\',
            'CLASSNAME' => 'ClassName',
        ]);

        $stub->setBasePath(__DIR__ . '/stubs');

        $stub->saveTo(base_path(), 'stub-override-not-exists.php');

        $this->assertTrue($this->filesystem->exists(base_path('stub-override-not-exists.php')));
    }
}
