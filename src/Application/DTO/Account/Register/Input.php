<?php

declare(strict_types=1);

namespace CodePix\System\Application\DTO\Account\Register;

class Input
{
    public function __construct(
        public string $bank,
        public string $key,
        public string $value,
    ) {
        //
    }
}