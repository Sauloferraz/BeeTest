<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EntregaController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->entregas();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query->latest('id')->paginate(10);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'descricao' => 'required|string|max:255',
            'endereco_coleta' => 'required|string|max:255',
            'endereco_entrega' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
        ]);

        $entrega = $request->user()->entregas()->create($dados);

        Cache::forget('entregas.todas');

        return response()->json($entrega, 201);
    }

    public function show(Request $request, Entrega $entrega)
    {
        abort_if($entrega->user_id !== $request->user()->id, 403);

        return $entrega;
    }

    public function update(Request $request, Entrega $entrega)
    {
        abort_if($entrega->user_id !== $request->user()->id, 403);

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

    public function destroy(Request $request, Entrega $entrega)
    {
        abort_if($entrega->user_id !== $request->user()->id, 403);

        $entrega->delete();

        Cache::forget('entregas.todas');

        return response()->noContent();
    }
}
