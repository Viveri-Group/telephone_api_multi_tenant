<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class APIHealthCheckController extends Controller
{
    public function __invoke()
    {
        $dbIsOperational = rescue(fn() => DB::select('SELECT 1'), false);

        return response()->json(['success' => (bool)$dbIsOperational]);
    }
}
