<?php

namespace App\Http\Controllers;

// models
use App\Models\Vent;

// requests
use App\Http\Requests\Vents\CreateVentRequest;
use App\Http\Requests\Vents\LoadVentInfoRequest;

use Illuminate\Http\Request;

class VentController extends Controller
{
    /**
     * Create a new vent to the logged user
     * 
     * @param App\Http\Requests\Vents\CreateVentRequest $request
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

    /**
     * Load all the vents of the logged user
     * 
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function loadUserVents(Request $request) {
        $vents = auth()->user()->vents;

        return response()->json([
            'success' => true,
            'vents' => $vents
        ], 200);
    }

    /**
     * Load all the infos about a given vent
     * 
     * @param App\Http\Requests\Vents\LoadVentInfoRequest $request
     * @param App\Models\Vent $vent
     * 
     * @return \Illuminate\Http\Response
     */
    public function loadVentInfo(LoadVentInfoRequest $request, Vent $vent) {
        $vent->load('reactions', 'ventComments')
            ->loadCount('ventViews');

        return response()->json([
            'success' => true,
            'vent'    => $vent
        ], 200);
    }
}
