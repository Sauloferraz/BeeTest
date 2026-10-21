<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Http\Resources\EntregaResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreEntregaRequest;
use App\Http\Requests\UpdateEntregaRequest;

class EntregaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $status = $request->input('status', 'todos');
        $page = $request->input('page', 1);

        $entregas = Cache::tags(["entregas.user.{$user->id}"])
            ->remember("entregas.status.{$status}.page.{$page}", 300, function () use ($request, $user) {
                $query = $user->entregas();

                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                return $query->latest('id')->paginate(10);
            });

        return EntregaResource::collection($entregas);
    }

    public function store(StoreEntregaRequest $request)
    {
        $entrega = $request->user()->entregas()->create($request->validated());

        Cache::tags(["entregas.user.{$request->user()->id}"])->flush();

        return new EntregaResource($entrega);
    }

    public function show(Request $request, Entrega $entrega)
    {
        abort_if($entrega->user_id !== $request->user()->id, 403);

        return new EntregaResource($entrega);
    }

    public function update(UpdateEntregaRequest $request, Entrega $entrega)
    {
        abort_if($entrega->user_id !== $request->user()->id, 403);

        $entrega->update($request->validated());

        Cache::tags(["entregas.user.{$request->user()->id}"])->flush();

        return new EntregaResource($entrega);
    }

    public function destroy(Request $request, Entrega $entrega)
    {
        abort_if($entrega->user_id !== $request->user()->id, 403);

        $entrega->delete();

        Cache::tags(["entregas.user.{$request->user()->id}"])->flush();

        return response()->noContent();
    }
}
