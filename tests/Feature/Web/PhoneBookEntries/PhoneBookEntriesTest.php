<?php

namespace Tests\Feature\Web\PhoneBookEntries;

use App\Models\PhoneBookEntry;
use Tests\TestCase;

class PhoneBookEntriesTest extends TestCase
{
    public function test_phone_book_entries_index_page_shows_all_phone_book_numbers()
    {
        $this->login();

        PhoneBookEntry::factory(['phone_number' => '555'])->create();

        $this->get(route('web.phone-book-entries.index'))
            ->assertOk()
            ->assertSee('555');
    }
}
