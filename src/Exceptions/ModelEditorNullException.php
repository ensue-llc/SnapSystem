<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/22/2017
 * Time: 5:18 PM
 */

namespace App\NicoSystem\src\Exceptions;


use NicoSystem\Exceptions\NicoException;

/**
 * Class ModelEditorNullException
 * @package App\NicoSystem\src\Exceptions
 */
class ModelEditorNullException extends NicoException
{
    protected $message = "Editor model not found.";
}
