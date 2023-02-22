<?php
/**
 * Created by PhpStorm.
 * User: amar
 * Date: 10/29/18
 * Time: 2:01 PM
 */

namespace Ensue\Snap\Exceptions;

use Ensue\Snap\Constants\SnapConstant;

/**
 * Class ResourceInUseException
 * @package Ensue\Snap\Exceptions
 */
class SnapResourceInUseException extends SnapException
{
    protected $code = 428;

    protected string $respCode = SnapConstant::ERR_RESOURCE_IN_USE;

    protected $message = "Resource in use";
}
