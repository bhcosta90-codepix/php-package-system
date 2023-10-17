<?php

declare(strict_types=1);

namespace Tests\Stubs;

use Costa\Entity\Data;
use Costa\Entity\Traits\ValidateTrait;
use Costa\Entity\ValueObject\Uuid;

class CustomerStub extends Data
{
    public function __construct(
        protected string $name,
        /**
         * @var AddressStub[]
         */
        protected array $address = [],
        /**
         * @var Uuid
         */
        protected array $orders = [],
    ) {
        parent::__construct();
    }


    protected function rules(): array
    {
        return [
            'name' => 'required|min:3|max:100',
        ];
    }
}