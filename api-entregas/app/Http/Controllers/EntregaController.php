<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EntregaController extends Controller
{
    public function index()
    {
        return Cache::remember('entregas.todas', 60, function () {
            return Entrega::all();
        });
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'descricao' => 'required|string|max:255',
            'endereco_coleta' => 'required|string|max:255',
            'endereco_entrega' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
        ]);

        $entrega = Entrega::create($dados);

        Cache::forget('entregas.todas');
        return response()->json($entrega, 201);
    }

    public function show(Entrega $entrega)
    {
        return $entrega;
    }

    public function update(Request $request, Entrega $entrega)
    {
        $dados = $request->validate([
            'descricao' => 'sometimes|string|max:255',
            'endereco_coleta' => 'sometimes|string|max:255',
            'endereco_entrega' => 'sometimes|string|max:255',
            'valor' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pendente,em_andamento,entregue,cancelada',
        ]);

        $entrega->update($dados);

        Cache::forget('entregas.todas');
        return $entrega;
    }

    public function destroy(Entrega $entrega)
    {
        $entrega->delete();

        Cache::forget('entregas.todas');
        return response()->noContent();
    }
}
