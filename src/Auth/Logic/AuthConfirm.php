<?php

namespace Minivel\Auth\Logic;

use Carbon\Carbon;
use Jenssegers\Blade\Blade;
use Minivel\Auth\Matrix\Auth;
use Minivel\Auth\Matrix\Email;
use Minivel\Auth\Matrix\FormErrors as Errors;

class AuthConfirm
{
    private string $viewConfirmForm;
    private string $viewConfirm;
    private object $errors;

    public function __construct()
    {
        $this->errors = new Errors;
        $this->viewConfirmForm = (config('chosen.constellations'))['confirm_get']['view'];
        $this->viewConfirm = (config('chosen.constellations'))['confirm_post']['view'];
    }

    public function showConfirmForm()
    {
        views($this->viewConfirmForm, ['name' => config('app.name'), 'stt' => 'formConfirm', 'errors' => $this->errors]);
    }

    public function confirm()
    {
        views($this->viewConfirm, ['name' => config('app.name'), 'stt' => 'reset', 'errors' => $this->errors]);
    }
}