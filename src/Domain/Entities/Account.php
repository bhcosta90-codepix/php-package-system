<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Entities;

use Costa\Entity\Data;

class Account extends Data
{
    protected string $name;

    protected string $bank;


    protected string $agency;

    protected string $account;
}