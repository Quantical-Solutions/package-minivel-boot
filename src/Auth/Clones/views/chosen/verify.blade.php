@extends('chosen-template')

@section('title')
    Verify
@endsection

@section('content')
    <div class="chosen-container">
        <div class="chosen-container-form">
            <div class="chosen-container-header">
                <div class="chosen-container-withIcon">
                    <i class="quantic-icon-ftp_codes"></i>
                    <p>Verify email address</p>
                </div>
            </div>
            <div class="chosen-container-body">
                @if(session('resent'))
                    <div class="chosen-container-withIcon chosen-resent">
                        <span>
                            <i class="quantic-icon-paperplane"></i>
                            An email has been sent to your email address
                        </span>
                    </div>
                @endif
                <p>Before proceeding, please check your email for a verification link.</p>
                <br>
                <p>If you didn't received any email please click on the resend email link.</p>
                <form id="verifyForm" method="POST" action="{{ route('verification.resend') }}">
                    @csrf

                    <div class="chosen-form-container-buttons">
                        <button class="chosen-container-withIcon" type="submit" id="verify_submit_btn">
                            <i class="quantic-icon-rocket"></i>
                            <span>Resend verification email</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection