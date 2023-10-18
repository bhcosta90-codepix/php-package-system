<?php

declare(strict_types=1);

use CodePix\System\Application\Exception\BadRequestException;
use CodePix\System\Application\Exception\NotFoundException;
use CodePix\System\Application\UseCase\AccountUseCase;
use CodePix\System\Domain\Entities\Account;
use Costa\Entity\ValueObject\Uuid;

describe("AccountUseCase Unit Test", function () {
    test("Creating a new account", function () {
        $account = new AccountUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'addAccount' => fn() => true,
                'findAccountByBankAgencyNumber' => fn() => null,
            ]),
        );

        $account->register((string)Uuid::make(), "testing", "0001", "0002");
    });

    test("Exception - when the account already exist", function () {
        $account = new AccountUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'findAccountByBankAgencyNumber' => fn() => Uuid::make(),
            ]),
        );

        expect(fn() => $account->register((string)Uuid::make(), "testing", "0001", "0002"))->toThrow(
            BadRequestException::class
        );
    });

    test("Find account", function () {
        $account = new AccountUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'findAccount' => fn() => new Account(
                    name: 'testing',
                    bank: Uuid::make(),
                    agency: '0001',
                    number: '0002'
                ),
            ]),
        );

        $account->find((string)Uuid::make());
    });

    test("Find account - exception", function () {
        $account = new AccountUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'findAccount' => fn() => null,
            ]),
        );

        expect(fn() => $account->find((string)Uuid::make()))->toThrow(NotFoundException::class);
    });
});