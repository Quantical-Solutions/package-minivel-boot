@extends('chosen-template')

@section('title')
    Confirm
@endsection

@section('content')
    <div class="chosen-container">
        <div class="chosen-container-form">
            <div class="chosen-container-header">
                <div class="chosen-container-withIcon">
                    <i class="quantic-icon-ftp_codes"></i>
                    <p>Confirm password</p>
                </div>
            </div>
            <div class="chosen-container-body">
                <form id="confirmForm" method="POST" action="{{ route('password.confirmed') }}">
                    @csrf
                    <div class="chosen-form-container">
                        <p>Please confirm your password before continuing.</p>
                    </div>
                    <div class="chosen-form-container">
                        <div class="chosen-form-container">
                            <div class="chosen-container-withIcon">
                                <i class="quantic-icon-key"></i>
                                <label for="confirm_password">Password</label>
                                @if($errors->has('confirm_password'))
                                    <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('confirm_password') }}
                                </span>
                                @endif
                            </div>
                            <input class="{{ $errors->has('confirm_password') ? ' is-invalid' : '' }}"
                                   type="password" id="confirm_password" name="confirm_password" autocomplete="off"
                                   data-pattern="{{ config('chosen.passwords')['users']['pattern'] }}">
                            <i onclick="displayPassword(this)" class="password-displayer quantic-icon-locked1"></i>
                        </div>
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
                <a href="{{ route('password.request') }}">Forgot password ?</a>
            </div>
        </div>
    </div>
@endsection