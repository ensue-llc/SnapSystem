<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 10/18/2017
 * Time: 4:46 PM
 */

namespace NicoSystem\Exceptions;

/**
 * Class NicoAuthenticationException
 * @package NicoSystem\Exceptions
 */
class NicoAuthenticationException extends NicoException
{
    protected $code = 401;

    protected string $respCode = 'err_authentication_error';
}
