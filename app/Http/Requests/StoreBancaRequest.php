<?php

namespace App\Http\Requests;

use App\Models\Banca;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreBancaRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $formasPagamento = $this->input('formas_pagamento');

        if (!is_array($formasPagamento)) {
            $campo = explode(',', $formasPagamento); // Transforma o valor em um array
            $this->merge(['formas_pagamento' => $campo]); // Atualiza o valor no request
        }

        $bairroEntrega = $this->input('bairro_entrega');

        if (!is_array($bairroEntrega)) {
            $array = explode(',', $bairroEntrega); // Transforma o valor em um array
            $valores = [];
            foreach ($array as $campo) {
                $campo = explode('=>', $campo);
                array_push($valores, $campo);
            }
            $this->merge(['bairro_entrega' => $valores]); // Atualiza o valor no request
        }

        $entrega = $this->input('entrega');
        $this->merge(['entrega' => filter_var($entrega, FILTER_VALIDATE_BOOLEAN)]);
    }

    public function authorize(): bool
    {
        $agricultor = User::findOrFail($this->input('agricultor_id'));

        return $this->user()->can('create', [Banca::class, $agricultor, $this->input('feira_id')]);
    }

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
            'entrega' => [
                'required',
                'boolean'
            ],
            'preco_minimo' => [
                'string'
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
            ],
            'bairro_entrega' => [
                'Required',
                'array'
            ],
            'bairro_entrega.*' => [
                'array',
                'size:2'
            ],
            'bairro_entrega.*.0' => [
                'integer',
                'exists:bairros,id'
            ],
            'bairro_entrega.*.1' => [
                'string'
            ],
            'pix' => [
                'nullable',
                'string'
            ],
            'feira_id' => [
                'required',
                'integer',
                'exists:feiras,id'
            ],
            'agricultor_id' => [
                'required',
                'integer',
                'exists:users,id'
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
