<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCidadeRequest extends FormRequest
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
            'nome' => [
                'required',
                'regex:/[a-zA-ZÀ-ÿ\s]/',
                'unique:cidades,nome',
                'max:30',
                'min:3'
            ],
            'estado_id' => [
                'required',
                'integer',
                'exists:estados,id'
            ]
        ];

        return $rules;
    }
}
