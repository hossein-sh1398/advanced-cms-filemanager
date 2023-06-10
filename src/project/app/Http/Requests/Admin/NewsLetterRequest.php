<?php

namespace App\Http\Requests\Admin;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class NewsLetterRequest extends FormRequest
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
                'email' => ['required', 'max:255', 'email', 'unique:news_letters,email'],

                'mobile' => ['nullable', new MobileRule, 'unique:news_letters,mobile'],
            ];
        } elseif ($this->isMethod('patch')) {
            return [
                'email' => ['required', 'max:255', 'email', 'unique:news_letters,email,' . $this->newsLetter->id],

                'mobile' => ['nullable', new MobileRule, 'unique:news_letters,mobile,' . $this->newsLetter->id],
            ];
        } else {
            return [
                'ids' => ['array', 'required',],
                'ids.*' => ['exists:news_letters,id'],
            ];
        }
    }
}

