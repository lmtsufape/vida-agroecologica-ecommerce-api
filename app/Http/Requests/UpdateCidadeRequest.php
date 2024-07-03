<?php

namespace App\Http\Requests;

use App\Models\Cidade;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCidadeRequest extends FormRequest
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
            'nome' => [
                'required',
                'regex:/[a-zA-ZÀ-ÿ\s]/',
                Rule::unique('cidades', 'nome')->ignore($this->route('cidade')),
                'max:30',
                'min:3'
            ]
        ];
    }
    
}
