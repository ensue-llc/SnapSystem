<?php

namespace Ensue\Snap\Foundation\Database\trait;

use Ensue\Snap\Foundation\Database\Scope\StatusUnpublishedScope;

trait StatusUnpublished
{
    public static function bootStatusPublished(): void
    {
        static::addGlobalScope(new StatusUnpublishedScope());
    }
}
