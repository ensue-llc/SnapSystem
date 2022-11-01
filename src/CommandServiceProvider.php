<?php

namespace Lyb\EnsueSystem;

use Illuminate\Support\ServiceProvider as AbstractServiceProvider;
use Lyb\EnsueSystem\Commands\AngularAppDeploy;
use Lyb\EnsueSystem\Commands\ModuleGenerateCommand;

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

    public function boot()
    {
        $this->commands($this->commands);
    }
}
