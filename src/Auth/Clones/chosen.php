<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

    /*
    |--------------------------------------------------------------------------
    | Logic constellations
    |--------------------------------------------------------------------------
    |
    | Are defined all constellations for authentication rooting.
    | You can redefined URLs and 'as' index.
    |
    */

    'namespace' => 'Quantic\\Chosen\\Logic\\',

    'constellations' => [

        'login_get' => [
            'data' => [
                'uri' => 'login',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenLogin',
                'method' => 'showLoginForm',
                'request' => 'get',
                'as' => 'login.request'
            ],
            'view' => 'chosen/login'
        ],

        'login_post' => [
            'data' => [
                'uri' => 'login',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenLogin',
                'method' => 'login',
                'request' => 'post',
                'as' => 'login'
            ],
            'view' => 'home'
        ],

        'logout' => [
            'data' => [
                'uri' => 'login',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenLogin',
                'method' => 'logout',
                'request' => 'post',
                'as' => 'logout'
            ],
            'view' => 'chosen/login'
        ],

        'register_get' => [
            'data' => [
                'uri' => 'register',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenRegister',
                'method' => 'showRegistrationForm',
                'request' => 'get',
                'as' => 'register.request'
            ],
            'view' => 'chosen/register'
        ],

        'register_post' => [
            'data' => [
                'uri' => 'register',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenRegister',
                'method' => 'register',
                'request' => 'post',
                'as' => 'register'
            ],
            'view' => 'chosen/register'
        ],

        'forgot_get' => [
            'data' => [
                'uri' => 'password/forgot',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenForgot',
                'method' => 'showLinkRequestForm',
                'request' => 'get',
                'as' => 'password.request'
            ],
            'view' => 'chosen/passwords/forgot'
        ],

        'forgot_post' => [
            'data' => [
                'uri' => 'password/forgot',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenForgot',
                'method' => 'sendResetLinkEmail',
                'request' => 'post',
                'as' => 'password.email'
            ],
            'view' => 'chosen/passwords/forgot'
        ],

        'reset_get' => [
            'data' => [
                'uri' => 'password/reset/{token}',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenReset',
                'method' => 'showResetForm',
                'request' => 'get',
                'as' => 'password.reset'
            ],
            'view' => 'chosen/passwords/reset'
        ],

        'reset_post' => [
            'data' => [
                'uri' => 'password/reset',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenReset',
                'method' => 'reset',
                'request' => 'post',
                'as' => 'password.update'
            ],
            'view' => 'chosen/passwords/reset'
        ],

        'confirm_get' => [
            'data' => [
                'uri' => 'password/confirm',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenConfirm',
                'method' => 'showConfirmForm',
                'request' => 'get',
                'as' => 'password.confirm'
            ],
            'view' => 'chosen/passwords/confirm'
        ],

        'confirm_post' => [
            'data' => [
                'uri' => 'password/confirm',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenConfirm',
                'method' => 'confirm',
                'request' => 'post',
                'as' => 'password.confirmed'
            ],
            'view' => 'chosen/passwords/confirm'
        ],

        'verify_notice_get' => [
            'data' => [
                'uri' => 'email/verify',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenVerify',
                'method' => 'showNoticeForm',
                'request' => 'post',
                'as' => 'verification.notice'
            ],
            'view' => 'chosen/verify'
        ],

        'verify_get' => [
            'data' => [
                'uri' => 'email/verify/{id}/{hash}',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenVerify',
                'method' => 'verify',
                'request' => 'post',
                'as' => 'verification.verify'
            ],
            'view' => 'chosen/verify'
        ],

        'verify_post' => [
            'data' => [
                'uri' => 'email/resend',
                'group' => config('app.url_admin'),
                'controller' => 'ChosenVerify',
                'method' => 'resend',
                'request' => 'post',
                'as' => 'verification.resend'
            ],
            'view' => 'chosen/verify'
        ],
    ],
];
