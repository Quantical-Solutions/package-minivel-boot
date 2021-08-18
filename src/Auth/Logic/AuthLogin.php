<?php

namespace Minivel\Auth\Logic;

use Minivel\Auth\Matrix\Auth;
use Minivel\Auth\Matrix\FormErrors as Errors;

class AuthLogin
{
    private string $viewLoginForm;
    private string $viewLogin;
    private string $viewLogout;
    private object $errors;

    public function __construct()
    {
        $this->errors = new Errors;
        $this->viewLoginForm = (config('chosen.constellations'))['login_get']['view'];
        $this->viewLogin = (config('chosen.constellations'))['login_post']['view'];
        $this->viewLogout = (config('chosen.constellations'))['logout']['view'];
    }

    public function showLoginForm()
    {
        views($this->viewLoginForm, ['name' => config('app.name'), 'stt' => 'formLogin', 'errors' => $this->errors]);
    }

    public function login()
    {
        echo 'post';
        views($this->viewLogin, ['name' => config('app.name'), 'stt' => 'login', 'errors' => $this->errors]);
    }

    public function logout()
    {
        views($this->viewLogout, ['name' => config('app.name'), 'stt' => 'logout', 'errors' => $this->errors]);
    }

    private function rules()
    {
        $rules = [
            'login_email' => 'required|email',
            'login_password' => 'required|min:8|alphaNum'
        ];


    }
}