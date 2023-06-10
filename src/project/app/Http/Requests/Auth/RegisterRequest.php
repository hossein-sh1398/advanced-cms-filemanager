<?php

namespace App\Http\Requests\Auth;

use App\Rules\MobileRule;
use App\Rules\RecaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the profile is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'mobile' => ['required', new MobileRule(), 'unique:users,mobile'],
            'password' => ['required', 'min:8', 'string', 'confirmed'],
            //'g-recaptcha-response' => ['required', new RecaptchaRule()],
        ];

        if ($this->has('toc')) {
            $rules['toc'] = 'accepted';
        }

        return $rules;
    }

    public  function attributes()
    {
        return [
            'toc' => 'شرایط و ضوابط'
        ];
    }
}
