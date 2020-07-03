<?php

namespace App\Service;

class StatusValueChecker
{
    const ACTIVE = 1;
    const DELETED = 2;

    protected static $values = [
        self::ACTIVE,
        self::DELETED,
    ];

    public static function isValidValue($value): bool
    {
        if (in_array($value, static::$values)) {
            return true;
        }

        return false;
    }
}