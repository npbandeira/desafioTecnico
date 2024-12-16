<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Http\Resources\UsuarioResource;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = Usuario::all();
        return response()->json(UsuarioResource::collection($users), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUsuarioRequest $request)
    {
        // Criação do usuário
        $usuario = Usuario::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => $request->password
        ]);

        return response()->json(new UsuarioResource($usuario), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        return response()->json(new UsuarioResource($usuario), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        // Atualização dos dados
        $usuario->fill($request->only('name', 'email', 'cpf', 'password'));
        // Verifica se algo mudou antes de salvar
        if ($usuario->isDirty()) {
            $usuario->save();
        }
        return response()->json(new UsuarioResource($usuario), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

}
