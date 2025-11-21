<?php

namespace Tests\Unit\Action\Helpers;

use App\Action\Helpers\RegexIDInArrayAction;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RegexIDInArrayActionTest extends TestCase
{
    protected RegexIDInArrayAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new RegexIDInArrayAction();
    }

    #[DataProvider('handleDataProvider')]
    public function test_handle(string $needle, array $haystack, bool $expected)
    {
        $this->assertSame($expected, $this->action->handle($haystack, $needle));
    }

    public static function handleDataProvider(): array
    {
        return [
            'matches pattern with {id}' => [
                '/api/download/competition/45/entrants',
                ['/api/download/competition/{id}/entrants'],
                true,
            ],
            'matches when multiple patterns' => [
                '/api/users/123/profile',
                [
                    '/api/download/competition/{id}/entrants',
                    '/api/users/{id}/profile',
                ],
                true,
            ],
            'does not match if no pattern matches' => [
                '/api/export/45/summary',
                ['/api/download/competition/{id}/entrants'],
                false,
            ],
            'does not match if id is not numeric' => [
                '/api/download/competition/abc/entrants',
                ['/api/download/competition/{id}/entrants'],
                false,
            ],
            'matches exact pattern without {id}' => [
                '/api/static/path',
                ['/api/static/path'],
                true,
            ],
            'returns false for empty haystack' => [
                '/api/download/competition/45/entrants',
                [],
                false,
            ],
        ];
    }
}
