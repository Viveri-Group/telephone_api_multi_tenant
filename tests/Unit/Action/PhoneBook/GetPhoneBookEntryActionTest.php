<?php

namespace Tests\Unit\Action\PhoneBook;

use App\Action\PhoneBook\GetPhoneBookEntriesAction;
use App\Action\PhoneBook\PhoneBookEntryExistsAction;
use App\Models\PhoneBookEntry;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GetPhoneBookEntryActionTest extends TestCase
{
    public function test_getter_works_as_expected()
    {
        $this->login();

        PhoneBookEntry::factory()->create(['phone_number' => '441111112222', 'name' => 'foo']);
        PhoneBookEntry::factory()->create(['phone_number' => '443333333333', 'name' => 'bar']);

        $entries = (new GetPhoneBookEntriesAction())->handle();

        tap($entries, function(Collection $entries) {
            $this->assertCount(2, $entries);

            $entryOne = $entries->get(0);
            $this->assertSame('441111112222', $entryOne->phone_number);
            $this->assertSame('foo', $entryOne->name);

            $entryTwo = $entries->get(1);
            $this->assertSame('443333333333', $entryTwo->phone_number);
            $this->assertSame('bar', $entryTwo->name);
        });
    }

    public function test_empty_phone_book()
    {
        $this->login();

        $entries = (new GetPhoneBookEntriesAction())->handle();

        $this->assertCount(0, $entries);
    }
}
