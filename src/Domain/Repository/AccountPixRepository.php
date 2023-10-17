<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Repository;

use CodePix\System\Domain\Entities\AccountPix;
use CodePix\System\Domain\Entities\Enum\AccountPix\TypeAccountPix;

interface AccountPixRepository
{
    public function create(AccountPix $account): bool;

    public function update(AccountPix $account): bool;

    public function find(TypeAccountPix $type, string $value): ?AccountPix;
    public function findById(string $id): ?AccountPix;
}