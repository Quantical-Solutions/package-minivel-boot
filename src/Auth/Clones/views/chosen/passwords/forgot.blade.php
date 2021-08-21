@extends('chosen-template')

@section('title')
    Forgot
@endsection

@section('content')
    <div class="chosen-container">
        <div class="chosen-container-form">
            <div class="chosen-container-header">
                <div class="chosen-container-withIcon">
                    <i class="quantic-icon-ftp_codes"></i>
                    <p>Forgot password</p>
                </div>
            </div>
            <div class="chosen-container-body">
                <form id="forgotForm" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    @if(session('status'))
                        <div class="chosen-container-withIcon chosen-resent">
                            <span class="chosen-not-valid">
                                <i class="quantic-icon-warning"></i>
                                {{ session('status') }}
                            </span>
                        </div>
                    @endif
                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-envelope"></i>
                            <label for="forgot_email">Email</label>
                            @if($errors->has('forgot_email'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('forgot_email') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('forgot_email') ? ' is-invalid' : '' }}" type="email" id="forgot_email" name="forgot_email">
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
                <a href="{{ route('login') }}">Back to login</a>
            </div>
        </div>
    </div>
@endsection