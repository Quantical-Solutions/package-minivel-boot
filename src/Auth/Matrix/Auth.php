<?php

namespace Minivel\Auth\Matrix;

use Minivel\Igniter\Workers\Route;

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
            if (strpos($key, 'minivel_log_') !== false) {
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

                    if (strpos($key, 'minivel_log_') !== false) {
                        $check = true;
                        break;
                    }
                }

                if ($check == false) {

                    $val = '';
                    for($i = 0; $i < 100; $i++) {
                        $val .= chr( rand( 65, 90 ) );
                    }
                    $hexdec = 'minivel_log_' . sha1($val);
                    self::$logger = $hexdec;
                    $_SESSION[$hexdec] = self::$id;
                }

            } else {
                trigger_error('Minivel::Auth::set($id) has not been set correctly');
            }
        }
    }

    public static function id()
    {
        if (self::$id > 0) {
            return self::$id;
        } else {
            trigger_error('Minivel::Auth user is not defined');
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
            if (strpos($key, 'minivel_log_') !== false) {
                $log = $key;
                $check = true;
                break;
            }
        }

        if ($check == true) {
            unset($_SESSION[$log]);
        }
    }

    public static function addRoutes()
    {
        $constellations = [];
        $chosenConfigFile = (file_exists(ROOTDIR . '/config/chosen.php'))
            ?  require ROOTDIR . '/config/chosen.php'
            : require ROOTDIR . '/vendor/minivel/boot/src/Auth/Clones/chosen.php';
        foreach ($chosenConfigFile['constellations'] as $key => $data) {
            $route = $data['data'];
            if ($route['request'] === 'get') {
                Route::get(
                    $route['uri'],
                    $chosenConfigFile['namespace'] . $route['controller'] . '@' . $route['method']
                )->name($route['as']);
            } else if ($data['data']['request'] === 'post') {
                Route::post(
                    $route['uri'],
                    $chosenConfigFile['namespace'] . $route['controller'] . '@' . $route['method']
                )->name($route['as']);
            } else if ($data['data']['request'] === 'patch') {
                Route::patch(
                    $route['uri'],
                    $chosenConfigFile['namespace'] . $route['controller'] . '@' . $route['method']
                )->name($route['as']);
            } else if ($data['data']['request'] === 'put') {
                Route::put(
                    $route['uri'],
                    $chosenConfigFile['namespace'] . $route['controller'] . '@' . $route['method']
                )->name($route['as']);
            } else if ($data['data']['request'] === 'delete') {
                Route::delete(
                    $route['uri'],
                    $chosenConfigFile['namespace'] . $route['controller'] . '@' . $route['method']
                )->name($route['as']);
            }

            if (isset($route['where']) && !empty($route['where'])) {
                Route::where($route['where']);
            }
        }
    }
}