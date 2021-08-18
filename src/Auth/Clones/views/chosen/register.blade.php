@extends('chosen-template')

@section('title')
    Quantic - Register
@endsection

@section('content')
    <div class="chosen-container">
        <div class="chosen-container-form">
            <div class="chosen-container-header">
                <div class="chosen-container-withIcon">
                    <i class="quantic-icon-clipboard"></i>
                    <p>Register new user</p>
                </div>
            </div>
            <div class="chosen-container-body">
                <form id="registerForm" method="POST" action="{{ constellation('register') }}">
                    @csrf

                    <div class="chosen-form-container chosen-form-container-avatar">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-profile"></i>
                            <label for="register_avatar">Avatar</label>
                        </div>
                        <div class="chosen-image-preview-container">
                            <i onclick="this.nextElementSibling.nextElementSibling.click()" class="quantic-icon-picture" title="Upload image"></i>
                            <i onclick="resetChosenImagePreview(this)" class="quantic-icon-cancel1" title="Upload image"></i>
                            <input type="file" accept="image/*" id="register_avatar" name="register_avatar" onchange="displayChosenImagePreview(this)">
                        </div>
                    </div>

                    <div class="chosen-hr-style"></div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-profile"></i>
                            <label for="register_firstname">Firstname</label>
                            @if($errors->has('register_firstname'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('register_firstname') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('register_firstname') ? ' is-invalid' : '' }}" type="text" id="register_firstname" name="register_firstname">
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-profile"></i>
                            <label for="register_lastname">Lastname</label>
                            @if($errors->has('register_lastname'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('register_lastname') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('register_lastname') ? ' is-invalid' : '' }}" type="text" id="register_lastname" name="register_lastname">
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-envelope"></i>
                            <label for="register_email">Email</label>
                            @if($errors->has('register_email'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('register_email') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('register_email') ? ' is-invalid' : '' }}" type="email" id="register_email" name="register_email">
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-key"></i>
                            <label for="register_password">Password</label>
                            @if($errors->has('register_password'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('register_password') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('register_password') ? ' is-invalid' : '' }}" type="password" id="register_password" name="register_password">
                        <i onclick="displayPassword(this)" class="password-displayer quantic-icon-locked1"></i>
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-key"></i>
                            <label for="register_confirm_password">Confirm password</label>
                            @if($errors->has('register_confirm_password'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('register_confirm_password') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('register_confirm_password') ? ' is-invalid' : '' }}" type="password" id="register_confirm_password" name="register_confirm_password">
                        <i onclick="displayPassword(this)" class="password-displayer quantic-icon-locked1"></i>
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-events"></i>
                            <label for="register_birth_date">Birth date</label>
                            @if($errors->has('register_birth_date'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('register_birth_date') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('register_birth_date') ? ' is-invalid' : '' }}" type="date" id="register_birth_date" name="register_birth_date">
                    </div>

                    <div class="chosen-chapter chosen-container-withIcon">
                        <i class="quantic-icon-direction"></i>
                        <label for="register_address">ADDRESS (optional)</label>
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-position"></i>
                            <label for="register_address">Address</label>
                            @if($errors->has('register_address'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('register_address') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('register_address') ? ' is-invalid' : '' }}" type="text" id="register_address" name="register_address">
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-position"></i>
                            <label for="register_address_details">Address details</label>
                            @if($errors->has('register_address_details'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('register_address_details') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('register_address_details') ? ' is-invalid' : '' }}" type="text" id="register_address_details" name="register_address_details">
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-pin1"></i>
                            <label for="register_address_zip">Zip code</label>
                            @if($errors->has('register_address_zip'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('register_address_zip') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('register_address_zip') ? ' is-invalid' : '' }}" type="number" id="register_address_zip" name="register_address_zip">
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-location"></i>
                            <label for="register_address_city">City</label>
                            @if($errors->has('register_address_city'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('register_address_city') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('register_address_city') ? ' is-invalid' : '' }}" type="text" id="register_address_city" name="register_address_city">
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-location"></i>
                            <label for="register_address_state">State</label>
                            @if($errors->has('register_address_state'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('register_address_state') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('register_address_state') ? ' is-invalid' : '' }}" type="text" id="register_address_state" name="register_address_state">
                    </div>

                    <div class="chosen-form-container">
                        <div class="chosen-container-withIcon">
                            <i class="quantic-icon-location"></i>
                            <label for="register_address_country">Country</label>
                            @if($errors->has('register_address_country'))
                                <span class="chosen-not-valid">
                                    <i class="quantic-icon-warning"></i>
                                    {{ $errors->first('register_address_country') }}
                                </span>
                            @endif
                        </div>
                        <input class="{{ $errors->has('register_address_country') ? ' is-invalid' : '' }}" type="text" id="register_address_country" name="register_address_country">
                    </div>

                    <div class="chosen-form-container-buttons">
                        <button disabled class="chosen-container-withIcon" type="submit" id="register_submit_btn">
                            <i class="quantic-icon-rocket"></i>
                            <span>Submit</span>
                        </button>
                        <button class="chosen-container-withIcon" type="reset" id="register_reset_btn">
                            <i class="quantic-icon-cancel"></i>
                            <span>Reset</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="chosen-container-footer">
                <a href="/">Back to home</a>
            </div>
        </div>
    </div>
@endsection