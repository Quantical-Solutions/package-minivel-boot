<?php

namespace Minivel\Auth\Matrix;

use FormErrors as Errors;

class Validation
{
    private static array $messages;

    public static function make($request, $messages): Validation
    {
        self::$messages = $messages;
        return new self();
    }

    public static function fails(): bool
    {
        return false;
    }
}