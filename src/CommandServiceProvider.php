<?php

namespace Ensue\NicoSystem;

use Illuminate\Support\ServiceProvider as AbstractServiceProvider;
use Ensue\NicoSystem\Commands\AngularAppDeploy;
use Ensue\NicoSystem\Commands\ModuleGenerateCommand;

class CommandServiceProvider extends AbstractServiceProvider
{

    /**
     * @var bool
     */
    protected bool $defer = true;

    /**
     * @var array|string[]
     */
    protected array $commands = [
        ModuleGenerateCommand::class,
        AngularAppDeploy::class,
    ];

    public function boot(): void
    {
        $this->commands($this->commands);
    }
}
