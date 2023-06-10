<?php

namespace App\Utility;

use App\Models\User;

class SmsCode
{
    /**
     * generate sms code
     *
     * @param User $user
     * @return void
     */
    public static function generate($user)
    {
        $user->sms_code = rand(1000, 9999);

        $user->save();
    }

    /**
     * check sms_code
     *
     * @param User $user
     * @param string $smsCode
     * @return bool
     */
    public static function check(User $user, string $smsCode): bool
    {
        return $user->sms_code == $smsCode;
    }
}
