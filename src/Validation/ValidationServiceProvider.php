<?php

namespace Ensue\NicoSystem\Validation;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    protected bool $defer = true;

    public function boot(): void
    {
        Validator::resolver(static function($translator, $data, $rules, $messages){
            return new NicoValidation($translator,$data,$rules,$messages);
        });
    }

}
