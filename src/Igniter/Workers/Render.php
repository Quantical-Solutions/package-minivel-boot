<?php

namespace Minivel\Igniter\Workers;

use Minivel\Igniter\Workers\Request;

class Render
{
    public function __construct($routes)
    {
        $this->view($routes);
    }

    private function view($routes)
    {
        $request = Request::params();

        foreach ($routes as $route) {

            if (count($route->required) === $request->segments || $route->segments === $request->segments) {

                $args = $route->args;
                if (empty($args)) {

                    if ($route->uri === $request->url) {

                        $this->middlewares($route->middlewares);
                        $controller = $route->controller;
                        $method = $route->function;
                        $render = new ('App\\Http\\Controllers\\' . $controller);
                        echo $render->$method();
                        return;
                    }

                } else {

                    $final = $this->uriCleaner($route, $request);
                    if ($final !== false) {

                        $this->middlewares($route->middlewares);
                        $controller = $final->controller;
                        $method = $final->function;
                        $arguments = $this->setArguments($final->args);
                        $render = new ('App\\Http\\Controllers\\' . $controller);
                        echo call_user_func_array(array($render, $method), $arguments);
                        return;
                    }
                }
            }
        }

        handleErrors(404);
    }

    private function uriCleaner($route, $request)
    {
        $cleaner = [];
        $uris = explode('/', $route->uri);
        $cnt = $cnt2 = $cnt3 = 0;
        $uriParser = [];

        // URI cleaner
        foreach ($uris as $uri) {
            if ($cnt > 0) {
                if (str_contains($uri, '{') && str_contains($uri, '}')) {
                    $uri = str_replace('{', '', $uri);
                    $uri = str_replace('}', '', $uri);
                    $uri = str_replace('?', '', $uri);
                    $uriParser[] = false;
                } else {
                    $uriParser[] = true;
                }
                $cleaner[] = (intval($uri) != 0) ? intval($uri) : $uri;
            }
            $cnt++;
        }

        // Request
        $requestCleaner = [];
        $requestUris = explode('/', $request->url);
        foreach ($requestUris as $requestUri) {
            if ($cnt2 > 0) {
                $requestCleaner[] = (intval($requestUri) != 0) ? intval($requestUri) : $requestUri;
            }
            $cnt2++;
        }

        // URI segments control
        $check = true;
        for ($i = 0; $i < count($uriParser); $i++) {

            if ($uriParser[$i] === true) {
                if ($cleaner[$i] === $requestCleaner[$i]) {
                    $check = true;
                } else {
                    $check = false;
                    break;
                }
            }
        }

        // Add regex rules to route
        $args = $route->args;
        $where = $route->where;
        for ($i = 0; $i < count($requestCleaner); $i++) {
            if ($uriParser[$i] === false) {
                $args[$cnt3]['value'] = $requestCleaner[$i];
                if (isset($where[$args[$cnt3]['name']])) {
                    $args[$cnt3]['regex'] = $where[$args[$cnt3]['name']];
                } else {
                    $args[$cnt3]['regex'] = null;
                }
                $cnt3++;
            } else {
                $args[$cnt3]['value'] = null;
                $args[$cnt3]['regex'] = null;
            }
        }

        $route->args = $args;

        // Regex match check
        if (!empty($where)) {
            if (!$this->regex($args)) {
                return false;
            }
        }

        return ($check) ? $route : false;
    }

    private function regex($args)
    {
        $check = true;
        foreach ($args as $arg) {

            if (isset($arg['value'])) {

                $segment = $arg['value'];
                $regex = '/' . $arg['regex'] . '/';

                if ($regex !== null && preg_match($regex, $segment) === 1) {
                    $check = true;
                } else {
                    return false;
                }
            }
        }
        return $check;
    }

    private function setArguments($args)
    {
        $finalArgs = [];
        foreach ($args as $arg) {
            $value = $arg['value'];
            if ($value !== null) {
                $finalArgs[] = $value;
            }
        }
        return $finalArgs;
    }

    private function middlewares($middlewares) {

        $next = true;
        if (!empty($middlewares)) {

        }

        return $next;
    }
}