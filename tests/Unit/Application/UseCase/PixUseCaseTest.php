<?php

declare(strict_types=1);

use CodePix\System\Application\Exception\EntityException;
use CodePix\System\Application\Exception\NotFoundException;
use CodePix\System\Application\Exception\UseCaseException;
use CodePix\System\Application\UseCase\PixUseCase;
use CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey;
use CodePix\System\Domain\Entities\PixKey;
use Costa\Entity\ValueObject\Uuid;

beforeEach(function () {
    $this->account = Uuid::make();
});

describe("PixUseCase Unit Test", function () {
    describe("Action - Register", function () {
        test("Creating a new pix", function () {
            $useCase = new PixUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface([
                    'findKeyByKind' => fn() => null,
                    'register' => fn() => true,
                ])
            );

            $useCase->register((string)Uuid::make(), (string)Uuid::make(), 'email', 'test@test.com');
        });

        test("Exception - Register error", function () {
            $useCase = new PixUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface([
                    'findKeyByKind' => fn() => null,
                    'register' => fn() => false,
                ])
            );

            expect(fn() => $useCase->register((string)Uuid::make(), (string)Uuid::make(), 'email', 'test@test.com')
            )->toThrow(UseCaseException::class);
        });

        test("Exception - Not found account", function () {
            $useCase = new PixUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface([
                    'findKeyByKind' => fn() => new PixKey(
                        bank: Uuid::make(),
                        account: Uuid::make(),
                        kind: CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey::EMAIL,
                        key: 'test@test.com',
                    ),
                ])
            );

            expect(
                fn() => $useCase->register(
                    '90e4d7c0-6d08-11ee-b962-0242ac120003',
                    'email',
                    'test@test.com',
                    '90e4d7c0-6d08-11ee-b962-0242ac120002'
                )
            )
                ->toThrow(
                    EntityException::class
                );
        });
    });

    describe("Action - Find", function () {
        test("Get a pix", function () {
            $useCase = new PixUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface([
                    'findKeyByKind' => fn() => new PixKey(
                        bank: Uuid::make(),
                        account: Uuid::make(),
                        kind: KindPixKey::EMAIL,
                        key: 'test@test.com',
                    ),
                ])
            );

            $useCase->find('email', 'test@test.com');
        });

        test("Exception - Get a pix", function () {
            $useCase = new PixUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface([
                    'findKeyByKind' => fn() => null,
                ])
            );

            expect(fn() => $useCase->find('email', 'test@test.com'))->toThrow(NotFoundException::class);
        });
    });
});