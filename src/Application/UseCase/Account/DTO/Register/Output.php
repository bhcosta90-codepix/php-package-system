<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase\Account\DTO\Register;

use CodePix\System\Application\Responses\ResponseEnum;

class Output
{
    public function __construct(public string $id, public ResponseEnum $status)
    {
    }
}