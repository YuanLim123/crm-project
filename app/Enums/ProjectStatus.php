<?php

namespace App\Enums;

enum ProjectStatus: String
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case BLOCKED = 'blocked';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
}

 