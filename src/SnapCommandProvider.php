<?php

namespace Ensue\Snap;

use Illuminate\Support\ServiceProvider as AbstractServiceProvider;
use Ensue\Snap\Commands\SnapAngularAppDeploy;
use Ensue\Snap\Commands\SnapGenerateCommand;

class SnapCommandProvider extends AbstractServiceProvider
{
    /**
     * @var bool
     */
    protected bool $defer = true;

    /**
     * @var array|string[]
     */
    protected array $commands = [
        SnapGenerateCommand::class,
        SnapAngularAppDeploy::class,
    ];

    public function boot(): void
    {
        $this->commands($this->commands);
    }
}
