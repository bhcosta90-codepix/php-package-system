<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase;

use CodePix\System\Application\Exception\NotFoundException;
use CodePix\System\Application\Exception\UseCaseException;
use CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey;
use CodePix\System\Domain\Entities\PixKey;
use CodePix\System\Domain\Entities\Transaction;
use CodePix\System\Domain\Repository\PixKeyRepositoryInterface;
use CodePix\System\Domain\Repository\TransactionRepositoryInterface;
use Costa\Entity\ValueObject\Uuid;

class TransactionUseCase
{
    public function __construct(
        protected PixKeyRepositoryInterface $pixKeyRepository,
        protected TransactionRepositoryInterface $transactionRepository,
    ) {
        //
    }

    /**
     * @throws NotFoundException
     * @throws UseCaseException
     */
    public function register(string $account, float $value, string $kind, string $key, string $description): Transaction
    {
        if (!$account = $this->pixKeyRepository->findAccount($account)) {
            throw new NotFoundException('Account not found');
        }

        if (!$pix = $this->pixKeyRepository->findKeyByKind($kind, $key)) {
            throw new NotFoundException('Pix not found');
        }


        $transaction = new Transaction(
            accountFrom: $account,
            value: $value,
            pixKeyTo: $pix,
            description: $description,
        );

        $response = $this->transactionRepository->register($transaction);

        if (!$response) {
            throw new UseCaseException();
        }

        return $transaction;
    }

    /**
     * @throws NotFoundException
     */
    public function confirm(string $id): Transaction
    {
        if (!$transaction = $this->transactionRepository->find($id)) {
            throw new NotFoundException('Transaction not found');
        }

        $transaction->confirmed();
        $this->transactionRepository->save($transaction);

        return $transaction;
    }

    /**
     * @throws NotFoundException
     */
    public function complete(string $id): Transaction
    {
        if (!$transaction = $this->transactionRepository->find($id)) {
            throw new NotFoundException('Transaction not found');
        }

        $transaction->complete();
        $this->transactionRepository->save($transaction);

        return $transaction;
    }

    /**
     * @throws NotFoundException
     */
    public function error(string $id, string $description): Transaction
    {
        if (!$transaction = $this->transactionRepository->find($id)) {
            throw new NotFoundException('Transaction not found');
        }

        $transaction->error($description);
        $this->transactionRepository->save($transaction);

        return $transaction;
    }
}