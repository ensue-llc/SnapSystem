<?php

namespace Ensue\Snap\Exceptions;

use Ensue\Snap\Constants\SnapConstant;

/**
 * Class NicoAuthenticationException
 * @package Ensue\Snap\Exceptions
 */
class SnapAuthenticationException extends SnapException
{
    protected $code = 401;

    protected string $respCode = SnapConstant::ERR_AUTHENTICATION_ERROR;
}
