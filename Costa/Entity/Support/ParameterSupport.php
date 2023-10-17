<?php

declare(strict_types=1);

namespace Costa\Entity\Support;

use ReflectionClass;
use ReflectionParameter;

final class ParameterSupport
{
    public function __construct(protected object|string $class)
    {
        //
    }

    /**
     * @return ReflectionParameter[]
     */
    public function getConstructorProperties(): array
    {
        $reflectionClass = $this->getReflectionClass();
        $constructor = $reflectionClass->getConstructor();
        return array_map(fn($property) => [
            'value' => $property->getName(),
            'type' => $property->getType()->getName(),
        ], $constructor->getParameters());
    }

    public function getProperties(): array
    {
        $reflectionClass = $this->getReflectionClass();
        return array_map(fn($property) => [
            'value' => $property->getName(),
            'type' => $property->getType()->getName(),
        ], $reflectionClass->getProperties());
    }

    /**
     * @return ReflectionClass
     */
    public function getReflectionClass(): ReflectionClass
    {
        return new ReflectionClass($this->class);
    }
}