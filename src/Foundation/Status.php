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
    use HasEnumOptions;

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
}
