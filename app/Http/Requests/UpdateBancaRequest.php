<?php

namespace App\Http\Requests;

use App\Models\Banca;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBancaRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $formasPagamento = $this->input('formas_pagamento');

        if (!is_array($formasPagamento)) {
            $campo = explode(',', $formasPagamento); // Transforma o valor em um array
            $this->merge(['formas_pagamento' => $campo]); // Atualiza o valor no request
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $banca = Banca::findOrFail($this->route('banca'));
        return $this->user()->can('update', $banca);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nome' => [
                'required',
                'string',
                'max:60',
                'min:3'
            ],
            'descricao' => [
                'nullable',
                'string',
                'max:120'
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
            'preco_minimo' => [
                'required',
                'decimal:2'
            ],
            'imagem' => [
                'nullable',
                'image',
                'max:5120'
            ],
            'formas_pagamento' => [
                'required',
                'array',
                'min:1'
            ],
            'formas_pagamento.*' => [
                'integer',
                'exists:formas_pagamento,id'
            ]
        ];
    }

    public function messages()
    {
        return [
            'required' => 'o campo :attribute é um parâmetro obrigatório.',
            'max' => 'o campo :attribute deve ter no máximo :max caracteres.',
            'min' => 'o campo :attribute deve ter no mínimo :min caracteres.',
            'string' => 'o campo :attribute deve ser uma string.',
            'date_format' => 'o campo :attribute deve estar no formato hora:minuto.',
            'horario_abertura.before' => 'Horário de abertura deve ser antes do Horário de fechamento.',
            'horario_fechamento.after' => 'Horário de fechamento deve ser depois do Horário de abertura.',
            'imagem.image' => 'O arquivo enviado não é uma imagem',
            'imagem.max' => 'A imagem enviada é muito grande (máximo de :max KB)'
        ];
    }
}
