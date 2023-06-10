<?php

namespace App\Rules;

use App\Models\Config;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class RecaptchaRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $configs = Config::whereIn('key', ['api_recaptcha_secret', 'api_recaptcha_client'])->pluck('value', 'key')->toArray();

        $response = Http::asForm()->post($configs['api_recaptcha_client'], [
            'secret' => $configs['api_recaptcha_secret'],
            'response' => $value,
            'remoteip' => request()->ip()
        ]);

        $response = json_decode($response->body());

        return $response->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'کپچا نامعتبر می باشد';
    }
}
