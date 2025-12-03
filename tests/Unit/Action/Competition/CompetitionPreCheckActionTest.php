<?php

namespace Tests\Unit\Action\Competition;

use App\Action\Competition\CompetitionPreCheckAction;
use App\DTO\Competition\CompetitionPreCheckRequestDTO;
use App\Exceptions\CallerExceededMaxEntriesHTTPException;
use App\Exceptions\CompetitionClosedHTTPException;
use App\Exceptions\NoActiveCompetitionsHTTPException;
use App\Exceptions\PhoneBookEntryMissingHTTPException;
use App\Http\Resources\CompetitionCapacityCheckWithActivePhoneLineResource;
use App\Models\ActiveCall;
use App\Models\Competition;
use App\Models\CompetitionPhoneLine;
use App\Models\EntrantRoundCount;
use App\Models\Organisation;
use App\Models\PhoneBookEntry;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class CompetitionPreCheckActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Bus::fake();

        $this->setFileDefaults();

        $this->login();

        $this->organisation = Organisation::factory()->create();

        $this->competitionNumber = '0333456555';

        PhoneBookEntry::factory(['phone_number' => $this->competitionNumber, 'organisation_id' => $this->organisation->id])->create();

        $this->competition = Competition::factory(['start' => now()->subDays(2), 'end' => now()->addDay(), 'max_entries' => 4, 'organisation_id' => $this->organisation->id])
            ->hasPhoneLines(['phone_number' => $this->competitionNumber, 'organisation_id' => $this->organisation->id])
            ->create();

        $this->phoneline = CompetitionPhoneLine::first();
        $this->callerNumber = '441604464237';
    }

    public function test_success()
    {
        EntrantRoundCount::create(
            [
                'hash' => hash('xxh128', "{$this->competition->start} {$this->competition->id} 441604464237"),
                'competition_id' => $this->competition->id,
                'caller_number' => $this->callerNumber,
                'total_entry_count' => 2,
            ]
        );

        $response = (new CompetitionPreCheckAction())->handle(
            new CompetitionPreCheckRequestDTO(
                $this->callerNumber,
                $this->competitionNumber,
                1234
            ),
            $this->phoneline,
            CompetitionCapacityCheckWithActivePhoneLineResource::class,
            true
        );

        $this->assertCount(1, $activeCalls = ActiveCall::all());

        $activeCall = $activeCalls->first();

        tap($activeCall, function (ActiveCall $activeCall) {
            $this->assertSame($this->competition->id, $activeCall->competition_id);
            $this->assertSame($this->organisation->id, $activeCall->organisation_id);
            $this->assertSame($this->competitionNumber, $activeCall->phone_number);
            $this->assertSame($this->callerNumber, $activeCall->caller_phone_number);
            $this->assertSame(1234, $activeCall->call_id);
            $this->assertNotNull($activeCall->round_start);
            $this->assertNotNull($activeCall->round_end);
        });

        $this->assertTrue($response instanceof CompetitionCapacityCheckWithActivePhoneLineResource);

        tap($response, function (CompetitionCapacityCheckWithActivePhoneLineResource $resource) use($activeCall){
            $this->assertSame('OPEN', $resource->parameters['status']);
            $this->assertSame($activeCall->id, $resource->parameters['active_call_id']);
            $this->assertSame(false, $resource->parameters['sms_offer_enabled']);
            $this->assertSame(2, $resource->parameters['entry_count']);
        });
    }

    public function test_407_is_returned()
    {
        try {
            (new CompetitionPreCheckAction())->handle(
                new CompetitionPreCheckRequestDTO(
                    $this->callerNumber,
                    '441111111111',
                    1234
                ),
                $this->phoneline,
                CompetitionCapacityCheckWithActivePhoneLineResource::class
            );

            $this->fail('Expected exception was not thrown');
        } catch (PhoneBookEntryMissingHTTPException $e) {
            $this->assertEquals(407, $e->getCode());
            $this->assertStringContainsString('No Phone Book entry exists.', $e->getMessage());
        }
    }

    public function test_400_is_returned()
    {
        try {
            (new CompetitionPreCheckAction())->handle(
                new CompetitionPreCheckRequestDTO(
                    $this->callerNumber,
                    $this->competitionNumber,
                    1234
                ),
                null,
                CompetitionCapacityCheckWithActivePhoneLineResource::class
            );

            $this->fail('Expected exception was not thrown');
        } catch (NoActiveCompetitionsHTTPException $e) {
            $this->assertEquals(400, $e->getCode());
            $this->assertStringContainsString('No competitions associated with this phone line.', $e->getMessage());
        }
    }

    public function test_caller_exceeds_max_calls_406_is_returned()
    {
        EntrantRoundCount::create(
            [
                'hash' => hash('xxh128', "{$this->competition->start} {$this->competition->id} 441604464237"),
                'competition_id' => $this->competition->id,
                'caller_number' => $this->callerNumber,
                'total_entry_count' => 5,
            ]
        );

        try {
            (new CompetitionPreCheckAction())->handle(
                new CompetitionPreCheckRequestDTO(
                    $this->callerNumber,
                    $this->competitionNumber,
                    1234
                ),
                $this->phoneline,
                CompetitionCapacityCheckWithActivePhoneLineResource::class,
                true
            );

            $this->assertCount(1, $activeCalls = ActiveCall::all());

            tap($activeCalls->first(), function (ActiveCall $activeCall) {
                $this->assertSame($this->competition->id, $activeCall->competition_id);
                $this->assertSame($this->organisation->id, $activeCall->organisation_id);
                $this->assertSame($this->competitionNumber, $activeCall->phone_number);
                $this->assertSame($this->callerNumber, $activeCall->caller_phone_number);
                $this->assertSame(1234, $activeCall->call_id);
                $this->assertNotNull($activeCall->round_start);
                $this->assertNotNull($activeCall->round_end);
            });
            $this->fail('Expected exception was not thrown');
        } catch (CallerExceededMaxEntriesHTTPException $e) {
            $this->assertEquals(406, $e->getCode());
            $this->assertStringContainsString('Participant has exceeded allowed number of entries.', $e->getMessage());
        }
    }

    public function test_competition_is_closed_returns_200()
    {
        $this->competition->update(['end' => now()->subDay()]);

        try {
            (new CompetitionPreCheckAction())->handle(
                new CompetitionPreCheckRequestDTO(
                    $this->callerNumber,
                    $this->competitionNumber,
                    1234
                ),
                $this->phoneline,
                CompetitionCapacityCheckWithActivePhoneLineResource::class
            );

            $this->fail('Expected exception was not thrown');
        } catch (CompetitionClosedHTTPException $e) {
            $this->assertEquals(200, $e->getCode());
            $this->assertStringContainsString('Competition is closed.', $e->getMessage());
        }
    }
}
