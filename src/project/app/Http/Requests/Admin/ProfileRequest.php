<?php

namespace App\Http\Requests\Admin;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        return [
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'. auth()->id()],
            'mobile' => ['required', new MobileRule(), 'unique:users,mobile,' . auth()->id()],
            'password' => ['nullable', 'min:8', 'string'],
            're_password' => ['nullable', 'same:password'],
            'avatar' => ['nullable', 'max:10000', 'mimes:jpg,jpeg,png'],
            'twoStepMethodType' => ['nullable'],
            'twoStepMethodStatus' => ['nullable', 'boolean']
        ];
    }

}
