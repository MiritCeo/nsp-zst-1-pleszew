<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkerCostRequest extends FormRequest
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
            'hours' => "required|numeric|min:0",
            'wage' => "required|numeric|min:0",
            'tax' => "required|numeric|min:0|max:100",
            'cost' => "required|numeric|min:0"
        ];
    }

    public function messages()
    {
        return [
            '*.required' => "Pole jest wymagane",
            '*.min' => "Wartość nie może być ujemna",
            '*.numeric' => "Wartość musi być liczbą",
            'tax.max' => "Wartość nie może być większa niż 100"
        ];
    }
}