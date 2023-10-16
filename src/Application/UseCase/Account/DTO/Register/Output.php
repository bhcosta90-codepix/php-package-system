<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase\Account\DTO\Register;

class Output
{
    public function __construct(public string $id, public string $name, public string $bank)
    {
    }
}