<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ArticleRequest extends FormRequest
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
        if (Route::currentRouteName() == 'admin.articles.upload.videos') {
            return [
                'video' => ['required', 'max:10000', 'mimes:mp4'],
            ];
        }

        if (Route::currentRouteName() == 'admin.articles.upload.photos') {
            return [
                 'photo' => ['required', 'max:10000', 'mimes:jpg,jpeg,png'],
            ];
        }

        $rules = [
            'title' => ['required', 'min:3', 'max:255'],
            'active' => ['nullable', 'boolean'],
            'category_id' => ['required', 'exists:article_categories,id'],
            'special' => ['nullable', 'boolean'],
            'content' => ['required'],
            'meta_description' => ['nullable'],
            'tags' => ['nullable'],
            'published_at' => ['nullable', 'date'],
            'photo' => ['required', 'max:10000', 'mimes:jpg,jpeg,png'],
        ];

        if ($this->isMethod('patch')) {
            $rules['photo'] = ['nullable', 'max:10000', 'mimes:jpg,jpeg,png'];
        }

        return $rules;
    }
}

