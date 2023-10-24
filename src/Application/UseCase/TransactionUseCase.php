<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase;

use BRCas\CA\Contracts\Event\EventManagerInterface;
use CodePix\System\Application\Exception\NotFoundException;
use CodePix\System\Application\Exception\UseCaseException;
use CodePix\System\Domain\Entities\Transaction;
use CodePix\System\Domain\Repository\PixKeyRepositoryInterface;
use CodePix\System\Domain\Repository\TransactionRepositoryInterface;
use Costa\Entity\Exceptions\NotificationException;
use Costa\Entity\ValueObject\Uuid;

class TransactionUseCase
{
    public function __construct(
        protected PixKeyRepositoryInterface $pixKeyRepository,
        protected TransactionRepositoryInterface $transactionRepository,
        protected EventManagerInterface $eventManager,
    ) {
        //
    }

    /**
     * @throws NotFoundException
     * @throws UseCaseException
     * @throws NotificationException
     */
    public function register(string $debit, string $bank, string $account, float $value, string $kind, string $key, string $description): Transaction
    {
        if (!$pix = $this->pixKeyRepository->findKeyByKind($kind, $key)) {
            throw new NotFoundException('Pix not found');
        }

        $transaction = new Transaction(
            debit: new Uuid($debit),
            bank: new Uuid($bank),
            accountFrom: new Uuid($account),
            value: $value,
            pixKeyTo: $pix,
            description: $description,
        );

        $response = $this->transactionRepository->register($transaction);

        if (!$response) {
            throw new UseCaseException();
        }

        $this->eventManager->dispatch($transaction->getEvents());

        return $transaction;
    }

    /**
     * @throws NotFoundException
     * @throws UseCaseException
     */
    public function confirm(string $id): Transaction
    {
        if (!$transaction = $this->transactionRepository->find($id)) {
            throw new NotFoundException('Transaction not found');
        }

        $transaction->confirmed();
        $response = $this->transactionRepository->save($transaction);

        if (!$response) {
            throw new UseCaseException();
        }

        return $transaction;
    }

    /**
     * @throws NotFoundException
     * @throws UseCaseException
     */
    public function complete(string $id): Transaction
    {
        if (!$transaction = $this->transactionRepository->find($id)) {
            throw new NotFoundException('Transaction not found');
        }

        $transaction->complete();
        $response = $this->transactionRepository->save($transaction);

        if (!$response) {
            throw new UseCaseException();
        }

        return $transaction;
    }

    /**
     * @throws NotFoundException
     * @throws UseCaseException
     */
    public function error(string $id, string $description): Transaction
    {
        if (!$transaction = $this->transactionRepository->find($id)) {
            throw new NotFoundException('Transaction not found');
        }

        $transaction->error($description);
        $response = $this->transactionRepository->save($transaction);

        if (!$response) {
            throw new UseCaseException();
        }

        return $transaction;
    }
}