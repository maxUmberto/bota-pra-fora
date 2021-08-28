<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\Vent;
use App\Models\Reaction;

class VentReactionController extends Controller {
    
    /**
     * Keep toggling the user reaction to the vent. If he hadnt the given reaction
     * to the vent, save it. If he already has, delete it
     * 
     * @param App\Models\Vent $vent The vent the user is reacting to
     * @param App\Models\Reaction $reaction The reaction the user is giving to the vent
     * 
     * @return \Illuminate\Http\Response;
     */
    public function reactToAVent(Vent $vent, Reaction $reaction) {

        $user_reactions_to_the_vent = $vent::with(['reactions' => function($query) use ($reaction) {
            $query->where('user_id', auth()->user()->id);
        }])->first();

        if ($user_reactions_to_the_vent->reactions->contains($reaction->id)) {
            $vent->reactions()->detach($reaction->id, ['user_id' => auth()->user()->id]);
        }
        else {
            $vent->reactions()->attach($reaction->id, ['user_id' => auth()->user()->id]);
        }

        return response()->json([
            'success' => true,
        ], 200);

    }

}
