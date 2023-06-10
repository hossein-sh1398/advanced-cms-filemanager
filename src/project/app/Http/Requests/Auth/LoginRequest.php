<?php

namespace App\Http\Requests\Auth;

use App\Rules\MobileRule;
use App\Rules\RecaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    private mixed $username;

    /**
     * Determine if the profile is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'password' => ['required'],
            //'g-recaptcha-response' => ['required', new RecaptchaRule()],
        ];

        if (isMobile($this->get('username'))) {
            $rules['username'] = ['required', new MobileRule()];
        } else {
            $rules['username'] = ['required', 'email'];
        }
        return $rules;
    }
}
