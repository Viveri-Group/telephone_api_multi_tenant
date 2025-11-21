<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DocsCallFlowController extends Controller
{
    public function __invoke()
    {
        return Inertia::render(
            'Auth/Docs/CallFlow',
            []
        );
    }
}
