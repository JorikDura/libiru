<?php

declare(strict_types=1);

namespace App\Enums;

enum PersonRole: string
{
    case AUTHOR = 'author';
    case TRANSLATOR = 'translator';
}
