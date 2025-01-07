<?php

namespace App\Services;

use ReCaptcha\ReCaptcha;

class RecaptchaService
{
    public function validate(string $token): bool
    {
        $secret = config('services.recaptcha.secret');
        $recaptcha = new ReCaptcha($secret);

        $response = $recaptcha->verify($token, $_SERVER['REMOTE_ADDR']);
        return $response->isSuccess();
    }
}
