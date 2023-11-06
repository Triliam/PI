<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {

        $credenciais = $request->all(['email', 'password']);

        //autenticacao (email e senha)

        $token = auth('api')->attempt($credenciais);

        //usuario autenticado com sucesso
        if($token) {
            return response()->json(['token' => $token]);
            //erro de usuario ou senha
        } else {
            return response()->json(['erro' => 'Usuaário ou senha inválido!'], 403);
        }
        //403 = forbidden -> proibido (login invalido)
    }

    public function logout() {
        auth('api')->logout(); //cliente encaminhe um jwt valido
        return response()->json(['msg' => 'Logout realizado com sucesso!']);
    }

    public function refresh() {
        $token = auth('api')->refresh(); //cliente encaminhe um jwt valido
        return response()->json(['token' => $token]);
    }

    public function me() {
        return response()->json(auth()->user());
    }
    
    //gera o token por meio de usuario(no caso email) e senha
    //cliente armazena
    //cliente precisa implementar no headers: Key:Authorization, Value Bearer token gerado
}
