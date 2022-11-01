<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 1/3/2017
 * Time: 10:10 PM
 */

namespace   NicoSystem\Validation;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    protected bool $defer = true;

    public function boot()
    {
        Validator::resolver(function($translator,$data,$rules,$messages){
            return new NicoValidation($translator,$data,$rules,$messages);
        });
    }

}
