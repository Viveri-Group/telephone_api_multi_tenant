<?php

namespace App\Http\Controllers;

use App\Models\ActiveCall;
use Exception;

class DBHealthCheckController extends Controller
{
    public function __invoke()
    {
        try {
            ActiveCall::count();

            return response()->json('databaseup');

        } catch (Exception $e) {
            return response()->json([], 500);
        }
    }
}
