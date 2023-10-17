<?php

declare(strict_types=1);

use CodePix\System\Application\DTO\Account\Register\Input;
use CodePix\System\Application\UseCase\Account\RegisterUseCase;
use CodePix\System\Domain\Entities\AccountPix;
use CodePix\System\Domain\Entities\Enum\AccountPix\TypeAccountPix;
use Costa\Entity\ValueObject\Uuid;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

describe("RegisterUseCase Unit Test", function () {
    test("create a new account", function () {
        $input = new Input(
            bank: '0ab4b766-6c8a-11ee-b962-0242ac120002',
            key: 'email',
            value: 'test@test.com.br'
        );

        $useCase = new RegisterUseCase(
            accountRepository: mockAccountPixRepository([
                'find' => fn() => null,
                'create' => fn() => true,
            ]),
        );

        $response = $useCase->handle($input);

        assertNotNull($response->id);
        assertEquals($response->status->value, 201);
    });

    test("exception -> create a new account", function () {
        $input = new Input(
            bank: '0ab4b766-6c8a-11ee-b962-0242ac120002',
            key: 'email',
            value: 'test@test.com.br'
        );

        $id = Uuid::make();

        $useCase = new RegisterUseCase(
            accountRepository: mockAccountPixRepository([
                'find' => fn() => AccountPix::from(
                    key: TypeAccountPix::EMAIL,
                    value: 'test@test.com.br',
                    id: $id,
                    bank: new Uuid('0ab4b766-6c8a-11ee-b962-0242ac120002')
                ),
                'create' => [
                    'action' => fn() => true,
                    'times' => 0,
                ],
            ]),
        );

        $response = $useCase->handle($input);

        assertEquals($response->id, (string)$id);
        assertEquals($response->status->value, 200);
    });
});