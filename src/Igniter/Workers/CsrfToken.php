<?php

namespace Minivel\Igniter\Workers;

use Minivel\Auth\Matrix\Auth;

class CsrfToken
{
    private static string $token;
    private static string $cookie;

    public static function init(): bool
    {
        self::$cookie = 'XSRF-TOKEN';
        self::verifyForm();
        return self::compare();
    }

    private static function verifyForm(): bool
    {
        $token = null;
        addSolution('Token mismatch', 'You should include <b>"@csrf"</b> in your HTML Form.');

        if (!empty($_GET) || !empty($_POST)) {

            foreach ($_GET as $key => $value) {

                if ($key === '_token' && $value === $_SESSION['_token']) {
                    $token = $value;
                }
            }
            foreach ($_POST as $key => $value) {
                
                if ($key === '_token' && $value === $_SESSION['_token']) {
                    $token = $value;
                }
            }

            if ($token === null) {

                trigger_error('The security token mismatch or is not present.');
            }
        }
        return true;
    }

    private static function createCookie($maintain)
    {
        self::$token = ($maintain === true)
            ? $_SESSION['_token']
            : self::generateToken(40);

        setcookie(
            self::$cookie,
            self::$token,
            time() + ((int) config('session.lifetime') * 60), "/" // 86400 = 1 day
        );

        if ($maintain === false) {
            $_SESSION['_token'] = self::$token;
        }
    }

    private static function deleteCookie()
    {
        $cookie_name = "XSRF-TOKEN";
        setcookie($cookie_name, time() - 3600);
    }

    private static function compare(): bool
    {
        if (Auth::isAdmin()) {

            if (!isset($_SESSION['_token'])) {

                self::createCookie(false);

            } else {

                if (isset($_COOKIE[self::$cookie])) {

                    if ($_SESSION['_token'] === $_COOKIE[self::$cookie]) {

                        self::createCookie(true);
                        return true;

                    } else {

                        self::createCookie(false);
                        return false;
                    }

                } else {

                    unset($_SESSION['_token']);
                    Auth::reset();
                    handleErrors(419);
                }
            }

            return false;

        } else {

            if (!isset($_SESSION['_token'])) {

                self::createCookie(false);
                return false;

            } else {

                self::createCookie(true);
                return true;
            }
        }
    }

    private static function generateToken($val_length): string
    {
        $result = '';
        $module_length = 40;   // we use sha1, so module is 40 chars
        $steps = round(($val_length/$module_length) + 0.5);

        for ($i = 0; $i < $steps; $i++) {

            $result .= sha1(
                uniqid() . md5(
                    rand()
                )
            );
        }

        return substr($result, 0, $val_length);
    }
}