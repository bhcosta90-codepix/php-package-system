<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase\Account;

use CodePix\System\Application\Responses\ResponseEnum;
use CodePix\System\Application\UseCase\Account\DTO\Register\Input;
use CodePix\System\Application\UseCase\Account\DTO\Register\Output;
use CodePix\System\Domain\Entities\AccountPix;
use CodePix\System\Domain\Entities\Enum\AccountPix\TypeAccountPix;
use CodePix\System\Domain\Repository\AccountPixRepository;
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
        if ($account = $this->accountRepository->find(TypeAccountPix::from($input->key), $input->value)) {
            return new Output(
                id: (string)$account->id,
                status: ResponseEnum::OK
            );
        }

        $account = AccountPix::from(
            key: TypeAccountPix::from($input->key),
            value: $input->value,
        );

        $this->accountRepository->create($account);

        return new Output(
            id: (string)$account->id,
            status: ResponseEnum::CREATE
        );
    }
}