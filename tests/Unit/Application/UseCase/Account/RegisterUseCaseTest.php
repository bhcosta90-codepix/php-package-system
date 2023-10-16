<?php

declare(strict_types=1);

use CodePix\System\Application\UseCase\Account\DTO\Register\Input;
use CodePix\System\Application\UseCase\Account\RegisterUseCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

describe("RegisterUseCase Unit Test", function () {
    test("handle", function () {
        $input = new Input(
            user: '1',
            name: 'testing',
            bank: '1'
        );

        $useCase = new RegisterUseCase(
            userRepository: mockUserRepository([
                'getAgencyByUser' => [
                    'action' => fn() => '1',
                    'with' => '1',
                ],
            ]),
            accountRepository: mockAccountRepository([
                'generateAccountByAgency' => [
                    'action' => fn() => '1',
                    'with' => '1',
                ],
                'verifyAccountWithAgency' => fn() => false,
                'create' => fn() => true,
            ]),
        );

        $response = $useCase->handle($input);

        assertNotNull($response->id);
        assertEquals($response->name, 'testing');
        assertNotNull($response->bank, '1');
    });
});