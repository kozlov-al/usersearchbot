<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Telegram\Bot\Laravel\Facades\Telegram;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '1074162449:*',
        '1074162449:AAGmQ7cf_SZCOG9nkqVkaec3ZTEgw8sPiIg/webhook',
    ];

//    /**
//     * VerifyCsrfToken constructor.
//     * @param Application $app
//     * @param Encrypter $encrypter
//     */
//    public function __construct(Application $app, Encrypter $encrypter)
//    {
//        $this->app = $app;
//        $this->except = $encrypter;
//        $this->except = Telegram::getAccessToken();
//    }
}
