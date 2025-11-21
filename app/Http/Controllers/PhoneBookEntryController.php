<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneBookEntryRequest;
use App\Http\Resources\PhoneBookEntryResource;
use App\Models\PhoneBookEntry;

class PhoneBookEntryController extends Controller
{
    public function index()
    {
        return PhoneBookEntryResource::collection(PhoneBookEntry::all());
    }

    public function show(PhoneBookEntry $phoneBookEntry)
    {
        return new PhoneBookEntryResource($phoneBookEntry);
    }
}
