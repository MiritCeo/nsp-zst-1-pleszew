<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeboxRequest extends FormRequest
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
            "start_time" => [
                "required",
                "date",
            ],
            "end_time" => [
                "required",
                "date",
                "after:start_time"
            ],
            "day" => [
                "required",
                "date",
                "before:60 days"
            ],
            "fields.*.working_hours" => [
                "required",
                "gt:0"
            ],
            "fields.*.work_type" => [
                "required"
            ],
            "fields.*.project" => [
                "required"
            ],
            "fields.*.description" => [
                "max:255"
            ],
        ];
    }

    public function messages()
    {
        return [
            'end_time.after' => 'Koniec musi być później niż początek pracy.',
            'fields.*.working_hours.required' => "Godziny pracy są wymagane.",
            'fields.*.working_hours.gt' => 'Podaj ilość większą od 0.',
            'fields.*.description' => 'Opis jest za długi'
        ];
    }
}
