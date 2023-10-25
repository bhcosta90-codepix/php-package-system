<?php

use CodePix\System\Domain\Entities\PixKey;
use CodePix\System\Domain\Entities\Transaction;
use CodePix\System\Domain\Events\Transaction\ConfirmationEvent;
use Costa\Entity\ValueObject\Uuid;

use function PHPUnit\Framework\assertEquals;

beforeEach(fn() => $this->transaction = new Transaction(
    debit: $this->debit = Uuid::make(),
    bank: $this->bank = Uuid::make(),
    accountFrom: new Uuid('018b68e0-0584-7241-be65-f2d7a580fec6'),
    value: 50,
    pixKeyTo: new PixKey(
        bank: Uuid::make(),
        account: $this->accountFrom = new Uuid('018b68e0-057e-72c5-a07a-3208fd99319f'),
        kind: CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey::EMAIL,
        key: 'test@test.com',
    ),
    description: 'testing'
));

describe("ConfirmationEvent Unit Test", function () {
    test("payload", function () {
        $event = new ConfirmationEvent($this->transaction);
        assertEquals([
            'bank' => (string) $this->bank,
            'id' => (string) $this->debit,
        ], $event->payload());
    });
});