<?php

namespace Ensue\Snap\Foundation\Database\Scope;

use Ensue\Snap\Foundation\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class StatusPublishedScope implements Scope
{
    /**
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereStatus(Status::STATUS_PUBLISHED->value);
    }
}
