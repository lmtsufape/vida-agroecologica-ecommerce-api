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

        // $bairroEntrega = $this->input('bairro_entrega');

        // if (!is_array($bairroEntrega)) {
        //     $array = explode(',', $bairroEntrega); // Transforma o valor em um array
        //     $valores = [];
        //     foreach ($array as $campo) {
        //         $campo = explode('=>', $campo);
        //         array_push($valores, $campo);
        //     }
        //     $this->merge(['bairro_entrega' => $valores]); // Atualiza o valor no request
        // }

        $entrega = $this->input('entrega');
        $this->merge(['entrega' => filter_var($entrega, FILTER_VALIDATE_BOOLEAN)]);


        //tratamento do json de horarios_funcionamento
        $horarios_funcionamento = $this->input('horarios_funcionamento');
        
        if (is_string($horarios_funcionamento)) {
            $horarios_funcionamento = json_decode($horarios_funcionamento, true);
        }
        
        $this->merge(['horarios_funcionamento' => $horarios_funcionamento]);
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
            'horarios_funcionamento' => [
                'required',
                'array',
                'min:1',
                'max:7',
                function ($attribute, $value, $fail) {
                    $allowedKeys = ['domingo', 'segunda-feira', 'terca-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
                    foreach ($value as $key => $val) {
                        if (!in_array($key, $allowedKeys)) {
                            $fail("A chave '$key' do elemento não pertence ao conjunto de valores permitidos: " . implode(', ', $allowedKeys));
                        }
                    }
                },
            ],
            'horarios_funcionamento.*' => [
                'array',
                'size:2'
            ],
            'horarios_funcionamento.*.0' => [
                'date_format:H:i',
            ],
            'horarios_funcionamento.*.1' => [
                'date_format:H:i',
                'after:horarios_funcionamento.*.0'
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
            // 'bairro_entrega' => [
            //     'Required',
            //     'array'
            // ],
            // 'bairro_entrega.*' => [
            //     'array',
            //     'size:2'
            // ],
            // 'bairro_entrega.*.0' => [
            //     'integer',
            //     'exists:bairros,id'
            // ],
            // 'bairro_entrega.*.1' => [
            //     'string'
            // ],
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
            'horarios_funcionamento.min'                => 'É necessário ao menos um dia de funcionamento.',
            'horarios_funcionamento.max'                => 'Não é permitido mais de 7 dias de funcionamento.',
            'horarios_funcionamento.*.array'            => 'Cada dia deve ser um array com horário de abertura e fechamento.',
            'horarios_funcionamento.*.0.date_format'    => 'O horário de abertura deve estar no formato hora:minuto.',
            'horarios_funcionamento.*.1.date_format'    => 'O horário de fechamento deve estar no formato hora:minuto.',
            'horarios_funcionamento.*.1.after'          => 'O horário de fechamento deve ser depois do horário de abertura.',
            'imagem.image' => 'O arquivo enviado não é uma imagem',
            'imagem.max' => 'A imagem enviada é muito grande (máximo de :max KB)'
        ];
    }
}
