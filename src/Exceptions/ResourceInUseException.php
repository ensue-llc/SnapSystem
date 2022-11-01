<?php
/**
 * Created by PhpStorm.
 * User: amar
 * Date: 10/29/18
 * Time: 2:01 PM
 */

namespace Ensue\NicoSystem\Exceptions;

use App\System\AppConstants;

/**
 * Class ResourceInUseException
 * @package Ensue\NicoSystem\Exceptions
 */
class ResourceInUseException extends NicoException
{
    protected $code = 428;

    protected string $respCode = AppConstants::ERR_RESOURCE_IN_USE;

    protected $message = "Resource in use";
}
