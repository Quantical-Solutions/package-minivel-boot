<?php

namespace Minivel\Igniter\Workers;

use App\Http\Kernel;

class Route
{
    public static $all = [];
    private static $middlewares = null;

    public static function get($uri, $data): Route
    {
        $url = self::cleanURI($uri);
        $split = explode('@', $data);
        self::$all[] = (object) ['method' => 'GET', 'uri' => $url['uri'], 'controller' => ($split[0] ?? null), 'function' =>
            ($split[1] ?? null), 'name' => null, 'segments' => $url['segments'], 'required' => self::minSegment($url['uri']), 'args' => $url['args'], 'where' => null, 'middlewares' => self::$middlewares];
        self::$middlewares = null;
        return new self();
    }

    public static function post($uri, $data): Route
    {
        $url = self::cleanURI($uri);
        $split = explode('@', $data);
        self::$all[] = (object) ['method' => 'POST', 'uri' => $url['uri'], 'controller' => ($split[0] ?? null), 'function' =>
            ($split[1] ?? null), 'name' => null, 'segments' => $url['segments'], 'required' => self::minSegment($url['uri']), 'args' => $url['args'], 'where' => null, 'middlewares' => self::$middlewares];
        self::$middlewares = null;
        return new self();
    }

    public static function put($uri, $data): Route
    {
        $url = self::cleanURI($uri);
        $split = explode('@', $data);
        self::$all[] = (object) ['method' => 'PUT', 'uri' => $url['uri'], 'controller' => ($split[0] ?? null), 'function' =>
            ($split[1] ?? null), 'name' => null, 'segments' => $url['segments'], 'required' => self::minSegment($url['uri']), 'args' => $url['args'], 'where' => null, 'middlewares' => self::$middlewares];
        self::$middlewares = null;
        return new self();
    }

    public static function patch($uri, $data): Route
    {
        $url = self::cleanURI($uri);
        $split = explode('@', $data);
        self::$all[] = (object) ['method' => 'PATCH', 'uri' => $url['uri'], 'controller' => ($split[0] ?? null), 'function' =>
            ($split[1] ?? null), 'name' => null, 'segments' => $url['segments'], 'required' => self::minSegment($url['uri']), 'args' => $url['args'], 'where' => null, 'middlewares' => self::$middlewares];
        self::$middlewares = null;
        return new self();
    }

    public static function delete($uri, $data): Route
    {
        $url = self::cleanURI($uri);
        $split = explode('@', $data);
        self::$all[] = (object) ['method' => 'DELETE', 'uri' => $url['uri'], 'controller' => ($split[0] ?? null), 'function' =>
            ($split[1] ?? null), 'name' => null, 'segments' => $url['segments'], 'required' => self::minSegment($url['uri']), 'args' => $url['args'], 'where' => null, 'middlewares' => self::$middlewares];
        self::$middlewares = null;
        return new self();
    }

    private static function cleanURI($uri): array
    {
        $uri = trim($uri);
        $uri = (strlen($uri) === 0 || $uri[0] !== '/') ? '/' . $uri : $uri;
        $dynamics = [];
        $segments = explode('/', $uri);
        $count = count($segments) - 1;

        foreach ($segments as $key => $segment) {

            if ($segment !== '') {

                if ($segment[0] === '{' && $segment[strlen($segment) - 1] === '}') {
                    if ($segment[strlen($segment) - 2] === '?') {
                        $dynamics[] = ['name' => substr($segment, 1, -2), 'optional' => true];
                    } else {
                        $dynamics[] = ['name' => substr($segment, 1, -1), 'optional' => false];
                    }
                }
            }
        }
        return ['uri' => $uri, 'segments' => $count, 'args' => $dynamics];
    }

    private static function minSegment($segments)
    {
        $split = explode('/', $segments);
        array_shift($split);
        $final = [];
        foreach ($split as $item) {
            $chunk = array_pop($split);
            if (!str_contains($chunk, '?}')) {
                array_unshift($final, $chunk);
            }
        }
        return $final;
    }

    public static function name($name): Route
    {
        $index = count(self::$all) - 1;
        self::$all[$index]->name = trim($name);
        return new self();
    }

    public function where($data): Route
    {
        $index = count(self::$all) - 1;
        self::$all[$index]->where = $data;
        return new self();
    }

    public static function middleware($array): Route
    {
        $refs = [];
        foreach ($array as $item) {
            $mid = Kernel::append_middlewares($item);
            $exec = new $mid;
            $exec->rules();
            $refs[$item] = $mid;
        }

        self::$middlewares = $refs;
        return new self();
    }

    public static function all(): array
    {
        return self::$all;
    }
}