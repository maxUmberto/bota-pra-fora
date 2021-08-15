<?php

namespace App\Http\Controllers;

// models
use App\Models\Vent;

// requests
use App\Http\Requests\Vents\CreateVentRequest;

use Illuminate\Http\Request;

class VentController extends Controller
{
    /**
     * Create a new vent to the logged user
     * 
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function createNewVent(CreateVentRequest $request) {
        $vent = Vent::create([
            'user_id'        => auth()->user()->id,
            'vent_content'   => $request->vent_content,
            'allow_comments' => $request->allow_comments
        ]);

        return response()->json([
            'success'  => true,
            'message' => 'Desabafo criado com sucesso'
        ], 200);
    }
}
