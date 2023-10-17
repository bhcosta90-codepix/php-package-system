<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Entities;

use Costa\Entity\Data;

class Bank extends Data
{
    public function __construct(
        protected string $code,
        protected string $name,
        /**
         * @var Account[]
         */
        protected array $accounts = [],
    ) {
        parent::__construct();
    }
}