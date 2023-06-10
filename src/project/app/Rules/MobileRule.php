<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MobileRule implements Rule
{
    public const Pattern = "/^[0]{1}[9]{1}\d{9}$/";
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match(self::Pattern, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'فرمت شماره موبایل صحیح نمی باشد';
    }
}