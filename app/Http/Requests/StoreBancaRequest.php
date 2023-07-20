<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;

class StoreBancaRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'nome' => [
                'required',
                'string',
                'max:50',
                'min:3'
            ],
            'descricao' => [
                'required',
                'string',
                'max:250'
            ],
            'horario_abertura' => [
                'required',
                'date_format:H:i:s',
                'before:horario_fechamento'
            ],
            'horario_fechamento' => [
                'required',
                'date_format:H:i:s',
                'after:horario_abertura'
            ],
            'preco_minimo' => [
                'required'
            ],
            'faz_entrega' => [
                'required',
                'boolean'
            ],
            'imagem' => [
                'nullable',
                'image',
                'max:5120'
            ],
            'formas_pagamento' => [
                'required'
            ]
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => ':attribute é um parâmetro obrigatório.',
            'max' => ':attribute deve ter no máximo 50 caracteres.',
            'min' => ':attribute deve ter no mínimo 3 caracteres.',
            'string' => ':attribute deve ser uma string.',
            'date_format' => ':attribute deve estar no formato: hora:minuto:segundo.',
            'horario_abertura.before' => 'Horário de abertura deve ser antes do fechamento.',
            'horario_fechamento.after' => 'Horário de fechamento deve ser depois da abertura.',
            'tipo_entrega.in' => 'Tipo de entrega de ser entrega ou retirada',
            'imagem.image' => 'O arquivo enviado não é uma imagem',
            'imagem.max' => 'A imagem enviada é muito grande (máximo de :max KB)'
        ];
    }
}
