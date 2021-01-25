<?php

namespace IgnitionWolf\API\Auth\Support;

class ModulesBridge
{
    /**
     * Get the namespace of the modules directory.
     *
     * @return string
     */
    public function getNamespace(): string
    {
        return config('modules.namespace', 'Modules');
    }

    /**
     * Check if the laravel-modules package by Nicolas Widart is installed.
     *
     * @return bool
     */
    public function isInstalled(): bool
    {
        return function_exists('module_path');
    }
}
