<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase\Account;

use CodePix\System\Application\UseCase\Account\DTO\Register\Input;
use CodePix\System\Application\UseCase\Account\DTO\Register\Output;
use CodePix\System\Domain\Entities\Account;
use CodePix\System\Domain\Repository\AccountRepository;
use CodePix\System\Domain\Repository\UserRepository;
use Throwable;

class RegisterUseCase
{
    public function __construct(
        protected UserRepository $userRepository,
        protected AccountRepository $accountRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function handle(Input $input): Output
    {
        $agency = $this->userRepository->getAgencyByUser($input->user);

        do {
            $account = (string)rand(0000000, 9999999);
        } while ($this->accountRepository->verifyAccountWithAgency($agency, $account));

        $account = Account::from(
            name: $input->name,
            bank: $input->bank,
            agency: $agency,
            account: $account,
        );

        $this->accountRepository->create($account);

        return new Output(
            id: (string)$account->id,
            name: $account->name,
            bank: $account->bank
        );
    }
}