<?php

namespace Feature\API\ClearDown;

use App\Jobs\HandleCompetitionClearDownSuccessJob;
use App\Jobs\HandleCompetitionFailClearDownJob;
use App\Models\ActiveCall;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ClearDownTest extends TestCase
{
    public function test_status_of_success_and_sms_offer_true()
    {
        Bus::fake();

        $this->login();

        $activeCall = ActiveCall::factory([
            'call_id' => '1234',
            'caller_phone_number' => '01604123456',
            'created_at' => '2025-01-01 09:00:00'
        ])->hasCompetitionPhoneLine()->create();

        $this->post(route('active-call.clear-down', $activeCall), [
            'type' => 'success',
            'sms_offer_accepted' => true,
        ])->assertOk();

        $this->assertCount(0, ActiveCall::all());

        Bus::assertDispatched(HandleCompetitionClearDownSuccessJob::class, function ($job) use ($activeCall) {
            $this->assertTrue($job->smsOfferAccepted);

            $this->assertSame($activeCall->id, $job->activeCallDTO->id);
            $this->assertSame($activeCall->competition_id, $job->activeCallDTO->competition_id);
            $this->assertEquals('1234', $job->activeCallDTO->call_id);
            $this->assertSame($activeCall->phone_number, $job->activeCallDTO->competition_phone_number);
            $this->assertSame($activeCall->caller_phone_number, $job->activeCallDTO->caller_phone_number);
            $this->assertSame('2025-01-01 09:00:00', $job->activeCallDTO->created_at);
            $this->assertNotNull($job->activeCallDTO->call_end);

            return true;
        });

        Bus::assertNotDispatched(HandleCompetitionFailClearDownJob::class);
    }

    public function test_status_of_success_and_sms_offer_with_string_true()
    {
        Bus::fake();

        $this->login();

        $activeCall = ActiveCall::factory([
            'call_id' => '1234',
            'caller_phone_number' => '01604123456',
            'created_at' => '2025-01-01 09:00:00'
        ])->hasCompetitionPhoneLine()->create();

        $this->post(route('active-call.clear-down', $activeCall), [
            'type' => 'success',
            'sms_offer_accepted' => 'true',
        ])->assertOk();

        $this->assertCount(0, ActiveCall::all());

        Bus::assertDispatched(HandleCompetitionClearDownSuccessJob::class, function ($job) use ($activeCall) {
            $this->assertTrue($job->smsOfferAccepted);

            $this->assertSame($activeCall->id, $job->activeCallDTO->id);
            $this->assertSame($activeCall->competition_id, $job->activeCallDTO->competition_id);
            $this->assertEquals('1234', $job->activeCallDTO->call_id);
            $this->assertSame($activeCall->phone_number, $job->activeCallDTO->competition_phone_number);
            $this->assertSame($activeCall->caller_phone_number, $job->activeCallDTO->caller_phone_number);
            $this->assertSame('2025-01-01 09:00:00', $job->activeCallDTO->created_at);
            $this->assertNotNull($job->activeCallDTO->call_end);

            return true;
        });

        Bus::assertNotDispatched(HandleCompetitionFailClearDownJob::class);
    }

    public function test_status_of_success_and_sms_offer_false()
    {
        Bus::fake();

        $this->login();

        $activeCall = ActiveCall::factory([
            'call_id' => '1234',
            'caller_phone_number' => '01604123456',
            'created_at' => '2025-01-01 09:00:00'
        ])->hasCompetitionPhoneLine()->create();

        $this->post(route('active-call.clear-down', $activeCall), [
            'type' => 'success',
            'sms_offer_accepted' => false,
        ])->assertOk();

        $this->assertCount(0, ActiveCall::all());

        Bus::assertDispatched(HandleCompetitionClearDownSuccessJob::class, function ($job) use ($activeCall) {
            $this->assertFalse($job->smsOfferAccepted);

            $this->assertSame($activeCall->id, $job->activeCallDTO->id);
            $this->assertSame($activeCall->competition_id, $job->activeCallDTO->competition_id);
            $this->assertEquals('1234', $job->activeCallDTO->call_id);
            $this->assertSame($activeCall->phone_number, $job->activeCallDTO->competition_phone_number);
            $this->assertSame($activeCall->caller_phone_number, $job->activeCallDTO->caller_phone_number);
            $this->assertSame('2025-01-01 09:00:00', $job->activeCallDTO->created_at);
            $this->assertNotNull($job->activeCallDTO->call_end);

            return true;
        });

        Bus::assertNotDispatched(HandleCompetitionFailClearDownJob::class);
    }

    public function test_status_of_success_and_sms_offer_with_string_false()
    {
        Bus::fake();

        $this->login();

        $activeCall = ActiveCall::factory([
            'call_id' => '1234',
            'caller_phone_number' => '01604123456',
            'created_at' => '2025-01-01 09:00:00'
        ])->hasCompetitionPhoneLine()->create();

        $this->post(route('active-call.clear-down', $activeCall), [
            'type' => 'success',
            'sms_offer_accepted' => 'false',
        ])->assertOk();

        $this->assertCount(0, ActiveCall::all());

        Bus::assertDispatched(HandleCompetitionClearDownSuccessJob::class, function ($job) use ($activeCall) {
            $this->assertFalse($job->smsOfferAccepted);

            $this->assertSame($activeCall->id, $job->activeCallDTO->id);
            $this->assertSame($activeCall->competition_id, $job->activeCallDTO->competition_id);
            $this->assertEquals('1234', $job->activeCallDTO->call_id);
            $this->assertSame($activeCall->phone_number, $job->activeCallDTO->competition_phone_number);
            $this->assertSame($activeCall->caller_phone_number, $job->activeCallDTO->caller_phone_number);
            $this->assertSame('2025-01-01 09:00:00', $job->activeCallDTO->created_at);
            $this->assertNotNull($job->activeCallDTO->call_end);

            return true;
        });

        Bus::assertNotDispatched(HandleCompetitionFailClearDownJob::class);
    }

    public function test_status_of_success_and_sms_offer_not_set()
    {
        Bus::fake();

        $this->login();

        $activeCall = ActiveCall::factory([
            'call_id' => '1234',
            'caller_phone_number' => '01604123456',
            'created_at' => '2025-01-01 09:00:00'
        ])->hasCompetitionPhoneLine()->create();

        $this->post(route('active-call.clear-down', $activeCall), [
            'type' => 'success',
        ])->assertOk();

        $this->assertCount(0, ActiveCall::all());

        Bus::assertDispatched(HandleCompetitionClearDownSuccessJob::class, function ($job) use ($activeCall) {
            $this->assertFalse($job->smsOfferAccepted);

            $this->assertSame($activeCall->id, $job->activeCallDTO->id);
            $this->assertSame($activeCall->competition_id, $job->activeCallDTO->competition_id);
            $this->assertEquals('1234', $job->activeCallDTO->call_id);
            $this->assertSame($activeCall->phone_number, $job->activeCallDTO->competition_phone_number);
            $this->assertSame($activeCall->caller_phone_number, $job->activeCallDTO->caller_phone_number);
            $this->assertSame('2025-01-01 09:00:00', $job->activeCallDTO->created_at);
            $this->assertNotNull($job->activeCallDTO->call_end);

            return true;
        });

        Bus::assertNotDispatched(HandleCompetitionFailClearDownJob::class);
    }

    public function test_clear_down_on_closed_unknown_competition()
    {
        Carbon::setTestNow('2025-01-01 09:00:00');

        $this->login();

        $activeCall = ActiveCall::factory([
            'competition_id' => null,
            'call_id' => '1234',
            'competition_phone_line_id' => null,
            'phone_number' => '0333456555',
            'caller_phone_number' => '441604123456',
            'status' => 'CLOSED',
            'round_start' => null,
            'round_end' => null,
            'call_end' => null,
        ])->create();

        $this->post(route('active-call.clear-down', $activeCall), [
            'type' => 'fail',
            'reason' => 'foo'
        ])->assertOk();
    }

    public function test_status_of_fail()
    {
        Bus::fake();

        $this->login();

        $activeCall = ActiveCall::factory([
            'call_id' => '1234',
            'caller_phone_number' => '01604123456',
            'created_at' => '2025-01-01 09:00:00'
        ])->hasCompetitionPhoneLine()->create();

        $this->post(route('active-call.clear-down', $activeCall), ['type' => 'fail', 'reason' => 'failedFoo'])
            ->assertOk();

        $this->assertCount(0, ActiveCall::all());

        Bus::assertDispatched(HandleCompetitionFailClearDownJob::class, function (HandleCompetitionFailClearDownJob $job) use ($activeCall) {
            $this->assertSame('failedFoo', $job->reason);

            $this->assertSame($activeCall->id, $job->activeCallDTO->id);
            $this->assertSame($activeCall->competition_id, $job->activeCallDTO->competition_id);
            $this->assertEquals('1234', $job->activeCallDTO->call_id);
            $this->assertSame($activeCall->phone_number, $job->activeCallDTO->competition_phone_number);
            $this->assertSame($activeCall->caller_phone_number, $job->activeCallDTO->caller_phone_number);
            $this->assertSame('2025-01-01 09:00:00', $job->activeCallDTO->created_at);
            $this->assertNotNull($job->activeCallDTO->call_end);

            return true;
        });

        Bus::assertNotDispatched(HandleCompetitionClearDownSuccessJob::class);
    }

    public function test_validation()
    {
        $this->login();

        $activeCall = ActiveCall::factory()->hasCompetitionPhoneLine()->create();

        $this->post(route('active-call.clear-down', $activeCall))
            ->assertUnprocessable()
            ->assertJson(function (AssertableJson $json) {
                return $json
                    ->has('message')
                    ->where('data.0.source', 'type');
            });
    }
}
