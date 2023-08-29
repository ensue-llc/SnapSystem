<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 12/30/2016
 * Time: 11:26 PM
 */

namespace Ensue\Snap\Foundation;

use Ensue\Snap\Foundation\Interfaces\HasOptionInterface;

enum Status : int implements HasOptionInterface
{
    /**
     * The suspended status
     */
    case Suspended = 0;

    /**
     * The unpublished status
     */
    case Unpublished = 1;

    /**
     * The published status
     */
    case Published = 2;

    /**
     * @return Status[]
     */
    public static function options(): array
    {
        return [
            self::Published->value,
            self::Unpublished->value,
        ];
    }
}
