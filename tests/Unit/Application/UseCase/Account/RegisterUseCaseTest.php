<?php

declare(strict_types=1);

use CodePix\System\Application\UseCase\Account\DTO\Register\Input;
use CodePix\System\Application\UseCase\Account\RegisterUseCase;
use CodePix\System\Domain\Entities\Account;
use Costa\Entity\ValueObject\Uuid;

use function PHPUnit\Framework\assertNotNull;

describe("RegisterUseCase Unit Test", function () {
    test("create a new account", function () {
        $input = new Input(
            bank: '1',
            agency: '1',
            number: '1',
            account: 'testing'
        );

        $useCase = new RegisterUseCase(
            accountRepository: mockAccountRepository([
                'findAccount' => fn() => null,
                'create' => fn() => true,
            ]),
        );

        $response = $useCase->handle($input);

        assertNotNull($response->id);
    });

    test("exception -> create a new account", function () {
        $input = new Input(
            bank: '1',
            agency: '1',
            number: '1',
            account: 'testing'
        );

        $id = Uuid::make();

        $useCase = new RegisterUseCase(
            accountRepository: mockAccountRepository([
                'findAccount' => fn() => Account::from(
                    id: $id,
                    bank: '1',
                    agency: '1',
                    account: 'testing'
                ),
                'create' => [
                    'action' => fn() => true,
                    'times' => 0,
                ],
            ]),
        );

        $response = $useCase->handle($input);
        expect($response->id)->toBe((string)$id);
    });
});