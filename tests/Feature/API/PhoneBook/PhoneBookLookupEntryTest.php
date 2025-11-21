<?php

namespace Tests\Feature\API\PhoneBook;

use App\Models\Competition;
use App\Models\PhoneBookEntry;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PhoneBookLookupEntryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        PhoneBookEntry::factory(['phone_number' => '441234567890'])->create();
    }

    public function test_that_it_shows_phone_number_associated_with_competition()
    {
        $competition = Competition::factory([
            'start' => now(),
            'end' => now()->addMinute(),
        ])->hasPhoneLines(1, ['phone_number' => '441234567890'])->create();

        $this->getJson(route('phone-book.lookup.entry', ['441234567890']))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($competition) {
                return $json
                    ->where('data.type', 'phone-book-lookup-entry')
                    ->where('data.phone_line.id', $competition->phoneLines()->first()->id)
                    ->where('data.phone_line.number', $competition->phoneLines()->first()->phone_number)
                    ->where('data.competition.id', $competition->id)
                    ->where('data.competition.name', $competition->name)
                    ->where('data.competition.start', $competition->start->toISOString())
                    ->where('data.competition.end', $competition->end->toISOString());
            });
    }

    public function test_that_it_shows_no_competition_set()
    {
        $this->getJson(route('phone-book.lookup.entry', ['441234567890']))
            ->assertOk()
            ->assertExactJson([]);
    }

    public function test_that_it_shows_multiple_competitions_set()
    {
        $compA = Competition::factory([
            'start' => now(),
            'end' => now()->addMinute(),
        ])->hasPhoneLines(1, ['phone_number' => '441234567890'])->create();

        $compB = Competition::factory([
            'start' => now(),
            'end' => now()->addMinute(),
        ])->hasPhoneLines(1, ['phone_number' => '441234567890'])->create();

        $this->getJson(route('phone-book.lookup.entry', ['441234567890']))
            ->assertConflict()
            ->assertJson(function (AssertableJson $json) use($compA, $compB){
                return $json
                    ->where('error', "Number 441234567890 points to multiple competitions: ({$compA->id},{$compB->id})");
            });

    }
}
