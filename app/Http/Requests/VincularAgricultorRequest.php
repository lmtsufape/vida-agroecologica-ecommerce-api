<?php

namespace App\Http\Requests;

use App\Models\OrganizacaoControleSocial;
use Illuminate\Foundation\Http\FormRequest;

class VincularAgricultorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->hasAnyRoles(['administrador'])) {
            return true;
        } elseif ($this->user()->hasAnyRoles(['presidente'])) {
            $organizacao = OrganizacaoControleSocial::findOrFail($this->input('organizacao_id'));
            if ($organizacao->associacao->presidentes()->where('id', $this->user()->id)->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "organizacao_id" => 'required|integer|exists:organizacoes_controle_social,id'
        ];
    }

    public function messages()
    {
        return [
            "organizacao_id.numeric"    => "Organização inválida"
        ];
    }
}
