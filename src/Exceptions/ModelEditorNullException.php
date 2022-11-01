<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/22/2017
 * Time: 5:18 PM
 */

namespace App\Ensue\NicoSystem\src\Exceptions;


use Ensue\NicoSystem\Exceptions\NicoException;

/**
 * Class ModelEditorNullException
 * @package App\Ensue\NicoSystem\src\Exceptions
 */
class ModelEditorNullException extends NicoException
{
    protected $message = "Editor model not found.";
}
