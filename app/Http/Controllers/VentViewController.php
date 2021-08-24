<?php

namespace App\Http\Controllers;

// models
use App\Models\Vent;
use App\Models\VentView;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class VentViewController extends Controller {
    
    public function getRandomVent() {
        $vent = Vent::whereDoesntHave('ventViews', function(Builder $query) {
            $query->where('user_id', auth()->user()->id);
        })
        ->limit(1)
        ->inRandomOrder()
        ->first();

        if(is_null($vent)) {
            return response()->json([
                'success' => true,
                'message' => 'Você já visualizou todos os desabafos disponíveis',
                'vent'    => null
            ], 200);
        }
        
        VentView::create([
            'user_id' => auth()->user()->id,
            'vent_id' => $vent->id
        ]);

        return response()->json([
            'success' => true,
            'vent'    => $vent->makeHidden('user_id')
        ], 200);
    }

}
