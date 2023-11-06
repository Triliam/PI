<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function index() {
        // return User::all();

        //verificar qnd aluno, qnd colaborador e fazer rotas diferentes
        return response()->json($this->user->all(), 200);
    }

    public function store(Request $request) {
        //adm cria/insere colaborador no sistema/no banco?

        //verifica se o email termina com @fatec.sp.gov.br
        //verifica se o email ja existe no banco - unique
        $request->validate($this->user->rules(), $this->user->feedback());
        $user = User::create([
            'name'=>$request->input('name'),
            'level'=>$request->input('level'),
            'email'=>$request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        return $user;
    }

    public function show($id) {
        $user = $this->user->find($id);
        if($user === null) {
            return response()->json(['erro' => 'Usuario não encontrado.'], 404);
        }
        return response()->json($user, 200);
    }

    //colocar hash no atualizar senha
    public function update(Request $request, $id) {
        $user = $this->user->find($id);
        // if($user === null) {
        //     return response()->json(['erro' => 'n possivel atualizae'], 404);
        // }

        $regrasDinamicas = array();

        //percorrer todas as regras definidas no Model
        foreach($user->rules() as $input => $regra) {
            //coletar apenas as regras aplicaveis aos paramentros parciais da requisicao (só o que quer atualizar)
            if(array_key_exists($input, $request->all())) {
                $regrasDinamicas[$input] = $regra;
            }
        }
        $request->validate($regrasDinamicas, $user->feedback());
        $user->update($request->all());
        return response()->json($user, 200);
    }

    public function destroy(User $user) {
        $user->delete();
        return response()->json(['sucess'=>true]);
    }

    public function getUsersWithLevelOne() {
        $users = User::where('level', 1)->get();
        return response()->json($users);
    }
}

