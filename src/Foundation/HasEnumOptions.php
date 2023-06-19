<?php

namespace Ensue\Snap\Foundation;

trait HasEnumOptions
{
    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }
}
