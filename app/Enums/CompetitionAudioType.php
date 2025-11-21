<?php

namespace App\Enums;

use ArchTech\Enums\From;
use ArchTech\Enums\Names;

enum CompetitionAudioType: string
{
    use Names, From;

    case PRE_EVENT = 'PRE_EVENT';

    case POST_EVENT = 'POST_EVENT';

    case FN1 = 'FN1';

    case FN2 = 'FN2';

    case FN3 = 'FN3';

    case FN4 = 'FN4';

    case FN5 = 'FN5';

    case FN6 = 'FN6';

    case CALL_COST_WARNING = 'CALL_COST_WARNING'; // 1_50

    case CAPPING_MESSAGE = 'CAPPING_MESSAGE'; // 1_50


    case CALL_COST_WARNING_2_00 = 'CALL_COST_WARNING_2_00';

    case CAPPING_MESSAGE_2_00 = 'CAPPING_MESSAGE_2_00';


    case CALL_COST_WARNING_2_50 = 'CALL_COST_WARNING_2_50';

    case CAPPING_MESSAGE_2_50 = 'CAPPING_MESSAGE_2_50';
}
