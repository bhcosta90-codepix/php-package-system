<?php

declare(strict_types=1);

use CodePix\System\Application\UseCase\Account\DTO\Register\Input;
use CodePix\System\Application\UseCase\Account\Exception\AccountException;
use CodePix\System\Application\UseCase\Account\RegisterUseCase;

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
                'existThisCount' => fn() => false,
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

        $useCase = new RegisterUseCase(
            accountRepository: mockAccountRepository([
                'existThisCount' => fn() => true,
                'create' => [
                    'action' => fn() => true,
                    'times' => 0,
                ],
            ]),
        );

        expect(fn() => $useCase->handle($input))
            ->toThrow(new AccountException(message: 'This account already exists', code: 400));
    });
});