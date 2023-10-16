<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase\Account\DTO\Register;

class Input
{
    public function __construct(public string $user, public string $name, public string $bank)
    {

    }
}