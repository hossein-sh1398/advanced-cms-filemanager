<?php

namespace App\Utility;

use App\Enums\TwoStepType;
use PragmaRX\Google2FALaravel\Exceptions\InvalidSecretKey;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2FAAuthenticator extends Authenticator
{
    protected function canPassWithoutCheckingOTP()
    {
        if ($this->getUser()->google2fa_secret == null || ($this->getUser()->two_step_type != TwoStepType::Google)) {
            return true;
        }

        return
            !($this->getUser()->two_step_status && $this->getUser()->two_step_type == TwoStepType::Google) ||
            !$this->isEnabled() ||
            $this->noUserIsAuthenticated() ||
            $this->twoFactorAuthStillValid();
    }

    protected function getGoogle2FASecretKey()
    {
        $secret = $this->getUser()->{$this->config('otp_secret_column')};

        if (is_null($secret) || empty($secret)) {
            throw new InvalidSecretKey('کد امنیتی نمیتواند خالی باشد ');
        }

        return $secret;
    }
}
