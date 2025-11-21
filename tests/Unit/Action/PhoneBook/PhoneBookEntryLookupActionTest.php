<?php

namespace Tests\Unit\Action\PhoneBook;

use App\Action\PhoneBook\PhoneBookLookupAction;
use App\Models\PhoneBookEntry;
use Tests\TestCase;

class PhoneBookEntryLookupActionTest extends TestCase
{
    public function test_entry_is_found()
    {
        $this->login();

        PhoneBookEntry::factory()->create(['phone_number' => '441111112222']);
        PhoneBookEntry::factory()->create(['phone_number' => '443333333333']);

        $this->assertInstanceOf(PhoneBookEntry::class, (new PhoneBookLookupAction())->handle('441111112222'));
        $this->assertInstanceOf(PhoneBookEntry::class, (new PhoneBookLookupAction())->handle('443333333333'));
    }

    public function test_entry_does_not_exist()
    {
        $this->login();

        $this->assertNull((new PhoneBookLookupAction())->handle('441111112222'));
    }
}
