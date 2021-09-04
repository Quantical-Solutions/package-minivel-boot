<?php

use Minivel\Igniter\Workers\Route;
use Minivel\Igniter\Workers;
use Carbon\Carbon;
use Jenssegers\Blade\Blade;
use Minivel\Igniter\Candela\Config as Config;
use Minivel\Igniter\Workers\ErrorsHandler;
use Minivel\Igniter\Workers\ExceptionsHandler;
use Minivel\Igniter\Workers\SQLHandler;
use Minivel\Igniter\Solutions\Solutions;
use Minivel\Igniter\Workers\SwiftMailerCollector as Mail;
use Minivel\Igniter\ErrorDocument\ErrorsPage;
use Minivel\Auth\Matrix\Deploy;
use Minivel\Auth\Matrix\Auth;

if (!function_exists('env')) {

    function env($key, $value = false)
    {
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return $_ENV[$key];
        } else if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
            return $_SERVER[$key];
        } else {
            return $value;
        }
    }
}

if (!function_exists('config')) {

    function config($str)
    {
        $globals = ROOTDIR . '/config/';

        if (count(explode('.', $str)) == 2) {

            $component = strtolower(explode('.', $str)[0]);
            $index = strtolower(explode('.', $str)[1]);

            $files = scandir($globals);

            foreach ($files as $file) {

                $var = strtolower(str_replace('.php', '', $file));

                if ($component == $var) {

                    $content = require($globals . $file);
                    return $content[$index];
                }
            }

        } else {

            trigger_error('Wrong string parameter format in config(). Argument : "' . $str . '" is not valid');
        }
    }
}

if (!function_exists('views')) {
    function views($view, $data = false)
    {
        Config::views($view, $data);
    }
}

if (!function_exists('session')) {
    function session($key = false)
    {
        $return = [];
        if ($key == false) {
            $return = $_SESSION;
        } else {
            if (isset($_SESSION[$key])) {
                $return = $_SESSION[$key];
            } else {
                $return = false;
            }
        }
        return $return;
    }
}

if (!function_exists('redirect')) {

    function redirect($url)
    {
        header('Location: ' . $url);
        exit();
    }
}

if (!function_exists('route')) {

    function route($name)
    {
        $routes = Route::all();
        foreach ($routes as $route) {
            if ($route->name === $name) {
                echo $route->uri;
                return;
            }
        }
        trigger_error('La route "' . $name . '" n\'est pas définie.');
    }
}

if (!function_exists('request')) {

    function request()
    {
        return Request::helper();
    }
}

/**
 * csrf_token function
 *
 * Return CSRF Token
 */
if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        return $_SESSION['_token'];
    }
}

if (!function_exists('handleErrors')) {

    function handleErrors($code)
    {
        http_response_code($code);
        $error = new ErrorsPage;
        $error->ignite($code);
    }
}

if (!function_exists('bcrypt')) {

    function bcrypt($str)
    {
        $options = ['cost' => 12];
        return password_hash($str, PASSWORD_BCRYPT, $options);
    }
}

if (!function_exists('is_ajax')) {
    function is_ajax()
    {
        return Config::is_ajax();
    }
}

if (!function_exists('addSolution')) {
    function addSolution($message, $description)
    {
        Solutions::addSolution($message, $description);
    }
}

/**
 * wormAddMessage function
 *
 * References messages to the Wormhole
 */
if (!function_exists('wormAddMessage')) {
    function wormAddMessage($data, $level = 0)
    {
        Config::addMessage($data, $level);
    }
}

/**
 * wormAddModel function
 *
 * References models to the Wormhole
 */
if (!function_exists('wormAddModel')) {
    function wormAddModel($data)
    {
        Config::addModel($data);
    }
}

/**
 * wormAddQueries function
 *
 * References queries to the Wormhole
 */
if (!function_exists('wormAddQueries')) {
    function wormAddQueries($queries, $traces)
    {
        Config::addQueries($queries, $traces);
    }
}

/**
 * wormAddMail function
 *
 * References mails to the Wormhole
 */
if (!function_exists('wormAddMail')) {
    function wormAddMail($array)
    {
        Config::addMails($array);
    }
}

/**
 * wormAddGates function
 *
 * References gates to the Wormhole
 */
if (!function_exists('wormAddGates')) {
    function wormAddGates($array)
    {
        Config::addGates($array);
    }
}

/**
 * wormCollect function
 *
 * Collect reported data for the Wormhole debugBar
 */
if (!function_exists('wormCollect')) {
    function wormCollect()
    {
        return Config::collect();
    }
}

/**
 * sendMail function
 *
 * Send mail using SwiftMailer Package
 */
if (!function_exists('sendMail')) {
    function sendMail($data)
    {
        Mail::sendMail($data);
    }
}

/**
 * symlinker function
 *
 * Add a symlink to the link list
 */
if (!function_exists('symlinker')) {
    function symlinker()
    {
        Config::symlinker();
    }
}

/**
 * unlinkSymlinker function
 *
 * Delete a link from the symlink list
 */
if (!function_exists('unlinkSymlinker')) {
    function unlinkSymlinker($link)
    {
        //Config::unlinkSymlinker('symlink_you_want_to_delete')
        Config::unlinkSymlinker($link);
    }
}

/**
 * chrono function
 *
 * Get time stamp in seconds
 */
if (!function_exists('chrono')) {
    function chrono()
    {
        return explode(' ', microtime())[0] . ' s';
    }
}

/**
 * tracer function
 *
 * Return file from a given index in debug_backtrace() array
 */
if (!function_exists('tracer')) {
    function tracer($index)
    {
        $debug = debug_backtrace();
        return $debug[$index]['file'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field()
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

/**
 * exception_handler function
 *
 * Exceptions callback
 */
if (!function_exists('exception_handler')) {
    function exception_handler($exception)
    {
        if (config('app.debug') === true) {
            ob_clean();
            ExceptionsHandler::HTMLBuilder($exception);
            exit();
        } else {
            handleErrors(500);
        }
    }
}

/**
 * error_handler function
 *
 * Errors callback
 */
if (!function_exists('error_handler')) {
    function error_handler($severity, $message, $file, $line)
    {
        if (config('app.debug') === true) {
            ob_clean();
            ErrorsHandler::HTMLBuilder($severity, $message, $file, $line);
            exit();
        } else {
            handleErrors(500);
        }
    }
}

/**
 * sql_handler function
 *
 * SQL Exceptions callback
 */
if (!function_exists('sql_handler')) {
    function sql_handler($error)
    {
        if (config('app.debug') === true) {
            ob_clean();
            SQLHandler::HTMLBuilder($error);
            exit();
        } else {
            handleErrors(500);
        }
    }
}

/**
 * humanizeSize function
 *
 * Translate octets to a human scale
 */
if (!function_exists('humanizeSize')) {
    function humanizeSize($space)
    {
        return Config::humanizeSize($space);
    }
}

/**
 * Translate function
 *
 * Translate languages from locale if json file exist in resources/lang
 */
if (!function_exists('__')) {

    function __($str) {

        $lang = config('app.locale');
        $path = ROOTDIR . '/resources/lang';

        if (file_exists($path . '/' . $lang . '.json')) {

            $file = file_get_contents($path . '/' . $lang . '.json');
            $json = json_decode($file, true);
            if (isset($json[$str])) {
                return $json[$str];
            } else {
                return $str;
            }
        }

        return $str;
    }
}

/**
 * Translate function for frontend JS
 *
 * Deliver translations from locale if translation json file exists
 */
if (!function_exists('translateJS')) {

    function translateJS()
    {
        $lang = config('app.locale');
        $path = ROOTDIR . '/resources/lang';

        if (file_exists($path . '/' . $lang . '.json')) {

            $file = file_get_contents($path . '/' . $lang . '.json');
            return json_decode($file, true);
        }

        return [];
    }
}

/**
 * storage_path function
 *
 * Define the storage path
 */
if (!function_exists('storage_path')) {
    function storage_path($data)
    {
        return Config::storage_path($data);
    }
}

$newSessionName = str_replace('-', '_', str_replace(' ', '_', strtolower(config('app.name'))) . '_session');
if (session_name() != $newSessionName) { session_name($newSessionName); }
session_start();

if (class_exists(Deploy::class)) {
    new Deploy;
}

if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
    symlinker();
    set_exception_handler('exception_handler');
    set_error_handler('error_handler');
}
