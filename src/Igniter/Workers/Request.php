<?php

namespace Minivel\Igniter\Workers;

class Request
{
    private static object $params;

    public static function requestHandler()
    {
        $actual_link = "://" . $_SERVER['HTTP_HOST'];

        if (str_contains(config('app.url'), $actual_link)) {

            $locate = dirname(__DIR__, 2);
            $folder = '/' .  explode('/', $locate)[count(explode('/', $locate))-1] . '/public';
            $url = str_replace($folder, '', $_SERVER['REQUEST_URI']);
            $url = (str_contains($url, '?')) ? explode('?', $url)[0] : $url;
            $url = (str_contains($url, '#')) ? explode('#', $url)[0] : $url;
            $uris = explode('/', $url);

            self::$params = (object)[
                'method' => $_SERVER['REQUEST_METHOD'],
                'full' => $actual_link,
                'url' => $url,
                'get' => $_GET,
                'post' => $_POST,
                'segments' => count($uris) - 1,
                'inputs' => self::defineInputs()
            ];

        } else {

            trigger_error('La variable d\'environnement "APP_URL" ne correspond pas.');
            exit(1);
        }
    }

    public static function helper(): Request
    {
        return new self();
    }

    public static function params()
    {
        return self::$params;
    }

    public static function all()
    {
        return self::$params->inputs;
    }

    public static function input($name)
    {
        if (isset(self::$params->inputs->$name)) {
            return self::$params->inputs->$name;
        }
        return null;
    }

    private static function defineInputs()
    {
        $inputs = [];
        foreach ($_GET as $key => $value) {
            $inputs[$key] = $value;
        }
        foreach ($_POST as $key => $value) {
            $inputs[$key] = $value;
        }
        return (object) $inputs;
    }
}