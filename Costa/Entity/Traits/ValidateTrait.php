<?php

declare(strict_types=1);

namespace Costa\Entity\Traits;

use Costa\Entity\Exceptions\NotificationException;
use Costa\Entity\Factory\ValidatorFactory;
use Costa\Entity\Support\NotificationSupport;

trait ValidateTrait
{
    public abstract function toArray();

    protected abstract function notification(): NotificationSupport;

    protected abstract function rules(): array;

    /**
     * @throws NotificationException
     */
    protected function validated(): void
    {
        $data = $this->toArray();

        if ($errors = ValidatorFactory::make($data, $this->rules())) {
            array_map(fn($error) => $this->notification()->push(static::class, $error), $errors);
        }

        if ($this->notification()->has()) {
            throw new NotificationException($this->notification()->messages());
        }
    }

}