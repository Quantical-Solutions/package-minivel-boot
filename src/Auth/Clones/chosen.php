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
    | Regex ex : 'pattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!#\*\?])(?=.{8,})' // At least 8 characters,
    |             min 1 Uppercase 1 Lowercase 1 Number and 1 special char.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
            'pattern' => '^(?=.*[a-z])(?=.*[0-9])(?=.{8,})' // At least 8 characters, 1 Lowercase 1 Number
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

    'namespace' => 'Minivel\\Auth\\Logic\\',

    'home_page' => '/',

    'constellations' => [

        'login_get' => [
            'data' => [
                'uri' => 'login',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthLogin',
                'method' => 'showLoginForm',
                'request' => 'get',
                'as' => 'login.request'
            ],
            'view' => 'chosen/login'
        ],

        'login_post' => [
            'data' => [
                'uri' => 'login',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthLogin',
                'method' => 'login',
                'request' => 'post',
                'as' => 'login'
            ],
            'view' => 'chosen/login'
        ],

        'logout' => [
            'data' => [
                'uri' => 'logout',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthLogin',
                'method' => 'logout',
                'request' => 'post',
                'as' => 'logout'
            ],
            'view' => 'chosen/login'
        ],

        'register_get' => [
            'data' => [
                'uri' => 'register',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthRegister',
                'method' => 'showRegistrationForm',
                'request' => 'get',
                'as' => 'register.request'
            ],
            'view' => 'chosen/register'
        ],

        'register_post' => [
            'data' => [
                'uri' => 'register',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthRegister',
                'method' => 'register',
                'request' => 'post',
                'as' => 'register'
            ],
            'view' => 'chosen/register'
        ],

        'forgot_get' => [
            'data' => [
                'uri' => 'password/forgot',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthForgot',
                'method' => 'showLinkRequestForm',
                'request' => 'get',
                'as' => 'password.request'
            ],
            'view' => 'chosen/passwords/forgot'
        ],

        'forgot_post' => [
            'data' => [
                'uri' => 'password/forgot',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthForgot',
                'method' => 'sendResetLinkEmail',
                'request' => 'post',
                'as' => 'password.email'
            ],
            'view' => 'chosen/passwords/forgot'
        ],

        'reset_get' => [
            'data' => [
                'uri' => 'password/reset/{token}',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthReset',
                'method' => 'showResetForm',
                'request' => 'get',
                'as' => 'password.reset'
            ],
            'view' => 'chosen/passwords/reset'
        ],

        'reset_post' => [
            'data' => [
                'uri' => 'password/reset',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthReset',
                'method' => 'reset',
                'request' => 'post',
                'as' => 'password.update'
            ],
            'view' => 'chosen/passwords/reset'
        ],

        'confirm_get' => [
            'data' => [
                'uri' => 'password/confirm',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthConfirm',
                'method' => 'showConfirmForm',
                'request' => 'get',
                'as' => 'password.confirm'
            ],
            'view' => 'chosen/passwords/confirm'
        ],

        'confirm_post' => [
            'data' => [
                'uri' => 'password/confirm',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthConfirm',
                'method' => 'confirm',
                'request' => 'post',
                'as' => 'password.confirmed'
            ],
            'view' => 'chosen/passwords/confirm'
        ],

        'verify_notice_get' => [
            'data' => [
                'uri' => 'email/verify',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthVerify',
                'method' => 'showNoticeForm',
                'request' => 'post',
                'as' => 'verification.notice'
            ],
            'view' => 'chosen/verify'
        ],

        'verify_get' => [
            'data' => [
                'uri' => 'email/verify/{id}/{hash}',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthVerify',
                'method' => 'verify',
                'request' => 'post',
                'as' => 'verification.verify',
                'where' => ['id' => '[0-9]+']
            ],
            'view' => 'chosen/verify'
        ],

        'verify_post' => [
            'data' => [
                'uri' => 'email/resend',
                'group' => env('APP_URL_ADMIN', config('app.url')),
                'controller' => 'AuthVerify',
                'method' => 'resend',
                'request' => 'post',
                'as' => 'verification.resend'
            ],
            'view' => 'chosen/verify'
        ],
    ],
];
