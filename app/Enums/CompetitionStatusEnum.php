<?php

namespace App\Enums;

enum CompetitionStatusEnum: string
{
    case OPEN_PRE_ANSWER = 'OPEN_PRE_ANSWER'; // not provided by telephony side - default starting point for active call


    // below are considered successful entries
    case COMP_OPEN_ANSWERED = 'COMP_OPEN_ANSWERED'; // charged so in the competition

    case COMP_OPEN_RECORDING = 'COMP_OPEN_RECORDING'; // in the competition

    case COMP_OPEN_COMPLETE = 'COMP_OPEN_COMPLETE'; //call completed successfully

    case EARLY_HANGUP_COMP_OPEN_ANSWERED = 'EARLY_HANGUP_COMP_OPEN_ANSWERED';  //caller hung up early but still considered a successful entry

    case EARLY_HANGUP_COMP_OPEN_RECORDING = 'EARLY_HANGUP_COMP_OPEN_RECORDING';

    case ABORTED_COMP_OPEN_RECORDING = 'ABORTED_COMP_OPEN_RECORDING'; // edge case where we want user to be entered

    case ABORTED_COMP_OPEN_ANSWERED = 'ABORTED_COMP_OPEN_ANSWERED'; // edge case where we want user to be entered
}
