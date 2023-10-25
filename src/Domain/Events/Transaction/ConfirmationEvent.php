<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Events\Transaction;

use CodePix\System\Domain\Entities\Transaction;
use Costa\Entity\Contracts\EventInterface;

class ConfirmationEvent implements EventInterface
{
    public function __construct(protected Transaction $transaction)
    {
        //
    }

    public function payload(): array
    {
        return [
            'bank' => (string) $this->transaction->bank,
            'id' => (string) $this->transaction->debit,
        ];
    }


}