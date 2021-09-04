<?php

namespace Minivel\Auth\Logic;

use Carbon\Carbon;
use Jenssegers\Blade\Blade;
use Minivel\Auth\Matrix\Auth;
use Minivel\Auth\Matrix\Email;

class AuthForgot
{
    private string $viewRequestForm;
    private string $viewResetLink;

    public function __construct()
    {
        $this->viewRequestForm = (config('chosen.constellations'))['forgot_get']['view'];
        $this->viewResetLink = (config('chosen.constellations'))['forgot_post']['view'];
    }

    public function showLinkRequestForm()
    {
        if (Auth::isAdmin()) {
            return redirect(config('chosen.home_page'));
        }
        views($this->viewRequestForm, ['name' => config('app.name'), 'stt' => 'formRequest']);
    }

    public function sendResetLinkEmail()
    {
        views($this->viewResetLink, ['name' => config('app.name'), 'stt' => 'resetLink']);
    }
}