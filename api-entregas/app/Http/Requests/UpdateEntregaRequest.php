<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEntregaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descricao' => 'sometimes|string|max:255',
            'endereco_coleta' => 'sometimes|string|max:255',
            'endereco_entrega' => 'sometimes|string|max:255',
            'valor' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pendente,em_andamento,entregue,cancelada',
        ];
    }
}
