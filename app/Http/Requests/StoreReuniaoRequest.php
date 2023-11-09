<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReuniaoRequest extends FormRequest
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
            'titulo' => [
                'required',
                'string',
                'max:60'
            ],
            'pauta' => [
                'required',
                'string'
            ],
            'data' => [
                'required',
                'date'
            ],
            'tipo' => [
                'required',
                'string',
                'in:ordinaria,extraordinaria,multirao'
            ],
            'participantes' => [
                'required',
                'array',
                'min:2'
            ],
            'participantes.*' => [
                'integer',
                'exists:users,id'
            ],
            'associacao_id' => [
                'required',
                'integer',
                'exists:associacoes,id'
            ],
            'organizacao_id' => [
                'required',
                'integer',
                'exists:organizacoes_controle_social,id'
            ],
        ];
    }
}
