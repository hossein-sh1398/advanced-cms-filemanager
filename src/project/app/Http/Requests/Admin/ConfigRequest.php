<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ConfigRequest extends FormRequest
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
        if ($this->isMethod('delete')) {
            return [
                'ids' => ['required'],
                'ids.*' => ['required', 'exists:configs,id'],
            ];
        } elseif (Route::currentRouteName('admin.configs.update')) {
            return [
                'promotion_expire' => ['date'],
                'general_offline_text' => ['required'],
                'sms_url' => ['nullable', 'url'],
                'promotion_link' => ['nullable', 'url'],
                'general_default_avatar' => ['nullable', 'max:10000', 'mimes:jpg,jpeg,png'],
                'promotion_image' => ['nullable', 'max:10000', 'mimes:jpg,jpeg,png'],
                'general_default_logo' => ['nullable', 'max:10000', 'mimes:jpg,jpeg,png'],
                'image_watermark_file' => ['nullable', 'max:10000', 'mimes:jpg,jpeg,png'],
                'image_favicon' => ['nullable', 'max:10000', 'mimes:jpg,jpeg,png,ico'],
            ];
        }

        return [
            'key' => ['required', 'min:3', 'max:255'],
            'value' => ['nullable'],
        ];
    }
}
