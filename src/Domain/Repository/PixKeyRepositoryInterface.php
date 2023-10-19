<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Repository;

use CodePix\System\Domain\Entities\Account;
use CodePix\System\Domain\Entities\PixKey;
use Costa\Entity\ValueObject\Uuid;

interface PixKeyRepositoryInterface
{
    public function register(PixKey $pixKey): bool;

    public function findKeyByKind(string $kind, string $key): ?PixKey;
}
