<?php

namespace Ensue\Snap\Foundation\Database\Trait;

use Ensue\Snap\Foundation\Database\Scope\StatusPublishedScope;

trait StatusPublished
{
    /**
     * @return void
     */
    public static function bootStatusPublished(): void
    {
        static::addGlobalScope(new StatusPublishedScope());
    }
}
