<?php

namespace App\Http\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LikeRequest extends FormRequest
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
            'likeable_type' => [
               "bail",
               "required",
               "string",
               function ($attribute, $value, $fail) {
                   if (! class_exists($value, true)) {
                       $fail($value . " کلاس انتخاب شده متعبر نمی باشد");
                   }

                   if (! in_array(Model::class, class_parents($value))) {
                       $fail($value . " is not Illuminate\Database\Eloquent\Model");
                   }
               },
           ],
           'likeable_id' => [
                "required",
                function ($attribute, $value, $fail) {
                    $class = $this->input('likeable_type');

                    if (! $class::where('id', $value)->exists()) {
                        $fail($value . "مقدار فیلد آدی معتبر نمی باشد");
                    }
                },
            ],
            'vote' => ['required', Rule::in(1, 2, 3, 4, 5)],
        ];
    }

    public function likeable()
    {
        $class = $this->input('likeable_type');

        return $class::findOrFail($this->input('likeable_id'));
    }

}
