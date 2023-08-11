<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeiraRequest extends FormRequest
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
        $rules = [
            'funcionamento' => [
                'required',
                'array'
            ],
            'horario_abertura' => [
                'required',
                'date_format:H:i',
                'before:horario_fechamento'
            ],
            'horario_fechamento' => [
                'required',
                'date_format:H:i',
                'after:horario_abertura'
            ],
            'bairro_id' => [
                'required',
                'integer',
                'exists:bairros,id'
            ]
        ];

        return $rules;
    }
}
