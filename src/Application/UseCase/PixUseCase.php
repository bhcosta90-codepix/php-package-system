<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase;

use CodePix\System\Application\Exception\EntityException;
use CodePix\System\Application\Exception\NotFoundException;
use CodePix\System\Application\Exception\UseCaseException;
use CodePix\System\Domain\Entities\Enum\PixKey\KindPixKey;
use CodePix\System\Domain\Entities\PixKey;
use CodePix\System\Domain\Repository\PixKeyRepositoryInterface;
use Costa\Entity\Exceptions\NotificationException;
use Costa\Entity\ValueObject\Uuid;

class PixUseCase
{
    public function __construct(
        protected PixKeyRepositoryInterface $pixKeyRepository,
    ) {
        //
    }

    /**
     * @throws UseCaseException
     * @throws EntityException
     * @throws NotificationException
     */
    public function register(string $bank, string $account, string $kind, string $key): PixKey
    {
        if ($this->pixKeyRepository->findKeyByKind($kind, $key)) {
            throw new EntityException("Entity already exist");
        }

        $pix = new PixKey(
            bank: new Uuid($bank),
            account: new Uuid($account),
            kind: KindPixKey::from($kind),
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
