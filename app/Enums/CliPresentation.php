<?php

namespace App\Enums;

enum CliPresentation: int
{
    case PI_ALLOW_PRESENTATION = 0;

    case PI_RESTRICT_PRESENTATION = 2;

    case PI_NOT_AVAILABLE = 4;
}
