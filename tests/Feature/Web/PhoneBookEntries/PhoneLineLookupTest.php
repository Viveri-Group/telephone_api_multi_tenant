<?php

namespace Tests\Feature\Web\PhoneBookEntries;

use App\Models\Competition;
use App\Models\PhoneBookEntry;
use Tests\TestCase;

class PhoneLineLookupTest extends TestCase
{
    public function test_phone_line_lookup_shows_no_competition()
    {
        $this->login();

        PhoneBookEntry::factory(['phone_number' => '555'])->create();
        Competition::factory()->hasPhoneLines(1, ['phone_number' => '555'])->create();

        $this->get(route('web.phone-book-entries.lookup', ['phoneBookEntry' => '555']))
            ->assertOk()
            ->assertSee('555');
    }

    public function test_page_works_when_no_data_is_passed()
    {
        $this->login();

        $this->get(route('web.phone-book-entries.lookup', ['phoneBookEntry' => '111']))
            ->assertNotFound();
    }
}
