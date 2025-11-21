<?php

namespace Tests\Feature\API\PhoneBook;

use App\Models\PhoneBookEntry;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PhoneBookEntryTest extends TestCase
{
    public function test_can_get_all_phone_book_entries()
    {
        $this->login();

        $phoneBookEntryA = PhoneBookEntry::factory(['phone_number'=>'443331310150', 'name' => 'foo'])->create();
        $phoneBookEntryB = PhoneBookEntry::factory(['phone_number'=>'443331310999', 'name' => 'bar'])->create();

        $this->get(route('phone-book.entry.index'))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use($phoneBookEntryA, $phoneBookEntryB) {
                return $json
                    ->where('data.0.type', 'phone-book-entry')
                    ->where('data.0.id', $phoneBookEntryA->id)
                    ->where('data.0.attributes.phone_number', '443331310150')
                    ->where('data.0.attributes.name', 'foo')
                    ->where('data.1.type', 'phone-book-entry')
                    ->where('data.1.id', $phoneBookEntryB->id)
                    ->where('data.1.attributes.phone_number', '443331310999')
                    ->where('data.1.attributes.name', 'bar');
            });
    }

    public function test_can_get_a_phone_book_entry()
    {
        $this->login();

        $phoneBookEntry = PhoneBookEntry::factory(['phone_number'=>'443331310150', 'name' => 'baz'])->create();

        $this->get(route('phone-book.entry.show', $phoneBookEntry))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use($phoneBookEntry) {
                return $json
                    ->where('data.type', 'phone-book-entry')
                    ->where('data.id', $phoneBookEntry->id)
                    ->where('data.attributes.phone_number', '443331310150')
                    ->where('data.attributes.name', 'baz');

            });
    }

    public function test_not_found_phone_book_entry()
    {
        $this->login();

        $this->get(route('phone-book.entry.show', 5))
            ->assertNotFound();
    }
}
