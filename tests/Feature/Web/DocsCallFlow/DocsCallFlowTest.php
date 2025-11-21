<?php

namespace Tests\Feature\Web\DocsCallFlow;

use App\Models\FailedEntry;
use Tests\TestCase;

class DocsCallFlowTest extends TestCase
{
    public function test_page_displays_as_expected()
    {
        $this->login();


        $this->get(route('web.docs.call-flow'))
            ->assertOk();
    }
}
