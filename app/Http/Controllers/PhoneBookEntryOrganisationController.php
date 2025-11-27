<?php

namespace App\Http\Controllers;

use App\Http\Resources\PhoneBookEntryResource;
use App\Models\Organisation;

class PhoneBookEntryOrganisationController extends Controller
{
    public function index(Organisation $organisation)
    {
        return PhoneBookEntryResource::collection($organisation->phoneBookEntries);
    }
}
