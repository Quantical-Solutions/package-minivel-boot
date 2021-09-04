<?php

namespace Minivel\Auth\Logic;

use Carbon\Carbon;
use Jenssegers\Blade\Blade;
use Minivel\Auth\Matrix\Auth;
use Minivel\Auth\Matrix\Email;

class AuthConfirm
{
    private string $viewConfirmForm;
    private string $viewConfirm;

    public function __construct()
    {
        $this->viewConfirmForm = (config('chosen.constellations'))['confirm_get']['view'];
        $this->viewConfirm = (config('chosen.constellations'))['confirm_post']['view'];
    }

    public function showConfirmForm()
    {
        views($this->viewConfirmForm, ['name' => config('app.name'), 'stt' => 'formConfirm']);
    }

    public function confirm()
    {
        views($this->viewConfirm, ['name' => config('app.name'), 'stt' => 'reset']);
    }
}