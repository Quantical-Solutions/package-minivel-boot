<?php

namespace Minivel\Auth\Matrix;

class FormErrors
{
    private static object $errors;

    public static function get(): FormErrors
    {
        self::$errors = (object) [];
        return new self();
    }
    public static function has($name): bool
    {
        $errors = self::$errors;
        foreach ($errors as $key => $error) {
            if ($name === $key) {
                return true;
            }
        }
        return false;
    }

    public static function first($name): ?string
    {
        $errors = self::$errors;
        if (isset($errors[$name])) {
            return $errors[$name];
        }
        return null;
    }

    public static function all(): object
    {
        return self::$errors;
    }
}