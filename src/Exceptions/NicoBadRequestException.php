<?php

namespace Ensue\NicoSystem\Exceptions;

use Ensue\NicoSystem\Constants\AppConstants;

/**
 * Class NicoBadRequestException
 * @package Ensue\NicoSystem\Exceptions
 */
class NicoBadRequestException extends NicoException
{
    protected $code = 400;

    protected string $respCode = AppConstants::ERR_BAD_REQUEST;
}
