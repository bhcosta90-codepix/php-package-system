<?php

declare(strict_types=1);

use CodePix\System\Application\Exception\BadRequestException;
use CodePix\System\Application\Exception\NotFoundException;
use CodePix\System\Application\Exception\UseCaseException;
use CodePix\System\Application\UseCase\PixUseCase;
use CodePix\System\Domain\Entities\Account;
use CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey;
use CodePix\System\Domain\Entities\PixKey;
use Costa\Entity\ValueObject\Uuid;

beforeEach(function(){
    $this->account = new Account(
        name: 'bruno costa',
        bank: Uuid::make(),
        agency: '0001',
        number: '0002',
    );
});

describe("PixUseCase Unit Test", function () {
    describe("Action - Register", function() {
        test("Creating a new pix", function () {
            $useCase = new PixUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface([
                    'findAccount' => fn() => $this->account,
                    'findKeyByKind' => fn() => null,
                    'register' => fn() => true,
                ])
            );

            $useCase->register('email', 'test@test.com', '90e4d7c0-6d08-11ee-b962-0242ac120002');
        });

        test("Exception - Find By Key", function () {
            $useCase = new PixUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface([
                    'findAccount' => fn() => null,
                ])
            );

            expect(fn() => $useCase->register('email', 'test@test.com', '90e4d7c0-6d08-11ee-b962-0242ac120002'))
                ->toThrow(
                    NotFoundException::class
                );
        });

        test("Exception - Not found account", function () {
            $useCase = new PixUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface([
                    'findAccount' => fn() => $this->account,
                    'findKeyByKind' => fn() => new PixKey(
                        bank: Uuid::make(),
                        kind: CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey::EMAIL,
                        account: $this->account,
                        key: 'test@test.com',
                    ),
                ])
            );

            expect(fn() => $useCase->register('email', 'test@test.com', '90e4d7c0-6d08-11ee-b962-0242ac120002'))
                ->toThrow(
                    BadRequestException::class
                );
        });

        test("Exception - Creating a new pix", function () {
            $useCase = new PixUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface([
                    'findAccount' => fn() => $this->account,
                    'findKeyByKind' => fn() => null,
                    'register' => fn() => false,
                ])
            );

            expect(fn() => $useCase->register('email', 'test@test.com', '90e4d7c0-6d08-11ee-b962-0242ac120002'))
                ->toThrow(
                    UseCaseException::class
                );
        });
    });

    describe("Action - Find", function(){
        test("Get a pix", function(){
            $useCase = new PixUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface([
                    'findKeyByKind' => fn() => new PixKey(
                        bank: Uuid::make(),
                        kind: KindPixKey::EMAIL,
                        account: $this->account,
                        key: 'test@test.com',
                    ),
                ])
            );

            $useCase->find('email', 'test@test.com');
        });

        test("Exception - Get a pix", function(){
            $useCase = new PixUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface([
                    'findKeyByKind' => fn() => null,
                ])
            );

            expect(fn() => $useCase->find('email', 'test@test.com'))->toThrow(NotFoundException::class);
        });
    });
});