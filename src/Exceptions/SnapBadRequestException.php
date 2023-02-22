<?php

namespace Ensue\Snap\Exceptions;

use Ensue\Snap\Constants\SnapConstant;

/**
 * Class NicoBadRequestException
 * @package Ensue\Snap\Exceptions
 */
class SnapBadRequestException extends SnapException
{
    protected $code = 400;

    protected string $respCode = SnapConstant::ERR_BAD_REQUEST;
}
