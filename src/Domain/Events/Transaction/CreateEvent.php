<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Events\Transaction;

use CodePix\System\Domain\Entities\Transaction;
use Costa\Entity\Contracts\EventInterface;

class CreateEvent implements EventInterface
{
    public function __construct(protected Transaction $transaction)
    {
    }

    public function payload(): array
    {
        return $this->transaction->toArray();
    }


}