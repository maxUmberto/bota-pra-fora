<?php

namespace App\Http\Controllers;

// Models
use App\Models\Vent;
use App\Models\VentComment;

// Requests
use App\Http\Requests\VentComments\CreateVentCommentRequest;

use Illuminate\Http\Request;

class VentCommentController extends Controller {
    
    public function createNewComment(Vent $vent, CreateVentCommentRequest $request) {
        VentComment::create([
            'comment_content' => $request->comment_content,
            'user_id'         => auth()->user()->id,
            'vent_id'         => $vent->id,
        ]);

        return response()->json([
            'success' => true,
        ], 200);
    }

}
