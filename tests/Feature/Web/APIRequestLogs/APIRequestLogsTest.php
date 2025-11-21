<?php

namespace Tests\Feature\Web\APIRequestLogs;

use App\Models\ActiveCallOrphan;
use App\Models\APIRequestLog;
use App\Models\MaxCapacityCallLog;
use Tests\TestCase;

class APIRequestLogsTest extends TestCase
{
    public function test_page_redirects_as_expected()
    {
        $this->login();

        $this->get(route('web.api-request-logs.index'))
            ->assertStatus(302);
    }

    public function test_page_shows_as_expected()
    {
        $this->login();

        $requestLog = APIRequestLog::factory()->create();

        $this->get(route('web.api-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s')
            ]))
            ->assertOk()
            ->assertSee($requestLog->uuid);
    }

    public function test_summary_page_shows_as_expected()
    {
        $this->login();

        $requestLog = APIRequestLog::factory()->create();

        $this->get(route('web.api-request-logs.show', $requestLog))
            ->assertOk()
            ->assertSee($requestLog->uuid);
    }

    public function test_uuid_search_shows_as_expected()
    {
        $this->login();

        $requestLogA = APIRequestLog::factory(['uuid' => 'uuid_foo'])->create();
        $requestLogB = APIRequestLog::factory()->create();

        $this->get(route('web.api-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'uuid' => 'uuid_foo'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_user_id_search_shows_as_expected()
    {
        $user = $this->login();

        $requestLogA = APIRequestLog::factory(['user_id' => $user->id])->create();
        $requestLogB = APIRequestLog::factory()->create();

        $this->get(route('web.api-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'user_id' => $user->id
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_request_type_search_shows_as_expected()
    {
        $this->login();

        $requestLogA = APIRequestLog::factory(['request_type' => 'bar'])->create();
        $requestLogB = APIRequestLog::factory()->create();

        $this->get(route('web.api-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'request_type' => 'bar'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_response_status_search_shows_as_expected()
    {
        $this->login();

        $requestLogA = APIRequestLog::factory(['response_status' => 'foo'])->create();
        $requestLogB = APIRequestLog::factory()->create();

        $this->get(route('web.api-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'response_status' => 'foo'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_duration_search_shows_as_expected()
    {
        $this->login();

        $requestLogA = APIRequestLog::factory(['duration' => '100'])->create();
        $requestLogB = APIRequestLog::factory(['duration' => '600'])->create();
        $requestLogC = APIRequestLog::factory(['duration' => '1500'])->create();

        $this->get(route('web.api-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'duration' => '1'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid)
            ->assertDontSee($requestLogC->uuid);

        $this->get(route('web.api-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'duration' => '2'
            ]))
            ->assertOk()
            ->assertDontSee($requestLogA->uuid)
            ->assertSee($requestLogB->uuid)
            ->assertDontSee($requestLogC->uuid);

        $this->get(route('web.api-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'duration' => '3'
            ]))
            ->assertOk()
            ->assertDontSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid)
            ->assertSee($requestLogC->uuid);
    }

    public function test_request_input_search_shows_as_expected()
    {
        $this->login();

        $requestLogA = APIRequestLog::factory(['request_input' => 'foo'])->create();
        $requestLogB = APIRequestLog::factory()->create();

        $this->get(route('web.api-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'request_input' => 'foo'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_request_output_search_shows_as_expected()
    {
        $this->login();

        $requestLogA = APIRequestLog::factory(['response_data' => 'foo'])->create();
        $requestLogB = APIRequestLog::factory()->create();

        $this->get(route('web.api-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'request_output' => 'foo'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_caller_number_search_shows_as_expected()
    {
        $this->login();

        $requestLogA = APIRequestLog::factory(['request_input' => ['caller_phone_number'=>'555-123-555']])->create();
        $requestLogB = APIRequestLog::factory()->create();

        $this->get(route('web.api-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'request_input' => '555'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_competition_phone_number_search_shows_as_expected()
    {
        $this->login();

        $requestLogA = APIRequestLog::factory(['request_input' => ['phone_number'=>'555-123-555']])->create();
        $requestLogB = APIRequestLog::factory()->create();

        $this->get(route('web.api-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'request_input' => '555'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }
}
