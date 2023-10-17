<?php

declare(strict_types=1);

namespace CodePix\System\Application\UseCase\Account\DTO\Account\Change;

use CodePix\System\Application\Responses\ResponseEnum;

class Output
{
    public function __construct(public bool $success, public ResponseEnum $status)
    {
    }
}