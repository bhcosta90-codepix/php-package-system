<?php

declare(strict_types=1);

use CodePix\System\Domain\Entities\Account;
use CodePix\System\Domain\Entities\Bank;
use CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey;
use CodePix\System\Domain\Entities\Enum\Transaction\StatusTransaction;
use CodePix\System\Domain\Entities\PixKey;
use CodePix\System\Domain\Entities\Transaction;

use Costa\Entity\Contracts\DataInterface;

use Costa\Entity\Exceptions\NotificationException;

use Costa\Entity\ValueObject\Uuid;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

describe("Transaction Unit Test", function () {
    beforeEach(function () {
        $this->bank = Uuid::make();

        $this->pixKeyTo = new PixKey(
            bank: $this->bank,
            account: $this->account = Uuid::make(),
            kind: KindPixKey::EMAIL,
            key: 'testing@test.com'
        );
    });

    test("Creating a new transaction", function () {
        $transaction = new Transaction(
            accountFrom: Uuid::make(),
            value: 50,
            pixKeyTo: $this->pixKeyTo,
            description: 'testing',
        );
        assertInstanceOf(DataInterface::class, $transaction);
        assertEquals($transaction->status, StatusTransaction::PENDING);
    });

    test("Creating a new transaction with the same account", function () {
        $pix = new PixKey(
            bank: $this->bank,
            account: $this->account,
            kind: KindPixKey::EMAIL,
            key: 'testing@test.com'
        );

        expect(fn() => new Transaction(
            accountFrom: $this->account,
            value: 50,
            pixKeyTo: $pix,
            description: 'testing',
        ))->toThrow(new NotificationException('account: the source and destination account cannot be the same'));
    });

    test("Creating a new transaction with the value is zero", fn() => expect(fn() => new Transaction(
        accountFrom: Uuid::make(),
        value: 0.00,
        pixKeyTo: $this->pixKeyTo,
        description: 'testing',
    ))->toThrow(new NotificationException(Transaction::class . ': The Value minimum is 0.01')));
//
    test("Creating a new transaction with the value is negative", fn() => expect(fn() => new Transaction(
        accountFrom: Uuid::make(),
        value: -1,
        pixKeyTo: $this->pixKeyTo,
        description: 'testing',
    ))->toThrow(new NotificationException(Transaction::class . ': The Value minimum is 0.01')));

    test("Confirmation a transaction", function () {
        $transaction = new Transaction(
            accountFrom: Uuid::make(),
            value: 50,
            pixKeyTo: $this->pixKeyTo,
            description: 'testing',
        );

        $transaction->confirmed();
        assertEquals(StatusTransaction::CONFIRMED, $transaction->status);
    });

    test("Complete a transaction", function () {
        $transaction = new Transaction(
            accountFrom: Uuid::make(),
            value: 50,
            pixKeyTo: $this->pixKeyTo,
            description: 'testing',
        );

        $transaction->complete();
        assertEquals(StatusTransaction::COMPLETED, $transaction->status);
    });

    test("Error a transaction", function () {
        $transaction = new Transaction(
            accountFrom: Uuid::make(),
            value: 50,
            pixKeyTo: $this->pixKeyTo,
            description: 'testing',
        );

        $transaction->error('testing');
        assertEquals(StatusTransaction::ERROR, $transaction->status);
        assertEquals('testing', $transaction->cancelDescription);
    });
});