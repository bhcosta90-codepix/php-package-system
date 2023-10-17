<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase;

use CodePix\System\Application\Exception\NotFoundException;
use CodePix\System\Domain\Entities\Account;
use CodePix\System\Domain\Entities\PixKey;
use CodePix\System\Domain\Repository\PixKeyRepositoryInterface;
use Costa\Entity\ValueObject\Uuid;

class AccountUseCase
{
    public function __construct(
        protected PixKeyRepositoryInterface $pixKeyRepository,
    ) {
        //
    }

    public function register(string $bank, string $name, string $agency, string $number): Account
    {
        $account = new Account(
            name: $name,
            bank: new Uuid($bank),
            agency: $agency,
            number: $number
        );

        $this->pixKeyRepository->addAccount($account);

        return $account;
    }

    /**
     * @throws NotFoundException
     */
    public function find(string $id): Account
    {
        if (!$account = $this->pixKeyRepository->findAccount($id)) {
            throw new NotFoundException('Pix not found');
        }

        return $account;
    }
}