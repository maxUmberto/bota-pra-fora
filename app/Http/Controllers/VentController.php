<?php

namespace App\Http\Controllers;

// models
use App\Models\Vent;

use Illuminate\Http\Request;

class VentController extends Controller
{
    public function createNewVent(Request $request) {
        $logged_user = auth()->user();

        $validated = $request->validate([
            'vent_content'   => 'required|max:500',
            'allow_comments' => 'required|boolean',
        ]);

        $vent = Vent::create([
            'user_id'        => $logged_user->id,
            'vent_content'   => $request->vent_content,
            'allow_comments' => $request->allow_comments
        ]);

        return response()->json([
            'success'  => true,
            'message' => 'Desabafo criado com sucesso'
        ], 200);
    }
}
