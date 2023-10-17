<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Entities;

use Costa\Entity\Data;
use Costa\Entity\ValueObject\Uuid;

class Account extends Data
{
    /**
     * @var PixKey[]
     */
    protected array $pixKeys = [];

    public function __construct(
        protected string $name,
        protected Uuid $bank,
        protected string $agency,
        protected string $number,
    ) {
        parent::__construct();
    }
}