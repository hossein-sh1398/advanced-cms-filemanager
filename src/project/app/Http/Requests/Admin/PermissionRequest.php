<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    /**
     * Determine if the profile is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isMethod('patch') && $this->permission->global) {
            session()->flash('success', 'امکان ویرایش پرمیشن های گلوبال نیست');

            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $roles = [
            'group' => ['required', 'min:3', 'max:255'],
            'name' => ['required', 'min:3', 'max:255']
        ];

        if ($this->isMethod('post')) {
            $roles['name'][] = 'unique:permissions,name';
        } else {
            $roles['name'][] = 'unique:permissions,name,'. $this->permission->id;
        }

        return $roles;
    }
}
