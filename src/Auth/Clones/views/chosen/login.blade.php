@extends('chosen-template')

@section('title')
    Login
@endsection

@section('content')
    <div class="chosen-container">
        <div class="chosen-container-form">
            <div class="chosen-container-header">
                <div class="chosen-container-withIcon">
                    <i class="quantic-icon-profile1"></i>
                    <p>Login</p>
                </div>
            </div>
            <div class="chosen-container-body">
                <form id="loginForm" method="POST" action="{{ constellation('login') }}">
                    @csrf
                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-envelope"></i>
                            <label for="login_email">Email</label>
                            @if($errors->has('login_email'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('login_email') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('login_email') ? ' is-invalid' : '' }}" type="email" id="login_email" name="login_email">
                    </div>
                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-key"></i>
                            <label for="login_password">Password</label>
                            @if($errors->has('login_password'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('login_password') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('login_password') ? ' is-invalid' : '' }}" type="password" id="login_password" name="login_password">
                        <i onclick="displayPassword(this)" class="password-displayer quantic-icon-locked1"></i>
                    </div>
                    <div class="chosen-form-container-buttons">
                        <button disabled class="chosen-container-withIcon" type="submit" id="login_submit_btn">
                            <i class="quantic-icon-rocket"></i>
                            <span>Submit</span>
                        </button>
                        <button class="chosen-container-withIcon" type="reset" id="login_reset_btn">
                            <i class="quantic-icon-cancel"></i>
                            <span>Reset</span>
                        </button>
                    </div>
                    <div class="chosen-form-container-remember">
                        <input type="checkbox" id="login_remember">
                        <p class="chosen-remember" onclick="this.previousElementSibling.click()">Remember me</p>
                    </div>
                </form>
            </div>
            <div class="chosen-container-footer">
                <a href="/">Back to home</a>
                <a href="{{ constellation('password/forgot') }}">Forgot password</a>
            </div>
        </div>
    </div>
@endsection