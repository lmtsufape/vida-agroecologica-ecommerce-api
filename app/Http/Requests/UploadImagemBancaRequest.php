<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImagemBancaRequest extends FormRequest
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
            'imagem' => [
                'required',
                'image',
                'max:5120'
            ]
        ];
    }

    public function messages()
    {
        return [
            'imagem.required' => 'É necessário enviar uma imagem',
            'imagem.image' => 'O arquivo enviado não é uma imagem',
            'imagem.max' => 'A imagem enviada é muito grande (máximo de :max Bytes)'
        ];
    }
}
