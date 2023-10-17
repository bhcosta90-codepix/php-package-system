<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Entities;

use CodePix\System\Domain\Entities\Enum\Transaction\StatusTransaction;
use Costa\Entity\Data;

class Transaction extends Data
{
    public function __construct(
        protected Account $accountFrom,
        protected float $value,
        protected PixKey $pixKeyTo,
        protected StatusTransaction $status = StatusTransaction::PENDING,
    ) {
        parent::__construct();
    }
}