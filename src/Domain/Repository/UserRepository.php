<?php

declare(strict_types=1);

namespace CodePix\System\Domain\Repository;
interface UserRepository
{
    public function getAgencyByUser($id): string;
}