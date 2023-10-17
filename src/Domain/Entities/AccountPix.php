<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Entities;

use CodePix\System\Domain\Entities\Enum\AccountPix\TypeAccountPix;
use Costa\Entity\Data;
use Costa\Entity\ValueObject\Uuid;

class AccountPix extends Data
{
    protected Uuid $bank;

    protected TypeAccountPix $key;

    protected string $value;
}