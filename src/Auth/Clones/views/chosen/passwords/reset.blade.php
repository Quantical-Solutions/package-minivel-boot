@extends('chosen-template')

@section('title')
    Reset
@endsection

@section('content')
    <div class="chosen-container">
        <div class="chosen-container-form">
            <div class="chosen-container-header">
                <div class="chosen-container-withIcon">
                    <i class="quantic-icon-streetlight"></i>
                    <p>Reset password</p>
                </div>
            </div>
            <div class="chosen-container-body">
                <form id="resetForm" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="reset_token" value="{{ $token }}">
                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-key"></i>
                            <label for="reset_password">Actual password</label>
                            @if($errors->has('reset_password'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('reset_password') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('reset_password') ? ' is-invalid' : '' }}" type="password" id="reset_password" name="reset_password">
                        <i onclick="displayPassword(this)" class="password-displayer quantic-icon-locked1"></i>
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-key"></i>
                            <label for="reset_new_password">New password</label>
                            @if($errors->has('reset_new_password'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('reset_new_password') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('reset_new_password') ? ' is-invalid' : '' }}" type="password" id="reset_new_password" name="reset_new_password">
                        <i onclick="displayPassword(this)" class="password-displayer quantic-icon-locked1"></i>
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-key"></i>
                            <label for="reset_confirm_password">Confirm new password</label>
                            @if($errors->has('reset_confirm_password'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('reset_confirm_password') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('reset_confirm_password') ? ' is-invalid' : '' }}" type="password" id="reset_confirm_password" name="reset_confirm_password">
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
                </form>
            </div>
            <div class="chosen-container-footer">
                <a href="#">Back to home</a>
            </div>
        </div>
    </div>
@endsection