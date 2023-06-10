<?php

namespace App\Http\Requests\Admin;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            $this->merge(['ip' => $this->ip()]);

            return [
                'name' => ['required', 'min:3', 'max:255'],
                'email' => ['required', 'email'],
                'mobile' => ['required', new MobileRule()],
                'content' => ['required', 'min:3'],
            ];
        } elseif ($this->isMethod('delete')) {
            return [
                'ids' => ['array', 'required'],
                'ids.*' => ['exists:messages,id'],
            ];
        }
    }
}

