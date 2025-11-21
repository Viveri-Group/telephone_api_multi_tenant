<?php

namespace App\Action\PhoneBook;

use App\Models\PhoneBookEntry;
use Illuminate\Support\Facades\Cache;

class GetPhoneBookEntriesAction
{
    public function handle()
    {
        return Cache::remember(
            'system_phone_book_entries',
            now()->addMinute(),
            fn() => PhoneBookEntry::all()
        );
    }
}
