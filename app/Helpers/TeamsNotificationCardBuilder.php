<?php

namespace App\Helpers;

class TeamsNotificationCardBuilder
{
    protected array $card;

    public function __construct()
    {
        $this->card = [
            'type' => 'message',
            'attachments' => [
                [
                    'contentType' => 'application/vnd.microsoft.card.adaptive',
                    'content' => [
                        '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                        'type' => 'AdaptiveCard',
                        'version' => '1.4',
                        'body' => [],
                        'actions' => []
                    ]
                ]
            ]
        ];
    }

    /**
     * Add a status update block
     *
     * @param string $title
     * @param string $level low|medium|high
     * @param string|null $description
     * @return $this
     */
    public function status(string $title, string $level = 'low', string $description = null): self
    {
        $colorMap = [
            'low' => 'Good',       // Blue/green-ish
            'medium' => 'Warning', // Yellow
            'high' => 'Attention'  // Red
        ];

        $color = $colorMap[strtolower($level)] ?? 'Good';
        $descriptionText = $description ?? "This issue has a {$level} severity level.";

        $statusBlock = [
            [
                'type' => 'TextBlock',
                'size' => 'Large',
                'weight' => 'Bolder',
                'text' => $title,
                'wrap' => true
            ],
            [
                'type' => 'ColumnSet',
                'columns' => [
                    [
                        'type' => 'Column',
                        'width' => 'auto',
                        'items' => [
                            [
                                'type' => 'TextBlock',
                                'text' => strtoupper($level),
                                'color' => $color,
                                'weight' => 'Bolder',
                                'wrap' => true
                            ]
                        ]
                    ],
                    [
                        'type' => 'Column',
                        'width' => 'stretch',
                        'items' => [
                            [
                                'type' => 'TextBlock',
                                'text' => $descriptionText,
                                'isSubtle' => true,
                                'wrap' => true
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->card['attachments'][0]['content']['body'] = $statusBlock;

        return $this;
    }

    /**
     * Add a button to the card
     *
     * @param string $title
     * @param string $url
     * @return $this
     */
    public function actionButton(string $title, string $url): self
    {
        $this->card['attachments'][0]['content']['actions'][] = [
            'type' => 'Action.OpenUrl',
            'title' => $title,
            'url' => $url
        ];

        return $this;
    }

    /**
     * Get the final card array
     *
     * @return array
     */
    public function build(): array
    {
        return $this->card;
    }
}
