<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//mostra a pergunta sugerida pelo aluno e o id do aluno
//Route::get("perguntasugerida", "App\Http\Controllers\PerguntaController@mostrarPerguntasSugeridas");

    // Route::apiResource('user', 'App\Http\Controllers\UserController');
    // Route::apiResource('tema', 'App\Http\Controllers\TemaController');
    // Route::apiResource('pergunta', 'App\Http\Controllers\PerguntaController');
    // Route::apiResource('resposta', 'App\Http\Controllers\RespostaController');

// });


//ROTAS PUBLICAS


    Route::get('3.1', "App\Http\Controllers\PerguntaController@getDatas");
    Route::get('3', "App\Http\Controllers\PerguntaController@getData");

    Route::get("faqs", "App\Http\Controllers\FaqsController@index");

    Route::get("user", "App\Http\Controllers\UserController@index");
    Route::get("user/{user}", "App\Http\Controllers\UserController@show");
    Route::post("user", "App\Http\Controllers\UserController@store");


    Route::get("tema", "App\Http\Controllers\TemaController@index");
    Route::get("tema/{tema}", "App\Http\Controllers\TemaController@show");

    Route::get("pergunta", "App\Http\Controllers\PerguntaController@index");
    Route::get("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@show");

    Route::get("resposta", "App\Http\Controllers\RespostaController@index");
    Route::get("resposta/{resposta}", "App\Http\Controllers\RespostaController@show");

    Route::post('login', 'App\Http\Controllers\AuthController@login');

    Route::post("pr", "App\Http\Controllers\PerguntaController@storeTogether");


//Prefix pra l0 para adm, l1 colab, l2 aluno

//ROTAS ADM
Route::prefix('l2')->middleware('jwt.auth')->group(function() {

    Route::get("users", "App\Http\Controllers\UserController@getUsersWithLevelOne");

    Route::get("icones", "App\Http\Controllers\IconeController@index");
    Route::post("icones", "App\Http\Controllers\IconeController@store");
    Route::patch("icones", "App\Http\Controllers\IconeController@update");

    Route::post("pr", "App\Http\Controllers\PerguntaController@storeTogether");
    Route::patch("updatepr/{pergunta}", "App\Http\Controllers\PerguntaController@updateTogether");

    Route::post("user", "App\Http\Controllers\UserController@store");
    Route::patch("user/{user}", "App\Http\Controllers\UserController@update");
    Route::put("user/{user}", "App\Http\Controllers\UserController@update");
    Route::delete("user/{user}", "App\Http\Controllers\UserController@destroy");
    Route::get("users", "App\Http\Controllers\UserController@getUsersWithLevelOne");

    Route::post("tema", "App\Http\Controllers\TemaController@store");
    Route::patch("tema/{tema}", "App\Http\Controllers\TemaController@update");
    Route::put("tema/{tema}", "App\Http\Controllers\TemaController@update");
    Route::delete("tema/{tema}", "App\Http\Controllers\TemaController@destroy");

    Route::post("pergunta", "App\Http\Controllers\PerguntaController@store");
    Route::patch("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@update");
    Route::put("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@update");
    Route::delete("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@destroy");

    Route::post("resposta", "App\Http\Controllers\RespostaController@store");
    Route::patch("resposta/{resposta}", "App\Http\Controllers\RespostaController@update");
    Route::put("resposta/{resposta}", "App\Http\Controllers\RespostaController@update");
    Route::delete("resposta/{resposta}", "App\Http\Controllers\RespostaController@destroy");

    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
});


    //dando ruim
    Route::patch("updatepr/{pergunta}", "App\Http\Controllers\PerguntaController@updateTogether");

    //ROTAS COLABORADORES
    Route::prefix('l1')->middleware('jwt.auth')->group(function() {

        Route::post("user", "App\Http\Controllers\UserController@store");
        Route::patch("user/{user}", "App\Http\Controllers\UserController@update");
        Route::put("user/{user}", "App\Http\Controllers\UserController@update");
        Route::delete("user/{user}", "App\Http\Controllers\UserController@destroy");
        Route::get("users", "App\Http\Controllers\UserController@getUsersWithLevelOne");

        Route::post("tema", "App\Http\Controllers\TemaController@store");
        Route::patch("tema/{tema}", "App\Http\Controllers\TemaController@update");
        Route::put("tema/{tema}", "App\Http\Controllers\TemaController@update");
        Route::delete("tema/{tema}", "App\Http\Controllers\TemaController@destroy");

        Route::post("pr", "App\Http\Controllers\PerguntaController@storeTogether");
        Route::patch("updatepr/{pergunta}", "App\Http\Controllers\PerguntaController@updateTogether");
        Route::delete("delpr/{pergunta}", "App\Http\Controllers\PerguntaController@destroyTogether");

        Route::post("pergunta", "App\Http\Controllers\PerguntaController@store");
        Route::patch("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@update");
        Route::put("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@update");
        Route::delete("pergunta/{pergunta}", "App\Http\Controllers\PerguntaController@destroy");

        Route::post("resposta", "App\Http\Controllers\RespostaController@store");
        Route::patch("resposta/{resposta}", "App\Http\Controllers\RespostaController@update");
        Route::put("resposta/{resposta}", "App\Http\Controllers\RespostaController@update");
        Route::delete("resposta/{resposta}", "App\Http\Controllers\RespostaController@destroy");

        Route::post('logout', 'App\Http\Controllers\AuthController@logout');
        Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
        Route::post('me', 'App\Http\Controllers\AuthController@me');

    });

    //ROTAS ALUNOS
    Route::prefix('l0')->middleware('jwt.auth')->group(function() {

        Route::post("pergunta", "App\Http\Controllers\PerguntaController@store");

    });
