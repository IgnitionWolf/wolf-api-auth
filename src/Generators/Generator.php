<?php

namespace IgnitionWolf\API\Auth\Generators;

use IgnitionWolf\API\Auth\Support\Stub;
use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command as Console;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class Generator
{
    protected Filesystem $filesystem;
    protected ?Console $console = null;
    protected Config $config;

    /**
     * The base directory of the application.
     * @var string
     */
    protected string $baseDirectory;

    /**
     * The base namespace of the application.
     * @var string
     */
    protected string $baseNamespace;

    /**
     * The configured user model of the application
     * @var string|null
     */
    protected ?string $userModel = null;

    public function __construct(Filesystem $filesystem, Config $config)
    {
        $this->filesystem = $filesystem;
        $this->config = $config;

        $this->baseNamespace = config('api-auth.namespace');
        $this->baseDirectory = base_path();
        $this->userModel = config('auth.providers.users.model', 'App\User');
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
                    'BASENAMESPACE' => $this->getBaseNamespace(),
                    'NAMESPACE' => $namespace,
                    'CLASSNAME' => $classname,
                    'USERCLASS' => substr($this->userModel, strrpos($this->userModel, '\\') + 1),
                    'USERNAMESPACE' => $this->userModel,
                ]))->getContents()
            );

            $this->info("Created: $path");
        }

        return 0;
    }

    private function addSanctumPackage(): void
    {
        (new Process(['composer', 'require', 'laravel/sanctum'], base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->console->getOutput()->write($output);
            });
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

    private function info(string $message): void
    {
        if ($this->console) {
            $this->console->info($message);
        }
    }
}
