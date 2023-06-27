<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 12/30/2016
 * Time: 11:26 PM
 */

namespace Ensue\Snap\Foundation;

use Ensue\Snap\Foundation\Interfaces\HasOption;

enum Status : int implements HasOption
{
    /**
     * The suspended status
     */
    case STATUS_SUSPENDED = 0;

    /**
     * The unpublished status
     */
    case STATUS_UNPUBLISHED = 1;

    /**
     * The published status
     */
    case STATUS_PUBLISHED = 2;

    /**
     * @return Status[]
     */
    public static function options(): array
    {
        return [
            self::STATUS_PUBLISHED->value,
            self::STATUS_UNPUBLISHED->value,
        ];
    }
}
