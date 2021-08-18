<?php

namespace Minivel\Auth\Matrix;

class Auth
{
    public static array $user = [];
    private static int $id = 0;
    private static string $class = '';
    public static string $logger = '';

    public static function maintain()
    {
        $id = 0;
        foreach ($_SESSION as $key => $value) {
            if (strpos($key, 'quantic_log_') !== false) {
                $id = $value;
                self::$logger = $key;
                break;
            }
        }
        if ($id > 0) {
            self::set($id);
        }
    }

    public static function set($id)
    {
        $provider = config('chosen.providers');
        self::$class = $provider['users']['model'];

        if (is_int($id)) {

            self::$id = $id;

            if (self::$id > 0) {

                $model = self::$class;
                self::$user = $model::find($id)->toArray();
                $check = false;

                foreach ($_SESSION as $key => $value) {

                    if (strpos($key, 'quantic_log_') !== false) {
                        $check = true;
                        break;
                    }
                }

                if ($check == false) {

                    $val = '';
                    for($i = 0; $i < 100; $i++) {
                        $val .= chr( rand( 65, 90 ) );
                    }
                    $hexdec = 'quantic_log_' . sha1($val);
                    self::$logger = $hexdec;
                    $_SESSION[$hexdec] = self::$id;
                }

            } else {
                trigger_error('Chosen::Auth::set($id) has not been set correctly');
            }
        }
    }

    public static function id()
    {
        if (self::$id > 0) {
            return self::$id;
        } else {
            trigger_error('Chosen::Auth user is not defined');
        }
    }

    public static function isAdmin()
    {
        if (!empty(self::$user)) {
            return true;
        }
        return false;
    }

    public static function user()
    {
        return self::$user;
    }

    public static function reset()
    {
        $check = false;
        $log = '';

        foreach ($_SESSION as $key => $value) {
            if (strpos($key, 'quantic_log_') !== false) {
                $log = $key;
                $check = true;
                break;
            }
        }

        if ($check == true) {
            unset($_SESSION[$log]);
        }
    }

    public static function addLinks()
    {
        $constellations = [];
        $chosenConfigFile = (file_exists(ROOTDIR . '')) ? config('chosen.constellations') : [];
        foreach ($chosenConfigFile as $key => $data) {
            $constellations[$key] = $data['data'];
        }
        return $constellations;
    }
}