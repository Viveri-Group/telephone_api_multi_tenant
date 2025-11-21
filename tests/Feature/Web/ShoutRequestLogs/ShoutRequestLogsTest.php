<?php

namespace Tests\Feature\Web\ShoutRequestLogs;

use App\Models\ActiveCallOrphan;
use App\Models\APIRequestLog;
use App\Models\MaxCapacityCallLog;
use App\Models\ShoutServerRequestLog;
use Tests\TestCase;

class ShoutRequestLogsTest extends TestCase
{
    public function test_page_redirects_as_expected()
    {
        $this->login();

        $this->get(route('web.shout-request-logs.index'))
            ->assertStatus(302);
    }

    public function test_page_shows_as_expected()
    {
        $this->login();

        $requestLog = ShoutServerRequestLog::factory()->create();

        $this->get(route('web.shout-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s')
            ]))
            ->assertOk()
            ->assertSee($requestLog->uuid);
    }

    public function test_identifier_search_works_as_expected()
    {
        $this->login();

        $requestLogA = ShoutServerRequestLog::factory(['identifier'=>'foo'])->create();
        $requestLogB = ShoutServerRequestLog::factory()->create();

        $this->get(route('web.shout-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'identifier'=>'foo'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_http_method_search_works_as_expected()
    {
        $this->login();

        $requestLogA = ShoutServerRequestLog::factory(['http_method'=>'POST'])->create();
        $requestLogB = ShoutServerRequestLog::factory(['http_method'=>'GET'])->create();

        $this->get(route('web.shout-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'http_method'=>'POST'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_url_search_works_as_expected()
    {
        $this->login();

        $requestLogA = ShoutServerRequestLog::factory(['url'=>'foo'])->create();
        $requestLogB = ShoutServerRequestLog::factory()->create();

        $this->get(route('web.shout-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'url'=>'foo'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_status_code_search_works_as_expected()
    {
        $this->login();

        $requestLogA = ShoutServerRequestLog::factory(['status_code'=>'200'])->create();
        $requestLogB = ShoutServerRequestLog::factory()->create();

        $this->get(route('web.shout-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'status_code'=>'200'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_request_type_search_works_as_expected()
    {
        $this->login();

        $requestLogA = ShoutServerRequestLog::factory(['request_type'=>'foo_request'])->create();
        $requestLogB = ShoutServerRequestLog::factory()->create();

        $this->get(route('web.shout-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'request_type'=>'foo_request'
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_attempts_search_works_as_expected()
    {
        $this->login();

        $requestLogA = ShoutServerRequestLog::factory(['attempts'=>5])->create();
        $requestLogB = ShoutServerRequestLog::factory(['attempts'=>4])->create();

        $this->get(route('web.shout-request-logs.index',
            [
                'date_from' => now()->startOfMonth()->subMonth()->format('Y-m-d\TH:i:s'),
                'date_to' => now()->endOfDay()->format('Y-m-d\TH:i:s'),
                'attempts'=>5
            ]))
            ->assertOk()
            ->assertSee($requestLogA->uuid)
            ->assertDontSee($requestLogB->uuid);
    }

    public function test_summary_page_shows_as_expected()
    {
        $this->login();

        $requestLog = ShoutServerRequestLog::factory()->create();

        $this->get(route('web.shout-request-logs.show', $requestLog))
            ->assertOk()
            ->assertSee($requestLog->uuid);
    }
}
