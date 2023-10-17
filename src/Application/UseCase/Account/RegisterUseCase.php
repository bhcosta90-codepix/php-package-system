<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase\Account;

use CodePix\System\Application\DTO\Account\Register\Input;
use CodePix\System\Application\DTO\Account\Register\Output;
use CodePix\System\Application\Responses\ResponseEnum;
use CodePix\System\Domain\Entities\AccountPix;
use CodePix\System\Domain\Entities\Enum\AccountPix\TypeAccountPix;
use CodePix\System\Domain\Repository\AccountPixRepository;
use Costa\Entity\ValueObject\Uuid;
use Throwable;

class RegisterUseCase
{
    public function __construct(
        protected AccountPixRepository $accountRepository,
    ) {
        //
    }

    /**
     * @throws Throwable
     */
    public function handle(Input $input): Output
    {
        if ($account = $this->accountRepository->find(
            type: TypeAccountPix::from($input->key),
            value: $input->value
        )) {
            return new Output(
                id: (string)$account->id,
                bank: (string)$account->bank,
                status: ResponseEnum::OK,
            );
        }

        $account = AccountPix::from(
            key: TypeAccountPix::from($input->key),
            value: $input->value,
            bank: new Uuid($input->bank)
        );

        $this->accountRepository->create($account);

        return new Output(
            id: (string)$account->id,
            bank: (string)$account->bank,
            status: ResponseEnum::CREATE
        );
    }
}