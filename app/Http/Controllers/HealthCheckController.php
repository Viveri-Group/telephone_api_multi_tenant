<?php

namespace App\Http\Controllers;

class HealthCheckController extends Controller
{
    public function __invoke()
    {
        return response()->json(['success' => true]);
    }
}
