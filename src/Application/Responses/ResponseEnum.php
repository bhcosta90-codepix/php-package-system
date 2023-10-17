<?php

declare(strict_types=1);

namespace CodePix\System\Application\Responses;

enum ResponseEnum: int
{
    case OK = 200;

    case CREATE = 201;
}
