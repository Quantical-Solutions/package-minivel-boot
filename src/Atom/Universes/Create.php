<?php

namespace Minivel\Atom\Universes;

use Minivel\Atom\Console;
use Wujunze\Colors;

class Create
{
    public static function Controller($name)
    {
        $colors = new \Wujunze\Colors();

        $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString('>>> test',
            'red', null);
        Console::$terminate = $result;
    }

    public static function Model($name)
    {
        $colors = new \Wujunze\Colors();

        $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString('>>> test',
            'red', null);
        Console::$terminate = $result;
    }

    public static function Migration($name)
    {
        $colors = new \Wujunze\Colors();

        $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString('>>> test',
            'red', null);
        Console::$terminate = $result;
    }
}