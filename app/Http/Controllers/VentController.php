<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VentController extends Controller
{
    public function createNewVent() {
        return response()->json([], 204);
    }
}
