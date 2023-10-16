<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Repository;

use BRCas\CA\Contracts\Items\ItemInterface;
use BRCas\CA\Contracts\Repository\RepositoryInterface;
use CodePix\System\Domain\Entities\Account;
use CodePix\System\Domain\Entities\AccountPix;

interface AccountRepository extends RepositoryInterface
{
    public function verifyAccountWithAgency(string $agency, string $account): bool;

    public function itemsPix(Account $account): ItemInterface;

    public function addPix(AccountPix $accountPix): bool;

    public function removePix(string $id): bool;
}