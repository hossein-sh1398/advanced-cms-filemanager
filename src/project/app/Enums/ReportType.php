<?php

namespace App\Enums;

class ReportType
{
    public const Email = 1;
    public const Mobile = 2;


    public static function getTitle($number)
    {
        return  match ($number) {
            self::Email => 'ایمیل',
            self::Mobile => 'موبایل',

        };
    }

    public static function toArray()
    {
        return [
            ['id' => self::Email, 'title' => 'ایمیل'],
            ['id' => self::Mobile, 'title' => 'موبایل'],
        ];
    }
}
