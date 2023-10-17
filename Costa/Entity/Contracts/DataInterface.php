<?php

declare(strict_types=1);

namespace Costa\Entity\Contracts;

interface DataInterface
{
    public static function make(mixed ...$payloads): static;
}