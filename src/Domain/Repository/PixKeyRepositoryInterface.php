<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Repository;

use CodePix\System\Domain\Entities\Account;
use CodePix\System\Domain\Entities\Bank;
use CodePix\System\Domain\Entities\PixKey;

interface PixKeyRepositoryInterface
{
    public function register(PixKey $pixKey): bool;

    public function findKeyByKind(string $key, string $kind): ?PixKey;

    public function addBank(Bank $bank);

    public function addAccount(Account $account);

    public function findAccount(string $id): ?Account;
}