<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Repository;

use CodePix\System\Domain\Entities\PixKey;

interface PixKeyRepositoryInterface
{
    public function register(PixKey $pixKey): bool;

    public function findKeyByKind(string $kind, string $key): ?PixKey;
}
