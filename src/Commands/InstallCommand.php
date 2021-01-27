<?php

namespace IgnitionWolf\API\Auth\Commands;

use IgnitionWolf\API\Auth\Generators\Generator;
use IgnitionWolf\API\Auth\Support\ModulesBridge;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:auth {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the authentication files in the application directory.';

    protected Filesystem $filesystem;

    protected ModulesBridge $modules;

    public function __construct(Filesystem $filesystem, ModulesBridge $modules)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
        $this->modules = $modules;
    }

    protected function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when the auth is already installed.'],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->isInstalled() && !$this->option('force')) {
            $this->error('The files may already have been installed, please run this with --force.');
            return E_ERROR;
        }

        $namespace = null;
        if ($this->modules->isInstalled()) {
            if ($this->confirm('The laravel-modules package was detected. Do you want to install in a specific module?')) {
                $namespace = sprintf(
                    '%s%s%s',
                    config('modules.namespace', 'Modules'),
                    '\\',
                    $this->ask('Please type the name of the module. (case-sensitive)')
                );
            }
        }

        $generator = app(Generator::class)->setConsole($this);

        if ($namespace) {
            $generator->setBaseDirectory(base_path(str_replace('\\', '/', $namespace)));
            $generator->setBaseNamespace($namespace);
        } else {
            $generator->setBaseDirectory(base_path('app'));
        }

        $code = $generator->generate();
        $this->info("The authentication structure has been initiated successfully.");
        return $code;
    }

    /**
     * Check if the authentication is already installed or not.
     * @return bool
     */
    private function isInstalled(): bool
    {
        if (!empty(glob(
            sprintf(
                '%s/{%s/**/,app/}Http/Controllers/AuthenticationController.php',
                base_path(),
                config('modules.namespace', 'Modules')
            ),
            GLOB_BRACE
        ))) {
            return true;
        }
        return false;
    }
}
