<?php

declare(strict_types=1);

use CodePix\System\Application\Exception\UseCaseException;
use CodePix\System\Application\UseCase\PixUseCase;
use CodePix\System\Domain\Entities\Account;
use Costa\Entity\ValueObject\Uuid;

describe("PixUseCase Unit Test", function () {
    test("Creating a new pix", function () {
        $useCase = new PixUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'findAccount' => fn() => new Account(
                    name: 'bruno costa',
                    bank: Uuid::make(),
                    agency: '0001',
                    number: '0002',
                ),
                'register' => fn() => true,
            ])
        );

        $useCase->register('email', 'test@test.com', '90e4d7c0-6d08-11ee-b962-0242ac120002');
    });

    test("Exception - Creating a new pix", function () {
        $useCase = new PixUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'findAccount' => fn() => new Account(
                    name: 'bruno costa',
                    bank: Uuid::make(),
                    agency: '0001',
                    number: '0002',
                ),
                'register' => fn() => false,
            ])
        );

        expect(fn() => $useCase->register('email', 'test@test.com', '90e4d7c0-6d08-11ee-b962-0242ac120002'))
            ->toThrow(
                UseCaseException::class
            );
    });
});