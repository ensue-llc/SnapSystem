<?php

namespace Ensue\NicoSystem\Exceptions;

use Ensue\NicoSystem\Constants\AppConstants;

/**
 * Class NicoAuthenticationException
 * @package Ensue\NicoSystem\Exceptions
 */
class NicoAuthenticationException extends NicoException
{
    protected $code = 401;

    protected string $respCode = AppConstants::ERR_AUTHENTICATION_ERROR;
}
