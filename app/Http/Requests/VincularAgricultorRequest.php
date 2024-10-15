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
            $organizacao = OrganizacaoControleSocial::with('associacao.presidentes')->findOrFail($this->input('organizacao_id'));
            if ($organizacao->associacao->id && $this->user()->associacao->id) {
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
            "organizacao_id.integer"    => "Organização inválida",
            "organizacao_id.required"   => "O campo de organização é obrigatório.",
            "organizacao_id.exists"     => "A organização selecionada não existe."
        ];
    }
}
