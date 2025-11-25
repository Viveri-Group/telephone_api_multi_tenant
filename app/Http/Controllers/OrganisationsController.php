<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class OrganisationsController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'Auth/Organisations/Index',
            [
            ]
        );
    }
}
