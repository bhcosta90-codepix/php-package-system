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

    protected function validated(): void
    {
        if ($this->accountFrom->id() == $this->pixKeyTo->account->id()) {
            $this->notification()->push(
                'account',
                'the source and destination account cannot be the same'
            );
        }

        parent::validated();
    }

    protected function rules(): array
    {
        return [
            'value' => 'numeric|min:0.01',
        ];
    }
}