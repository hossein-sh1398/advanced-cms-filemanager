<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ArticleCategoryRequest extends FormRequest
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
            'title' => ['required', 'min:3', 'max:255'],
            'parent_id' => ['nullable', 'exists:article_categories,id'],
            'comment' => ['nullable', 'boolean'],
            'lang' => ['nullable'],
            'content' => ['nullable', 'min:8', 'string'],
            'photo' => ['nullable', 'max:10000', 'mimes:jpg,jpeg,png'],
        ];
    }

}
