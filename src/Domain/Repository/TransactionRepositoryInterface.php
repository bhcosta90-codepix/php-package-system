<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Repository;

use CodePix\System\Domain\Entities\Transaction;

interface TransactionRepositoryInterface
{
    public function register(Transaction $transaction): bool;

    public function save(Transaction $transaction): bool;

    public function find(string $id): ?Transaction;

    public function findByDebit(string $id): ?Transaction;
}