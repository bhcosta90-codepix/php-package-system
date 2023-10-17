<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase\Account;

use CodePix\System\Application\UseCase\Account\DTO\Register\Input;
use CodePix\System\Application\UseCase\Account\DTO\Register\Output;
use CodePix\System\Domain\Entities\Account;
use CodePix\System\Domain\Repository\AccountRepository;
use Throwable;

class RegisterUseCase
{
    public function __construct(
        protected AccountRepository $accountRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function handle(Input $input): Output
    {
        if ($account = $this->accountRepository->findAccount(
            bank: $input->bank,
            agency: $input->agency,
            account: $input->account
        )) {
            return new Output(
                id: (string)$account->id,
            );
        }

        $account = Account::from(
            bank: $input->bank,
            agency: $input->agency,
            account: $input->number,
        );

        $this->accountRepository->create($account);

        return new Output(
            id: (string)$account->id,
        );
    }
}