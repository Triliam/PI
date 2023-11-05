<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Icone;

class IconeController extends Controller
{

    public function __construct(Icone $icone) {
        $this->icone = $icone;
    }

    public function index() {
        // return Icone::all();

        return response()->json($this->icone->all(), 200);
    }

    public function store(Request $request)
    {
        $icone = $this->icone->create([
            'user_id' => $request->user_id,
            'icone' => $request->icone
        ]);
        return response()->json($icone, 201);
    }
}
