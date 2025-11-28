<?php

namespace Tests\Feature\API\Competition\Types;

use App\Models\Competition;
use App\Models\Organisation;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CompetitionWholeValidationTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
    }

    public function test_competition_validation()
    {
        $this->login();

        $this->post(route('competition.create'), [
        ])
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('message')
                    ->where('data.0.source', 'organisation_id')
                    ->where('data.1.source', 'name')
                    ->where('data.2.source', 'start')
                    ->where('data.3.source', 'end');
            });

        $this->assertCount(0, Competition::all());
    }

    public function test_can_create_a_competition()
    {
        Carbon::setTestNow(now()->setTime(01, 00));

        $this->login();

        $this->post(route('competition.create'), [
            'organisation_id' => $this->organisation->id,
            'name' => 'Test Competition',
            'start' => now()->setTime(15, 10),
            'end' => now()->setTime(15, 00)->addWeek(),
            'max_entries' => 5,
        ])
            ->assertCreated();

        $this->assertCount(1, Competition::all());
    }
}
