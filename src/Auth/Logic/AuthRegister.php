<?php

namespace Minivel\Auth\Logic;

use Carbon\Carbon;
use Jenssegers\Blade\Blade;
use Minivel\Auth\Matrix\Auth;
use Minivel\Auth\Matrix\Email;

class AuthRegister
{
    private string $viewRegisterForm;
    private string $viewRegister;

    public function __construct()
    {
        $this->viewRegisterForm = (config('chosen.constellations'))['register_get']['view'];
        $this->viewRegister = (config('chosen.constellations'))['register_post']['view'];
    }

    public function showRegistrationForm()
    {
        views($this->viewRegisterForm, ['name' => config('app.name'), 'stt' => 'formRegister']);
    }

    public function register()
    {
        views($this->viewRegister, ['name' => config('app.name'), 'stt' => 'register']);
    }
}