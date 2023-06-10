<?php

namespace App\Http\Requests\Admin;

use App\Rules\MobileRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'roles' => ['array', 'required'],
            'roles.*' => ['exists:roles,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'mobile' => ['required', new MobileRule(), 'unique:users,mobile'],
            'password' => ['required', 'min:8', 'string', 'confirmed'],
        ];
        
        if ($this->isMethod('patch')) {
            $rules['email'] = ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id, 'id')];
            $rules['mobile'] = ['required', new MobileRule(), Rule::unique('users', 'mobile')->ignore($this->user->id, 'id')];
            $rules['password'] = ['nullable', 'min:8', 'string'];
        }

        return $rules;
    }
}
