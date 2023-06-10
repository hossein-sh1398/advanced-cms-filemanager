<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return [
                'name' => ['bail', 'required', 'min:3', 'max:255', Rule::notIn(['superadmin'], 'unique:roles,name', )],
                'persian_name' => ['bail', 'required', 'min:3', 'max:255', 'unique:roles,persian_name'],
            ];
        } elseif ($this->isMethod('patch')) {
            return [
                'name' => [
                    'bail',
                    'required',
                    'min:3',
                    'max:255',
                    Rule::notIn(['superadmin']),
                    'unique:roles,name,'. $this->role->id,
                ],
                'persian_name' => [
                    'bail',
                    'required',
                    'min:3',
                    'max:255',
                    'unique:roles,persian_name,'. $this->role->id,
                ],
            ];
        } else {
            return [
                'ids' => ['array', 'required'],
                'ids.*' => ['exists:roles,id'],
            ];
        }
    }

}
