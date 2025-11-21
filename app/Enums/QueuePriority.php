<?php

namespace App\Enums;

enum QueuePriority: string
{
    case Low = 'low';

    case High = 'high';
}
