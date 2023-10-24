<?php

declare(strict_types=1);

use BRCas\CA\Contracts\Event\EventManagerInterface;
use CodePix\System\Application\Exception\NotFoundException;
use CodePix\System\Application\Exception\UseCaseException;
use CodePix\System\Application\UseCase\TransactionUseCase;
use CodePix\System\Domain\Entities\Enum\Transaction\StatusTransaction;
use CodePix\System\Domain\Entities\PixKey;
use CodePix\System\Domain\Entities\Transaction;
use Costa\Entity\ValueObject\Uuid;

use function PHPUnit\Framework\assertEquals;

beforeEach(function () {
    $this->pix = new PixKey(
        bank: Uuid::make(),
        account: Uuid::make(),
        kind: CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey::EMAIL,
        key: 'test@test.com',
    );

    $this->transaction = new Transaction(
        debit: Uuid::make(),
        bank: Uuid::make(),
        accountFrom: Uuid::make(),
        value: 50,
        pixKeyTo: $this->pix,
        description: 'testing'
    );
});

describe("TransactionUseCase Unit Test", function () {
    test("Register a new transaction", function () {
        $useCase = new TransactionUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'findKeyByKind' => fn() => $this->pix,
            ]),
            transactionRepository: mockTransactionRepositoryInterface([
                'register' => fn() => true,
            ]),
            eventManager: mockEventManager()
        );

        $useCase->register((string)Uuid::make(), (string)Uuid::make(), (string)Uuid::make(), 50, "email", "test@test.com", "testing");
    });

    test("Exception -> Register a new transaction", function () {
        $useCase = new TransactionUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'findKeyByKind' => fn() => $this->pix,
            ]),
            transactionRepository: mockTransactionRepositoryInterface([
                'register' => fn() => false,
            ]),
            eventManager: mockEventManager(0)
        );

        expect(fn() => $useCase->register((string)Uuid::make(), (string)Uuid::make(), (string)Uuid::make(), 50, "email", "test@test.com", "testing"))->toThrow(
            UseCaseException::class
        );
    });

    describe("Action - Confirmed", function () {
        test("Success", function () {
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => $this->transaction,
                    'save' => fn() => true,
                ]),
                eventManager: mockEventManager(0)
            );

            $response = $useCase->confirm("4990146a-6d0e-11ee-b962-0242ac120002");
            assertEquals(StatusTransaction::CONFIRMED, $response->status);
        });

        test("Exception - Find", function () {
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => null,
                ]),
                eventManager: mockEventManager(0)
            );

            expect(fn() => $useCase->confirm("4990146a-6d0e-11ee-b962-0242ac120002"))->toThrow(
                NotFoundException::class
            );
        });

        test("Exception - Register", function () {
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => $this->transaction,
                    'save' => fn() => false,
                ]),
                eventManager: mockEventManager(0)
            );

            expect(fn() => $useCase->confirm("4990146a-6d0e-11ee-b962-0242ac120002"))->toThrow(UseCaseException::class);
        });
    });

    describe("Action - complete", function () {
        test("Success", function () {
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => $this->transaction,
                    'save' => fn() => true,
                ]),
                eventManager: mockEventManager(0)
            );

            $response = $useCase->complete("4990146a-6d0e-11ee-b962-0242ac120002");
            assertEquals(StatusTransaction::COMPLETED, $response->status);
        });

        test("Exception - Find", function () {
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => null,
                ]),
                eventManager: mockEventManager(0)
            );

            expect(fn() => $useCase->complete("4990146a-6d0e-11ee-b962-0242ac120002"))->toThrow(
                NotFoundException::class
            );
        });

        test("Exception - Register", function () {
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => $this->transaction,
                    'save' => fn() => false,
                ]),
                eventManager: mockEventManager(0)
            );

            expect(fn() => $useCase->complete("4990146a-6d0e-11ee-b962-0242ac120002"))->toThrow(
                UseCaseException::class
            );
        });
    });

    describe("Action - error", function () {
        test("Success", function () {
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => $this->transaction,
                    'save' => fn() => true,
                ]),
                eventManager: mockEventManager(0)
            );

            $response = $useCase->error("4990146a-6d0e-11ee-b962-0242ac120002", "testing");
            assertEquals(StatusTransaction::ERROR, $response->status);
            assertEquals("testing", $response->cancelDescription);
        });

        test("Exception - Find", function () {
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => null,
                ]),
                eventManager: mockEventManager(0)
            );

            expect(fn() => $useCase->error("4990146a-6d0e-11ee-b962-0242ac120002", "testing"))->toThrow(
                NotFoundException::class
            );
        });

        test("Exception - Register", function () {
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => $this->transaction,
                    'save' => fn() => false,
                ]),
                eventManager: mockEventManager(0)
            );

            expect(fn() => $useCase->error("4990146a-6d0e-11ee-b962-0242ac120002", "testing"))->toThrow(
                UseCaseException::class
            );
        });
    });
});