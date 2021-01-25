<?php

namespace IgnitionWolf\API\Auth\Generators;

use IgnitionWolf\API\Auth\Support\Stub;
use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command as Console;
use Illuminate\Filesystem\Filesystem;

class Generator
{
    protected Filesystem $filesystem;
    protected Console $console;
    protected Config $config;
    protected string $baseDirectory;
    protected string $baseNamespace;

    public function __construct(Filesystem $filesystem, Console $console, Config $config)
    {
        $this->filesystem = $filesystem;
        $this->console = $console;
        $this->config = $config;

        $this->baseNamespace = config('api-auth.namespace');
        $this->baseDirectory = base_path();
    }

    /**
     * Generate the authentication file structure.
     * @return int
     */
    public function generate(): int
    {
        $files = $this->config->get('api-auth.stubs.files');
        foreach ($files as $file => $path) {
            $namespace = $this->pathToNamespace($path);
            $classname = basename($path);
            $path = $this->getFileDestination($path);

            $this->filesystem->ensureDirectoryExists(dirname($path), 0755);
            $this->filesystem->put(
                $path,
                (new Stub('/' . $file . '.stub', [
                    'NAMESPACE' => $namespace,
                    'CLASSNAME' => $classname,
                ]))->getContents()
            );

            //$this->console->info("Created: $path");
        }

        //$this->console->info("The authentication structure has been initiated successfully.");
        return 0;
    }

    /**
     * Helper function to obtain the target path of a file / stub.
     * @param string $path
     * @return string
     */
    public function getFileDestination(string $path): string
    {
        return sprintf('%s/%s.php', $this->getBaseDirectory(), $path);
    }

    /**
     * Helper function to convert a path to a namespace.
     * @param $path
     * @return string|string[]
     */
    public function pathToNamespace($path)
    {
        return $this->getBaseNamespace() . '\\' . str_replace('/', '\\', dirname($path));
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }

    /**
     * @param Filesystem $filesystem
     * @return Generator
     */
    public function setFilesystem(Filesystem $filesystem): Generator
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * @return Console
     */
    public function getConsole(): Console
    {
        return $this->console;
    }

    /**
     * @param Console $console
     * @return Generator
     */
    public function setConsole(Console $console): Generator
    {
        $this->console = $console;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseDirectory(): string
    {
        return $this->baseDirectory;
    }

    /**
     * @param string $baseDirectory
     * @return Generator
     */
    public function setBaseDirectory(string $baseDirectory): Generator
    {
        $this->baseDirectory = $baseDirectory;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseNamespace(): string
    {
        return $this->baseNamespace;
    }

    /**
     * @param string $baseNamespace
     * @return Generator
     */
    public function setBaseNamespace(string $baseNamespace): Generator
    {
        $this->baseNamespace = $baseNamespace;

        return $this;
    }
}
