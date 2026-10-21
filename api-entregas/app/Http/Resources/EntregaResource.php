<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntregaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'descricao' => $this->descricao,
            'endereco_coleta' => $this->endereco_coleta,
            'endereco_entrega' => $this->endereco_entrega,
            'valor' => (float) $this->valor,
            'status' => $this->status,
            'criado_em' => $this->created_at,
        ];
    }
}
