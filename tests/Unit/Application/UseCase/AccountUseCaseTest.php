<?php

declare(strict_types=1);

use CodePix\System\Application\Exception\NotFoundException;
use CodePix\System\Application\UseCase\AccountUseCase;
use CodePix\System\Domain\Entities\Account;
use Costa\Entity\ValueObject\Uuid;

describe("AccountUseCase Unit Test", function () {
    test("Creating a new account", function () {
        $account = new AccountUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'addAccount' => fn() => true,
            ]),
        );

        $account->register((string)Uuid::make(), "testing", "0001", "0002");
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