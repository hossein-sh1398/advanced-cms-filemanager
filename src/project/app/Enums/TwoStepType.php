<?php

namespace App\Enums;

class TwoStepType
{
    public const Email = 1;
    public const Mobile = 2;
    public const Google = 3;


    public static function getTitle($number)
    {
        return  match ($number) {
            self::Email => 'با ایمیل',
            self::Mobile => 'با موبایل',
            self::Google => 'با گوگل',
        };
    }

    public static function toArray()
    {
        return [
            ['id' => self::Email, 'title' => 'با ایمیل'],
            ['id' => self::Mobile, 'title' => 'با موبایل'],
            ['id' => self::Google, 'title' => 'با گوگل'],
        ];
    }
}
