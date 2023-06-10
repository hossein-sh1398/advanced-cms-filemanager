<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
            'title' => ['required', 'min:3', 'max:255']
        ];

        if ($this->isMethod('post')) {
            $rules['title'][] = 'unique:tags,title';
        } elseif ($this->isMethod('patch')) {
            $rules['title'][] = 'unique:tags,title,' . $this->route()->parameter('tag')->id;
        } else {
            return [
                'ids' => ['array', 'required'],
                'ids.*' => ['exists:tags,id'],
            ];
        }

        return $rules;
    }
}
