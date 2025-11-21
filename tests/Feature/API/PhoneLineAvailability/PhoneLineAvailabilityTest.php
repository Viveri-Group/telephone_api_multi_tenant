<?php

namespace Tests\Feature\API\PhoneLineAvailability;

use App\Models\ActiveCall;
use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use App\Models\Participant;
use App\Models\PhoneBookEntry;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PhoneLineAvailabilityTest extends TestCase
{
    public function test_returns_all_phone_book_entries_when_none_are_used()
    {
        $this->login();

        $entries = collect(range(1, 3))->map(function ($i) {
            return PhoneBookEntry::factory()->create([
                'phone_number' => '080000000' . $i,
            ]);
        });

        $response = $this->postJson(route('phone-line.availability'));

        $response->assertOk();
        $response->assertJsonCount(3, 'available_phone_numbers');
        $response->assertExactJson([
            'available_phone_numbers' => $entries->pluck('phone_number')->all(),
        ]);
    }

    public function test_excludes_used_phone_numbers_in_competition_phone_lines()
    {
        $this->login();

        $available = PhoneBookEntry::factory()->create([
            'phone_number' => '0800000001',
        ]);

        $used = PhoneBookEntry::factory()->create([
            'phone_number' => '0800000002',
        ]);

        Competition::factory()->create();

        CompetitionPhoneLine::factory()->create([
            'phone_number' => $used->phone_number,
        ]);

        $response = $this->postJson(route('phone-line.availability'));

        $response->assertOk();
        $response->assertJsonCount(1, 'available_phone_numbers');
        $response->assertJson([
            'available_phone_numbers' => [$available->phone_number],
        ]);
    }

    public function test_returns_empty_array_when_all_numbers_are_used()
    {
        $this->login();

        $numbers = ['0800000010', '0800000011'];

        foreach ($numbers as $number) {
            PhoneBookEntry::factory()->create([
                'phone_number' => $number,
            ]);

            CompetitionPhoneLine::factory()->create([
                'phone_number' => $number,
            ]);
        }

        $response = $this->postJson(route('phone-line.availability'));

        $response->assertOk();
        $response->assertJson([
            'available_phone_numbers' => [],
        ]);
    }

    public function test_returns_empty_array_when_no_phone_book_entries_exist()
    {
        $this->login();

        $response = $this->postJson(route('phone-line.availability'));

        $response->assertOk();
        $response->assertJson([
            'available_phone_numbers' => [],
        ]);
    }
}
