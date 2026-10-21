<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $fillable = [
        'descricao',
        'endereco_coleta',
        'endereco_entrega',
        'valor',
        'status',
    ];
}
