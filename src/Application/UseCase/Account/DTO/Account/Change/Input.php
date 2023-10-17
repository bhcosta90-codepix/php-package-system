<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase\Account\DTO\Account\Change;

class Input
{
    public function __construct(
        public string $id,
        public string $bank,
    ) {
        //
    }
}