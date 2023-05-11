<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HolidayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
            'days' => [
                "required",
                "int",
                "min:0",
                "max:50"
            ],
            'year' => "required"
        ];
    }

    public function messages()
    {
        return [
            'days.required' => "Ilość dni jest wymagana",
            'days.max' => "Ilość dni nie może być większa niż 50",
            'days.min' => "Ilość dni nie może być mniejsza niż 0",
            'year.required' => "Rok jest wymagany"
        ];
    }
}
