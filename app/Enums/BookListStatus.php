<?php

declare(strict_types=1);

namespace App\Enums;

enum BookListStatus: string
{
    case PLANNED = 'planned';
    case READING = 'reading';
    case READ = 'read';
    case DROPPED = 'dropped';
}
