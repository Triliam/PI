<?php

namespace App\Http\Controllers;

use App\Models\Pergunta;
use App\Models\Tema;
use App\Models\Icone;
use Illuminate\Http\Request;
use App\Repositories\PerguntaRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Resposta;
use App\Http\Controllers\RespostaController;

class PerguntaController extends Controller
{

    public function __construct(Pergunta $pergunta) {
        $this->pergunta = $pergunta;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $perguntaRepository = new PerguntaRepository($this->pergunta);

        if($request->has('filtro')){
            $perguntaRepository->filtro($request->filtro);
        }
        return response()->json($perguntaRepository->getResultado(), 200);
    }

    //metodo para mostrar para adm/colaborador - perguntas sugeridas por aluno
    // public function mostrarPerguntasSugeridas(Request $request)
    // {
    //     $perguntasSugeridas = Pergunta::get(['user_id', 'pergunta_sugerida']);
    //     return response()->json($perguntasSugeridas, 200);
    // }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        ////pro validade funcionar precisa implementar do lado do cliente: Accept - application/json - sem isso, vai retornar a rota raiz da aplicacao - a pagina do laravel
        $request->validate($this->pergunta->rules(), $this->pergunta->feedback());
        $pergunta = $this->pergunta->create([
            'tema_id' => $request->tema_id,
            'user_id' => $request->user_id,
            'pergunta' => $request->pergunta
        ]);
        return response()->json($pergunta, 201);
    }

    public function storeTogether(Request $request) {
        $pergunta = Pergunta::create([
            'tema_id' => $request->tema_id,
            'user_id' => $request->user_id,
            'pergunta' => $request->pergunta
        ]);

        Resposta::create([
            'pergunta_id' => $pergunta->id,
            'resposta' => $request->resposta
        ]);
        return response()->json('Pergunta e resposta cadastradas com sucesso!', 201);
    }

    //metodo para criar pergunta sugerida - aluno

    // public function criarPerguntaSugerida(Request $request)
    // {
    //     // $request->validate($this->pergunta->rules(), $this->pergunta->feedback());
    //     $perguntaSugerida = $this->pergunta->create([
    //         'user_id' => $request->user_id,
    //         'tema_id' => $request->tema_id,
    //         'pergunta_sugerida' => $request->pergunta_sugerida
    //     ]);
    //     return response()->json($perguntaSugerida, 201);
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pergunta  $pergunta
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $pergunta = $this->pergunta->find($id);
        if($pergunta === null) {
            return response()->json(['erro' => 'n existe'], 404);
        }
        return response()->json($pergunta, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pergunta  $pergunta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $pergunta = $this->pergunta->find($id);
        if($pergunta === null) {
            return response()->json(['erro' => 'Pergunta não existe.'], 404);
        }

        $request->validate($this->pergunta->rules(), $this->pergunta->feedback());

        $pergunta->update($request->all());
        return response()->json($pergunta, 200);
    }

    public function updateTogether(Request $request, $id) {

        $pergunta = Pergunta::findOrFail($id);
        $pergunta->pergunta = $request->input('pergunta');
        $pergunta->save();

        $resposta = Resposta::where('pergunta_id', $id)->first();
        $resposta->resposta = $request->input('resposta');
        $resposta->save();

        return response()->json("Pergunta e resposta atualizadas com sucesso!", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pergunta  $pergunta
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $pergunta = $this->pergunta->find($id);
        if($pergunta === null) {
            return response()->json(['erro' => 'Pergunta não existe.'], 404);
        }
        $pergunta->delete();
        return ['msg' => 'Pergunta removida'];
    }

    public function destroyTogether($id) {
        $pergunta = $this->pergunta->find($id);
        if($pergunta === null) {
            return response()->json(['erro' => 'Pergunta não existe.'], 404);
        }

        $resposta = Resposta::where('pergunta_id', $id)->first();
        $resposta->delete();
        $pergunta->delete();

        return ['msg' => 'Pergunta e resposta removidas.'];
    }



    public function getData() {
        $perguntas = Pergunta::with('tema', 'resposta')->get();
        $temas = Tema::all();
        $icones = Icone::all();

        return response()->json([
            'perguntas' => $perguntas,
            'temas' => $temas,
            'icones' => $icones,
        ]);
    }

    public function indexFaq() {
        $result = DB::table('temas')
        ->join('perguntas', 'temas.id', '=', 'perguntas.tema_id')
        ->join('respostas', 'perguntas.id', '=', 'respostas.pergunta_id')
        ->select('temas.tema', 'temas.icone', 'perguntas.id', 'perguntas.pergunta', 'respostas.resposta')
        ->get();

        return response()->json($result);
    }

    public function getDatas() {
        $perguntas = $this->indexFaq();
        $temas = Tema::all();
        $icones = Icone::all();

        return response()->json([
            'perguntas' => $perguntas,
            'temas' => $temas,
            'icones' => $icones,
        ]);
    }
}
