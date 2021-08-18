<?php

namespace Minivel\Auth\Logic;

use Carbon\Carbon;
use Jenssegers\Blade\Blade;
use Minivel\Auth\Matrix\Auth;
use Minivel\Auth\Matrix\Email;
use Minivel\Auth\Matrix\FormErrors as Errors;

class AuthForgot
{
    private string $viewRequestForm;
    private string $viewResetLink;
    private object $errors;

    public function __construct()
    {
        $this->errors = new Errors;
        $this->viewRequestForm = (config('chosen.constellations'))['forgot_get']['view'];
        $this->viewResetLink = (config('chosen.constellations'))['forgot_post']['view'];
    }

    public function showLinkRequestForm()
    {
        views($this->viewRequestForm, ['name' => config('app.name'), 'stt' => 'formRequest', 'errors' => $this->errors]);
    }

    public function sendResetLinkEmail()
    {
        views($this->viewResetLink, ['name' => config('app.name'), 'stt' => 'resetLink', 'errors' => $this->errors]);
    }
}