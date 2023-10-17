<?php

declare(strict_types=1);

use CodePix\System\Application\Exception\NotFoundException;
use CodePix\System\Application\Exception\UseCaseException;
use CodePix\System\Application\UseCase\TransactionUseCase;
use CodePix\System\Domain\Entities\Account;
use CodePix\System\Domain\Entities\Enum\Transaction\StatusTransaction;
use CodePix\System\Domain\Entities\PixKey;
use CodePix\System\Domain\Entities\Transaction;
use Costa\Entity\ValueObject\Uuid;

use function PHPUnit\Framework\assertEquals;

beforeEach(function () {
    $this->account = new Account(
        name: 'bruno costa',
        bank: Uuid::make(),
        agency: '0001',
        number: '0002',
    );

    $this->accountPix = new Account(
        name: 'bruno costa',
        bank: Uuid::make(),
        agency: '0001',
        number: '0002',
    );

    $this->pix = new PixKey(
        bank: Uuid::make(),
        kind: CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey::EMAIL,
        account: $this->accountPix,
        key: 'test@test.com',
    );

    $this->transaction = new Transaction(
        accountFrom: $this->account,
        value: 50,
        pixKeyTo: $this->pix,
        description: 'testing'
    );
});

describe("TransactionUseCase Unit Test", function () {
    test("Register a new transaction", function () {
        $useCase = new TransactionUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'findAccount' => fn() => $this->account,
                'findKeyByKind' => fn() => $this->pix,
            ]),
            transactionRepository: mockTransactionRepositoryInterface([
                'register' => fn() => true,
            ]),
        );

        $useCase->register($this->account->id(), 50, "email", "test@test.com", "testing");
    });

    test("Exception -> Register a new transaction", function () {
        $useCase = new TransactionUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'findAccount' => fn() => $this->account,
                'findKeyByKind' => fn() => $this->pix,
            ]),
            transactionRepository: mockTransactionRepositoryInterface([
                'register' => fn() => false,
            ]),
        );

        expect(fn() => $useCase->register($this->account->id(), 50, "email", "test@test.com", "testing"))->toThrow(
            UseCaseException::class
        );
    });

    test("Exception when do not account", function () {
        $useCase = new TransactionUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'findAccount' => fn() => null,
                'findKeyByKind' => [
                    'action' => fn() => $this->pix,
                    'times' => 0,
                ],
            ]),
            transactionRepository: mockTransactionRepositoryInterface([
                'register' => [
                    'action' => fn() => false,
                    'times' => 0,
                ],
            ]),
        );

        expect(fn() => $useCase->register($this->account->id(), 50, "email", "test@test.com", "testing"))->toThrow(
            NotFoundException::class
        );
    });

    test("Exception when do not pix", function () {
        $useCase = new TransactionUseCase(
            pixKeyRepository: mockPixKeyRepositoryInterface([
                'findAccount' => fn() => $this->account,
                'findKeyByKind' => fn() => null,
            ]),
            transactionRepository: mockTransactionRepositoryInterface([
                'register' => [
                    'action' => fn() => false,
                    'times' => 0,
                ],
            ]),
        );

        expect(fn() => $useCase->register($this->account->id(), 50, "email", "test@test.com", "testing"))->toThrow(
            NotFoundException::class
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
            );

            $response = $useCase->confirm("4990146a-6d0e-11ee-b962-0242ac120002");
            assertEquals(StatusTransaction::CONFIRMED, $response->status);
        });

        test("Exception - Find", function(){
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => null,
                ]),
            );

            expect(fn() => $useCase->confirm("4990146a-6d0e-11ee-b962-0242ac120002"))->toThrow(NotFoundException::class);
        });

        test("Exception - Register", function(){
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => $this->transaction,
                    'save' => fn() => false,
                ]),
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
            );

            $response = $useCase->complete("4990146a-6d0e-11ee-b962-0242ac120002");
            assertEquals(StatusTransaction::COMPLETED, $response->status);
        });

        test("Exception - Find", function(){
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => null,
                ]),
            );

            expect(fn() => $useCase->complete("4990146a-6d0e-11ee-b962-0242ac120002"))->toThrow(NotFoundException::class);
        });

        test("Exception - Register", function(){
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => $this->transaction,
                    'save' => fn() => false,
                ]),
            );

            expect(fn() => $useCase->complete("4990146a-6d0e-11ee-b962-0242ac120002"))->toThrow(UseCaseException::class);
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
            );

            $response = $useCase->error("4990146a-6d0e-11ee-b962-0242ac120002", "testing");
            assertEquals(StatusTransaction::ERROR, $response->status);
            assertEquals("testing", $response->cancelDescription);
        });

        test("Exception - Find", function(){
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => null,
                ]),
            );

            expect(fn() => $useCase->error("4990146a-6d0e-11ee-b962-0242ac120002", "testing"))->toThrow(NotFoundException::class);
        });

        test("Exception - Register", function(){
            $useCase = new TransactionUseCase(
                pixKeyRepository: mockPixKeyRepositoryInterface(),
                transactionRepository: mockTransactionRepositoryInterface([
                    "find" => fn() => $this->transaction,
                    'save' => fn() => false,
                ]),
            );

            expect(fn() => $useCase->error("4990146a-6d0e-11ee-b962-0242ac120002", "testing"))->toThrow(UseCaseException::class);
        });
    });
});