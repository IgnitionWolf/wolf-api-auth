<?php

namespace IgnitionWolf\API\Auth\Commands;

use IgnitionWolf\API\Auth\Generators\Generator;
use IgnitionWolf\API\Auth\Support\ModulesBridge;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:auth';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->isInstalled()) {
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

        $generator = app(Generator::class);

        if ($namespace) {
            $generator->setBaseDirectory(base_path(str_replace('\\', '/', $namespace)));
            $generator->setBaseNamespace($namespace);
        } else {
            $generator->setBaseDirectory(base_path('app'));
        }

        return $generator->generate();
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
