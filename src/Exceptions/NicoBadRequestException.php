<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 10/18/2017
 * Time: 4:48 PM
 */

namespace Ensue\NicoSystem\Exceptions;


/**
 * Class NicoBadRequestException
 * @package Ensue\NicoSystem\Exceptions
 */
class NicoBadRequestException extends NicoException
{
    protected $code = 400;

    protected string $respCode = "err_bad_request";
}
