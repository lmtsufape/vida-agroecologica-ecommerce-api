<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

    public function prepareForValidation()
    {
        $horarios_funcionamento = json_decode($this->input('horarios_funcionamento'), true);
        $this->merge(['horarios_funcionamento' => $horarios_funcionamento]);
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
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/',  // regex para validar apenas letras do alfabeto (maiúsculas e minúsculas, com acento ou não) e espaços em branco.
                'min:3',
                'max:60'
            ],
            'descricao' => [
                'required',
                'max:120',
                'string'
            ],
            'imagem' => [
                'nullable',
                'image',
                'max:5120'
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
            'bairro_id' => [
                'required',
                'integer',
                'exists:bairros,id'
            ],
            'associacao_id' => [
                'required',
                'integer',
                'exists:associacoes,id'
            ]
        ];

        return $rules;
    }
}
