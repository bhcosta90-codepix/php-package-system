<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Entities;

use CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey;
use Costa\Entity\Data;
use Costa\Entity\ValueObject\Uuid;

class PixKey extends Data
{
    public function __construct(
        protected Uuid $bank,
        protected Uuid $account,
        protected KindPixKey $kind,
        protected string $key,
        protected bool $status = true,
    ) {
        parent::__construct();
    }
}