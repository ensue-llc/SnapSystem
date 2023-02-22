<?php

namespace Ensue\Snap;

use Ensue\Snap\Validation\SnapValidation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    protected bool $defer = true;

    public function boot(): void
    {
        Validator::resolver(static function ($translator, $data, $rules, $messages) {
            return new SnapValidation($translator, $data, $rules, $messages);
        });
    }

}
