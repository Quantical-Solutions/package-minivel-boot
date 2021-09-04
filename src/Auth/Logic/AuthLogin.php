<?php

namespace Minivel\Auth\Logic;

use Minivel\Auth\Matrix\Auth;
use Minivel\Igniter\Workers\Request;
use App\Models\User;

class AuthLogin
{
    private string $viewLoginForm;
    private string $viewLogin;
    private string $viewLogout;

    public function __construct()
    {
        $this->viewLoginForm = (config('chosen.constellations'))['login_get']['view'];
        $this->viewLogin = (config('chosen.constellations'))['login_post']['view'];
        $this->viewLogout = (config('chosen.constellations'))['logout']['view'];
    }

    public function showLoginForm()
    {
        if (Auth::isAdmin()) {
            return redirect(config('chosen.home_page'));
        }
        return views($this->viewLoginForm, ['name' => config('app.name'), 'stt' => 'formLogin']);
    }

    public function login()
    {
        $user = User::select('id', 'password')->where('email', Request::input('login_email'))->first();
        if ($user !== null && password_verify(Request::input('login_password'), $user->password)) {
            Auth::set($user->id);
            return redirect(config('chosen.home_page'));
        }
        return redirect('login');
    }

    public function logout()
    {
        Auth::reset();
        return redirect('/login');
    }

    private function rules()
    {
        $rules = [
            'login_email' => 'required|email',
            'login_password' => 'required|min:8|alphaNum'
        ];


    }
}