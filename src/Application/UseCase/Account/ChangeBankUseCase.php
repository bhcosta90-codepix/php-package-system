<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase\Account;

use CodePix\System\Application\Exception\NotFoundException;
use CodePix\System\Application\Responses\ResponseEnum;
use CodePix\System\Application\UseCase\Account\DTO\Account\Change\Input;
use CodePix\System\Application\UseCase\Account\DTO\Account\Change\Output;
use CodePix\System\Domain\Repository\AccountPixRepository;
use Throwable;

class ChangeBankUseCase
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
        if (!$account = $this->accountRepository->findById($input->id)) {
            throw new NotFoundException('This pix account do not found');
        }

        $success = false;

        if ($input->bank != (string)$input->bank) {
            $account->changeBank($input->bank);
            $success = $this->accountRepository->update($account);
        }

        return new Output(
            success: $success,
            status: ResponseEnum::OK,
        );
    }
}