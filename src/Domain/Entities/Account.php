<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Entities;

use Costa\Entity\Data;

class Account extends Data
{
    public function __construct(
        protected string $name,
        protected Bank $bank,
        protected string $agency,
        protected string $number,
        /**
         * @var PixKey[]
         */
        protected array $pixKeys = [],
    ) {
        parent::__construct();
    }
}