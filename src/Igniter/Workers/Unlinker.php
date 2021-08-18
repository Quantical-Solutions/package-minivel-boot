<?php

namespace Minivel\Igniter\Workers;

class Unlinker
{
    public static function KillFiles()
    {
        if (file_exists(ROOTDIR . '/public/assets/styles.js')) {
            unlink(ROOTDIR . '/public/assets/styles.js');
        }
    }
}