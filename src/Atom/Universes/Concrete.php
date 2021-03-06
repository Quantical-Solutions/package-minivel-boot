<?php

namespace Minivel\Atom\Universes;

use Minivel\Atom\Console;
use Wujunze\Colors;

class Concrete
{
    public static function Build($name)
    {
        $colors = new \Wujunze\Colors();

        $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString('>>> test',
            'red', null);
        Console::$terminate = $result;
    }

    public static function Update($name)
    {
        $colors = new \Wujunze\Colors();

        $result = (true) ? $colors->getColoredString('>>> test', 'light_green', null) : $colors->getColoredString('>>> test',
            'red', null);
        Console::$terminate = $result;
    }
}