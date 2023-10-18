<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase;

use CodePix\System\Application\Exception\BadRequestException;
use CodePix\System\Application\Exception\NotFoundException;
use CodePix\System\Application\Exception\UseCaseException;
use CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey;
use CodePix\System\Domain\Entities\PixKey;
use CodePix\System\Domain\Repository\PixKeyRepositoryInterface;
use Costa\Entity\ValueObject\Uuid;

class PixUseCase
{
    public function __construct(
        protected PixKeyRepositoryInterface $pixKeyRepository,
    ) {
        //
    }

    /**
     * @throws NotFoundException
     * @throws UseCaseException
     * @throws BadRequestException
     */
    public function register(string $kind, string $key, string $account): PixKey
    {
        if (!$account = $this->pixKeyRepository->findAccount($account)) {
            throw new NotFoundException('Account not found');
        }

        if($this->pixKeyRepository->findKeyByKind($kind, $key)){
            throw new BadRequestException();
        }

        $pix = new PixKey(
            bank: $account->bank,
            kind: KindPixKey::from($kind),
            account: $account,
            key: $key,
        );

        $response = $this->pixKeyRepository->register($pix);

        if (!$response) {
            throw new UseCaseException();
        }

        return $pix;
    }

    /**
     * @throws NotFoundException
     */
    public function find(string $kind, string $key): PixKey
    {
        if (!$pix = $this->pixKeyRepository->findKeyByKind($kind, $key)) {
            throw new NotFoundException('Pix not found');
        }

        return $pix;
    }
}
