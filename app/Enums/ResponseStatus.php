<?php

namespace App\Enums;

enum ResponseStatus: string
{
    case OPEN = 'OPEN';

    case PRE_OPEN = 'PRE_OPEN';

    case TOO_MANY = 'TOO_MANY';

    case CLOSED = 'CLOSED';

    case REJECT_CALLER = 'REJECT_CALLER';
}
