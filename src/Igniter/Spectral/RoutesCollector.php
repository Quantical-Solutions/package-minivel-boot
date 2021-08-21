<?php

namespace Minivel\Igniter\Spectral;

use Minivel\Igniter\Workers\Route;

class RoutesCollector
{
    public function routeParser()
    {
        return Route::all();
    }
}