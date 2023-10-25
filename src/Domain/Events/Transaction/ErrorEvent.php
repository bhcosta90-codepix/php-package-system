<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Events\Transaction;

use Costa\Entity\Contracts\EventInterface;

class ErrorEvent implements EventInterface
{
    public function __construct(protected string $id, protected string $message){

    }
    public function payload(): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
        ];
    }

}