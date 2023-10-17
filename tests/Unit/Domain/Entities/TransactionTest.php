<?php

declare(strict_types=1);

use CodePix\System\Domain\Entities\Account;
use CodePix\System\Domain\Entities\Bank;
use CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey;
use CodePix\System\Domain\Entities\PixKey;
use CodePix\System\Domain\Entities\Transaction;

use Costa\Entity\Contracts\DataInterface;

use Costa\Entity\Exceptions\NotificationException;

use function PHPUnit\Framework\assertInstanceOf;

describe("Transaction Unit Test", function () {
    beforeEach(function () {
        $this->bank = new Bank(code: '001', name: 'testing');
        $this->account = new Account(
            name: 'testing',
            bank: $this->bank,
            agency: '0001',
            number: '0001'
        );

        $this->accountPix = new Account(
            name: 'testing',
            bank: $this->bank,
            agency: '0001',
            number: '0001'
        );

        $this->pixKeyTo = new PixKey(
            bank: $this->bank,
            kind: KindPixKey::EMAIL,
            account: $this->accountPix,
            key: 'testing@test.com'
        );
    });

    it("Creating a new transaction", function () {
        $transaction = new Transaction(
            accountFrom: $this->account,
            value: 50,
            pixKeyTo: $this->pixKeyTo,
        );
        assertInstanceOf(DataInterface::class, $transaction);
    });

    it("Creating a new transaction with the same account", function () {
        $pix = new PixKey(
            bank: $this->bank,
            kind: KindPixKey::EMAIL,
            account: $this->account,
            key: 'testing@test.com'
        );

        expect(fn() => new Transaction(
            accountFrom: $this->account,
            value: 50,
            pixKeyTo: $pix,
        ))->toThrow(new NotificationException('account: the source and destination account cannot be the same'));
    });

    it("Creating a new transaction with the value is zero", fn() => expect(fn() => new Transaction(
        accountFrom: $this->account,
        value: 0.00,
        pixKeyTo: $this->pixKeyTo,
    ))->toThrow(new NotificationException(Transaction::class . ': The Value minimum is 0.01')));

    it("Creating a new transaction with the value is negative", fn() => expect(fn() => new Transaction(
        accountFrom: $this->account,
        value: -1,
        pixKeyTo: $this->pixKeyTo,
    ))->toThrow(new NotificationException(Transaction::class . ': The Value minimum is 0.01')));
});