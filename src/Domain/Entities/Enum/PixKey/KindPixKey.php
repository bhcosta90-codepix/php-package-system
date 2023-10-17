<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Entities\Enum\PixKey;

enum KindPixKey: string
{
    case EMAIL = 'email';
    case PHONE = 'phone';

    case DOCUMENT = 'document';

    case ID = 'id';
}