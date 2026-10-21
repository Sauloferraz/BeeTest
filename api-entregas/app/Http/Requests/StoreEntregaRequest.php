<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntregaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descricao' => 'required|string|max:255',
            'endereco_coleta' => 'required|string|max:255',
            'endereco_entrega' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
        ];
    }
}
