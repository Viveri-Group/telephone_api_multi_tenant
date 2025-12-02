<?php

namespace App\Enums;

use ArchTech\Enums\From;
use ArchTech\Enums\Names;

enum CompetitionAudioType: string
{
    use Names, From;

    case INTRO = 'INTRO';

    case CLI_READOUT_NOTICE = 'CLI_READOUT_NOTICE';

    case DTMF_MENU = 'DTMF_MENU';

    case DTMF_SUCCESS = 'DTMF_SUCCESS';

    case DTMF_SUCCESS_SMS = 'DTMF_SUCCESS_SMS';

    case DTMF_FAIL = 'DTMF_FAIL';

    case COMPETITION_CLOSED = 'COMPETITION_CLOSED';

    case TOO_MANY_ENTRIES = 'TOO_MANY_ENTRIES';
}
