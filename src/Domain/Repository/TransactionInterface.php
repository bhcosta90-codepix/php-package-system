<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Repository;

use CodePix\System\Domain\Entities\Transaction;

interface TransactionInterface
{
    public function register(Transaction $transaction): bool;

    public function save(Transaction $transaction): bool;

    public function find(string $id): Transaction;
}