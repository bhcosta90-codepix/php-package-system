<?php

declare(strict_types=1);

namespace Costa\Entity;

use Costa\Entity\Contracts\DataInterface;
use Costa\Entity\Contracts\ValueObjectInterface;
use Costa\Entity\Support\NotificationSupport;
use Costa\Entity\Support\ParameterSupport;
use Costa\Entity\Traits\MethodMagicTrait;
use Costa\Entity\Traits\ValidateTrait;
use Costa\Entity\ValueObject\Uuid;
use DateTime;
use DateTimeInterface;

abstract class Data implements DataInterface
{
    use ValidateTrait;
    use MethodMagicTrait;

    private NotificationSupport $notification;

    protected Uuid $id;

    protected DateTimeInterface $createdAt;

    protected DateTimeInterface $updatedAt;

    protected function __construct()
    {
        $this->notification = new NotificationSupport();
        $this->id = Uuid::make();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();

        if (method_exists($this, 'validated')) {
            $this->validated();
        }
    }

    public function id(): string
    {
        return (string)$this->id;
    }

    public function createdAt(): string
    {
        return (string)$this->createdAt->format('Y-m-d H:i:s');
    }

    public function updatedAt(): string
    {
        return (string)$this->updatedAt->format('Y-m-d H:i:s');
    }

    public static function make(mixed ...$payloads): static
    {
        $parameters = new ParameterSupport(static::class);

        $dataConstructor = [];
        foreach ($parameters->getConstructorProperties() as $property) {
            if (in_array($property['value'], array_keys($payloads))) {
                $dataConstructor[$property['value']] = $payloads[$property['value']];
            }
        }

        $obj = new static(...$dataConstructor);

        foreach ($payloads as $key => $payload) {
            if (in_array($key, ['id', 'updatedAt', 'createdAt'])) {
                $obj->{$key} = $payload;
            }
        }

        if (method_exists($obj, 'validated')) {
            $obj->validated();
        }

        return $obj;
    }

    public function toArray(): array
    {
        $parameters = new ParameterSupport(static::class);

        $response = [];
        foreach ($parameters->getProperties() as $property) {
            $valueProperty = $this->{$property['value']};

            if ($valueProperty instanceof DateTimeInterface) {
                $valueProperty = $valueProperty->format('Y-m-d H:i:s');
            }

            if ($valueProperty instanceof ValueObjectInterface) {
                $valueProperty = (string)$valueProperty;
            }

            if (is_array($valueProperty)) {
                $newDataValue = [];
                foreach ($valueProperty as $value) {
                    if ($value instanceof DataInterface) {
                        $newDataValue[] = $value->toArray();
                    }

                    if ($value instanceof ValueObjectInterface) {
                        $newDataValue[] = (string)$value;
                    }
                }
                $valueProperty = $newDataValue;
            }

            $response[$property['value']] = $valueProperty;
        }

        return $response;
    }

    protected function rules(): array
    {
        return [];
    }

    protected function notification(): NotificationSupport
    {
        return $this->notification;
    }
}