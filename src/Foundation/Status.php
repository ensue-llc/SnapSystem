<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 12/30/2016
 * Time: 11:26 PM
 */

namespace Ensue\Snap\Foundation;

class Status
{
    /**
     * The suspended status
     */
    public const STATUS_SUSPENDED = 0;

    /**
     * The unpublished status
     */
    public const STATUS_UNPUBLISHED = 1;

    /**
     * The published status
     */
    public const STATUS_PUBLISHED = 2;

    /**
     * @param bool $withEmpty defaults to false. If true an empty option is added at the top
     * @param string $emptyString The label for empty value
     * @return array
     */
    public static function getList(bool $withEmpty = false, string $emptyString = 'Unspecified'): array
    {
        $items = [
            self::STATUS_UNPUBLISHED => 'Unpublished',
            self::STATUS_PUBLISHED => 'Published',
        ];
        if ($withEmpty) {
            $items = array_merge(['' => $emptyString], $items);
        }

        return $items;
    }

    /**
     * @return int[]
     */
    public static function options(): array
    {
        return [
            static::STATUS_PUBLISHED,
            static::STATUS_UNPUBLISHED,
        ];
    }
}
