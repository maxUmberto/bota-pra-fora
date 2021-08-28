<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\Vent;
use App\Models\Reaction;

class VentReactionController extends Controller {
    
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
