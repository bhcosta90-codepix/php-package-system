<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Entities;

use CodePix\System\Domain\Entities\Enum\Transaction\StatusTransaction;
use CodePix\System\Domain\Events\Transaction\CreateEvent;
use Costa\Entity\Data;
use Costa\Entity\ValueObject\Uuid;

class Transaction extends Data
{
    public function __construct(
        protected Uuid $debit,
        protected Uuid $bank,
        protected Uuid $accountFrom,
        protected float $value,
        protected PixKey $pixKeyTo,
        protected string $description,
        protected StatusTransaction $status = StatusTransaction::PENDING,
        protected ?string $cancelDescription = null,
    ) {
        parent::__construct();
        $this->addEvent(new CreateEvent($this));
    }

    public function confirmed(): void
    {
        $this->status = StatusTransaction::CONFIRMED;
    }

    public function complete(): void
    {
        $this->status = StatusTransaction::COMPLETED;
    }

    public function error($description): void
    {
        $this->status = StatusTransaction::ERROR;
        $this->cancelDescription = $description;
    }

    protected function validated(): void
    {
        if ($this->accountFrom == $this->pixKeyTo->account) {
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